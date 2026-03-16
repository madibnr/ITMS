<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetMaintenanceRequest;
use App\Http\Requests\UpdateAssetMaintenanceRequest;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Services\AssetMaintenanceService;
use Illuminate\Http\Request;

class AssetMaintenanceController extends Controller
{
    public function __construct(private AssetMaintenanceService $maintenanceService)
    {
    }

    public function index(Request $request)
    {
        $maintenance = $this->maintenanceService->list($request->all());
        $assets = Asset::orderBy('name')->get();

        return view('asset-maintenance.index', compact('maintenance', 'assets'));
    }

    public function create(Request $request)
    {
        $assets = Asset::orderBy('name')->get();
        $selectedAssetId = $request->get('asset_id');

        return view('asset-maintenance.create', compact('assets', 'selectedAssetId'));
    }

    public function store(StoreAssetMaintenanceRequest $request)
    {
        $this->maintenanceService->create($request->validated(), $request->user());

        return redirect()->route('asset-maintenance.index')
            ->with('success', 'Maintenance record created.');
    }

    public function edit(AssetMaintenance $assetMaintenance)
    {
        $assets = Asset::orderBy('name')->get();
        return view('asset-maintenance.edit', compact('assetMaintenance', 'assets'));
    }

    public function update(UpdateAssetMaintenanceRequest $request, AssetMaintenance $assetMaintenance)
    {
        $this->maintenanceService->update($assetMaintenance, $request->validated(), $request->user());

        return redirect()->route('asset-maintenance.index')
            ->with('success', 'Maintenance record updated.');
    }

    public function destroy(AssetMaintenance $assetMaintenance)
    {
        $this->maintenanceService->delete($assetMaintenance);

        return redirect()->route('asset-maintenance.index')
            ->with('success', 'Maintenance record deleted.');
    }
}
