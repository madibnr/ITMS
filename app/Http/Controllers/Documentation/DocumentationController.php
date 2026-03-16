<?php

namespace App\Http\Controllers\Documentation;

use App\Exports\DocumentationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentationRequest;
use App\Http\Requests\UpdateDocumentationRequest;
use App\Models\Documentation;
use App\Models\DocumentationCategory;
use App\Models\DocumentationTag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DocumentationController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = Documentation::with(['category', 'tags', 'creator']);

        // Only non-admin/manager/it-staff see published only
        if (!auth()->user()->hasAnyRole(['admin', 'manager', 'it-staff'])) {
            $query->published();
        }

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($categoryId = $request->get('category_id')) {
            $query->byCategory($categoryId);
        }

        if ($tagId = $request->get('tag_id')) {
            $query->byTag($tagId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $documentations = $query->latest()->paginate(15)->withQueryString();
        $categories     = DocumentationCategory::orderBy('name')->get();
        $tags           = DocumentationTag::orderBy('name')->get();
        $totalCount     = Documentation::published()->count();
        $recentDocs     = Documentation::with('category')->latest()->take(5)->get();

        return view('documentation.index', compact(
            'documentations', 'categories', 'tags', 'totalCount', 'recentDocs'
        ));
    }

    public function create()
    {
        $this->authorize('create', Documentation::class);

        $categories = DocumentationCategory::orderBy('name')->get();
        $tags       = DocumentationTag::orderBy('name')->get();

        return view('documentation.create', compact('categories', 'tags'));
    }

    public function store(StoreDocumentationRequest $request)
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $data['attachment']               = $file->store('documentation/attachments', 'public');
            $data['attachment_original_name'] = $file->getClientOriginalName();
        }

        $data['created_by'] = auth()->id();

        $documentation = Documentation::create($data);

        // Sync tags
        if (!empty($data['tags'])) {
            $documentation->tags()->sync($data['tags']);
        }

        // Save meta fields
        if (!empty($data['meta'])) {
            foreach ($data['meta'] as $key => $value) {
                if (!empty($value)) {
                    $documentation->meta()->create(['key' => $key, 'value' => $value]);
                }
            }
        }

        return redirect()
            ->route('docs.show', $documentation)
            ->with('success', 'Documentation created successfully.');
    }

    public function show(Documentation $documentation)
    {
        $this->authorize('view', $documentation);

        $documentation->load(['category', 'tags', 'creator', 'updater', 'meta']);

        return view('documentation.show', compact('documentation'));
    }

    public function edit(Documentation $documentation)
    {
        $this->authorize('update', $documentation);

        $documentation->load(['tags', 'meta']);
        $categories = DocumentationCategory::orderBy('name')->get();
        $tags       = DocumentationTag::orderBy('name')->get();

        return view('documentation.edit', compact('documentation', 'categories', 'tags'));
    }

    public function update(UpdateDocumentationRequest $request, Documentation $documentation)
    {
        $this->authorize('update', $documentation);

        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file
            if ($documentation->attachment) {
                Storage::disk('public')->delete($documentation->attachment);
            }
            $file = $request->file('attachment');
            $data['attachment']               = $file->store('documentation/attachments', 'public');
            $data['attachment_original_name'] = $file->getClientOriginalName();
        } else {
            unset($data['attachment']);
        }

        $data['updated_by'] = auth()->id();

        $documentation->update($data);

        // Sync tags
        $documentation->tags()->sync($data['tags'] ?? []);

        // Update meta fields
        $documentation->meta()->delete();
        if (!empty($data['meta'])) {
            foreach ($data['meta'] as $key => $value) {
                if (!empty($value)) {
                    $documentation->meta()->create(['key' => $key, 'value' => $value]);
                }
            }
        }

        return redirect()
            ->route('docs.show', $documentation)
            ->with('success', 'Documentation updated successfully.');
    }

    public function destroy(Documentation $documentation)
    {
        $this->authorize('delete', $documentation);

        if ($documentation->attachment) {
            Storage::disk('public')->delete($documentation->attachment);
        }

        $documentation->delete();

        return redirect()
            ->route('docs.index')
            ->with('success', 'Documentation deleted.');
    }

    public function export(Request $request)
    {
        $this->authorize('export', Documentation::class);

        return Excel::download(
            new DocumentationsExport($request->all()),
            'documentations-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function download(Documentation $documentation)
    {
        $this->authorize('view', $documentation);

        if (!$documentation->attachment || !Storage::disk('public')->exists($documentation->attachment)) {
            return back()->with('error', 'Attachment file not found.');
        }

        return Storage::disk('public')->download(
            $documentation->attachment,
            $documentation->attachment_original_name ?? basename($documentation->attachment)
        );
    }
}
