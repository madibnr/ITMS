<?php

namespace App\Http\Controllers;

use App\Exports\AssetsExport;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\StoreAssetAssignmentRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Imports\AssetsImport;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use App\Services\AssetService;
use App\Services\AssetAssignmentService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    public function __construct(
        private AssetService $assetService,
        private AssetAssignmentService $assignmentService,
    ) {
    }

    public function index(Request $request)
    {
        $assets = $this->assetService->list($request->all());
        $categories = Category::assetCategories()->get();
        $locations = Location::roots()->get();
        $summary = $this->assetService->getDashboardSummary();

        return view('assets.index', compact('assets', 'categories', 'locations', 'summary'));
    }

    public function create()
    {
        $categories = Category::assetCategories()->get();
        $users = User::where('is_active', true)->get();
        $assetModels = AssetModel::with('manufacturer')->get();
        $locations = Location::all();

        return view('assets.create', compact('categories', 'users', 'assetModels', 'locations'));
    }

    public function store(StoreAssetRequest $request)
    {
        $data = $request->validated();
        $category = Category::find($data['category_id']);
        $data['category_name'] = $category->name ?? 'GEN';

        $asset = $this->assetService->create($data, $request->user());

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Asset created. Tag: ' . $asset->asset_tag);
    }

    public function show(Asset $asset)
    {
        $asset->load([
            'category',
            'assignedUser',
            'assetModel.manufacturer',
            'locationRef',
            'histories.performer',
            'maintenanceSchedules',
            'assignments.assignedTo',
            'assignments.assignedByUser',
            'maintenanceLogs',
            'files.uploader',
            'auditLogs.user',
        ]);

        $depreciation = $asset->calculateDepreciation();

        return view('assets.show', compact('asset', 'depreciation'));
    }

    public function edit(Asset $asset)
    {
        $categories = Category::assetCategories()->get();
        $users = User::where('is_active', true)->get();
        $assetModels = AssetModel::with('manufacturer')->get();
        $locations = Location::all();

        return view('assets.edit', compact('asset', 'categories', 'users', 'assetModels', 'locations'));
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $this->assetService->update($asset, $request->validated(), $request->user());

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Asset updated successfully.');
    }

    public function updateTag(Request $request, Asset $asset)
    {
        $request->validate([
            'asset_tag' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('assets', 'asset_tag')->ignore($asset->id),
            ],
        ]);

        $oldTag = $asset->asset_tag;
        $asset->update(['asset_tag' => $request->asset_tag]);

        // Log the change if an audit/history mechanism exists
        if (method_exists($asset, 'histories')) {
            try {
                $asset->histories()->create([
                    'action'   => 'asset_tag_changed',
                    'note'     => "Asset tag changed from [{$oldTag}] to [{$request->asset_tag}]",
                    'user_id'  => auth()->id(),
                ]);
            } catch (\Throwable $e) {
                // silently ignore if history table schema differs
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'asset_tag' => $asset->asset_tag]);
        }

        return back()->with('success', "Asset tag berhasil diubah menjadi {$request->asset_tag}.");
    }

    public function destroy(Asset $asset)
    {
        $this->assetService->delete($asset, request()->user());
        return redirect()->route('assets.index')->with('success', 'Asset deleted.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new AssetsExport($request->all()),
            'assets-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ─── Asset Tag Format Settings ──────────────────────────────

    /** Path for the JSON settings file */
    private static function tagFormatPath(): string
    {
        return storage_path('app/asset_tag_format.json');
    }

    /** Read current format settings with defaults */
    public static function getTagFormat(): array
    {
        $path = static::tagFormatPath();
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (is_array($data)) return $data;
        }
        return [
            'prefix'      => 'ITMS',
            'separator'   => '-',
            'digits'      => 6,
            'next_number' => null, // null = auto-detect from DB
        ];
    }

    public function tagFormatSettings(Request $request)
    {
        $fmt = static::getTagFormat();

        // Auto-calculate next number from DB if not overridden
        if ($fmt['next_number'] === null) {
            $prefix    = strtoupper($fmt['prefix']);
            $sep       = $fmt['separator'];
            $like      = "like \"{$prefix}{$sep}%\"";
            $last      = \App\Models\Asset::whereNotNull('asset_tag')
                ->where('asset_tag', 'like', "{$prefix}{$sep}%")
                ->orderByDesc('asset_tag')
                ->first();
            $fmt['computed_next'] = $last
                ? (int) substr($last->asset_tag, -(int)$fmt['digits']) + 1
                : 1;
        } else {
            $fmt['computed_next'] = (int) $fmt['next_number'];
        }

        return response()->json($fmt);
    }

    public function tagFormatSettingsSave(Request $request)
    {
        $validated = $request->validate([
            'prefix'      => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/'],
            'separator'   => ['required', 'in:-,/,_,'],
            'digits'      => ['required', 'integer', 'min:3', 'max:8'],
            'next_number' => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['prefix'] = strtoupper($validated['prefix']);

        file_put_contents(static::tagFormatPath(), json_encode($validated, JSON_PRETTY_PRINT));

        // Preview the format
        $preview = $this->buildTagPreview(
            $validated['prefix'],
            $validated['separator'],
            (int) $validated['digits'],
            $validated['next_number'] ?? 1
        );

        return response()->json(['success' => true, 'preview' => $preview]);
    }

    public function tagFormatPreview(Request $request)
    {
        $preview = $this->buildTagPreview(
            strtoupper($request->get('prefix', 'ITMS')),
            $request->get('separator', '-'),
            max(3, min(8, (int) $request->get('digits', 6))),
            max(1, (int) $request->get('next', 1))
        );
        return response()->json(['preview' => $preview]);
    }

    private function buildTagPreview(string $prefix, string $separator, int $digits, int $next): string
    {
        return $prefix . $separator . str_pad($next, $digits, '0', STR_PAD_LEFT);
    }

    public function qrcode(Asset $asset)
    {
        $qrCode = $this->assetService->generateQrCode($asset, 250);
        return view('assets.qrcode', compact('asset', 'qrCode'));
    }

    public function importForm()
    {
        return view('assets.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        Excel::import(new AssetsImport, $request->file('file'));

        return redirect()->route('assets.index')
            ->with('success', 'Assets imported successfully.');
    }

    public function assign(StoreAssetAssignmentRequest $request, Asset $asset)
    {
        $this->assignmentService->assign($asset, $request->validated(), $request->user());

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Asset assigned successfully.');
    }

    public function returnAsset(Asset $asset)
    {
        $this->assignmentService->returnAsset($asset, request()->user());

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Asset returned successfully.');
    }
}
