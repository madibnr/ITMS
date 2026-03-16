<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetModelRequest;
use App\Http\Requests\UpdateAssetModelRequest;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class AssetModelController extends Controller
{
    public function index(Request $request)
    {
        $models = AssetModel::with(['manufacturer', 'category'])
            ->withCount('assets')
            ->when($request->search, fn($q, $v) => $q->where('model_name', 'like', "%{$v}%"))
            ->when($request->manufacturer_id, fn($q, $v) => $q->where('manufacturer_id', $v))
            ->latest()
            ->paginate(15);

        $manufacturers = Manufacturer::orderBy('name')->get();

        return view('asset-models.index', compact('models', 'manufacturers'));
    }

    public function create()
    {
        $manufacturers = Manufacturer::orderBy('name')->get();
        $categories = Category::assetCategories()->get();

        return view('asset-models.create', compact('manufacturers', 'categories'));
    }

    public function store(StoreAssetModelRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('asset-models', 'public');
        }

        AssetModel::create($data);

        return redirect()->route('asset-models.index')
            ->with('success', 'Asset model created successfully.');
    }

    public function edit(AssetModel $assetModel)
    {
        $manufacturers = Manufacturer::orderBy('name')->get();
        $categories = Category::assetCategories()->get();

        return view('asset-models.edit', compact('assetModel', 'manufacturers', 'categories'));
    }

    public function update(UpdateAssetModelRequest $request, AssetModel $assetModel)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('asset-models', 'public');
        }

        $assetModel->update($data);

        return redirect()->route('asset-models.index')
            ->with('success', 'Asset model updated successfully.');
    }

    public function destroy(AssetModel $assetModel)
    {
        $assetModel->delete();

        return redirect()->route('asset-models.index')
            ->with('success', 'Asset model deleted successfully.');
    }
}
