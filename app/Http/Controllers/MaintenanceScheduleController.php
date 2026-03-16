<?php

namespace App\Http\Controllers;

use App\Exports\MaintenanceExport;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\Asset;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Services\MaintenanceService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MaintenanceScheduleController extends Controller
{
    public function __construct(private
        MaintenanceService $maintenanceService,
        )
    {
    }

    public function index(Request $request)
    {
        $schedules = $this->maintenanceService->list($request->all());
        return view('maintenance.index', compact('schedules'));
    }

    public function create()
    {
        $assets = Asset::active()->get();
        $technicians = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();

        return view('maintenance.create', compact('assets', 'technicians'));
    }

    public function store(StoreMaintenanceRequest $request)
    {
        $schedule = $this->maintenanceService->create($request->validated());

        return redirect()->route('maintenance.show', $schedule)
            ->with('success', 'Maintenance schedule created.');
    }

    public function show(MaintenanceSchedule $maintenance)
    {
        $maintenance->load(['asset', 'assignee']);
        return view('maintenance.show', ['schedule' => $maintenance]);
    }

    public function edit(MaintenanceSchedule $maintenance)
    {
        $assets = Asset::active()->get();
        $technicians = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();

        return view('maintenance.edit', ['schedule' => $maintenance, 'assets' => $assets, 'technicians' => $technicians]);
    }

    public function update(UpdateMaintenanceRequest $request, MaintenanceSchedule $maintenance)
    {
        $this->maintenanceService->update($maintenance, $request->validated());

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Maintenance schedule updated.');
    }

    public function destroy(MaintenanceSchedule $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')->with('success', 'Maintenance schedule deleted.');
    }

    public function complete(MaintenanceSchedule $maintenance)
    {
        $this->maintenanceService->complete($maintenance);
        return redirect()->route('maintenance.show', $maintenance)->with('success', 'Maintenance completed.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new MaintenanceExport($request->all()),
            'maintenance-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
