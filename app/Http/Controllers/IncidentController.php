<?php

namespace App\Http\Controllers;

use App\Exports\IncidentsExport;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentRequest;
use App\Models\Asset;
use App\Models\Incident;
use App\Models\Ticket;
use App\Models\User;
use App\Services\IncidentService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IncidentController extends Controller
{
    public function __construct(private
        IncidentService $incidentService,
        )
    {
    }

    public function index(Request $request)
    {
        $incidents = $this->incidentService->list($request->all());
        return view('incidents.index', compact('incidents'));
    }

    public function create()
    {
        $assets = Asset::active()->get();
        $tickets = Ticket::open()->get();
        $technicians = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();

        return view('incidents.create', compact('assets', 'tickets', 'technicians'));
    }

    public function store(StoreIncidentRequest $request)
    {
        $incident = $this->incidentService->create($request->validated(), $request->user());

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Incident reported. Number: ' . $incident->incident_number);
    }

    public function show(Incident $incident)
    {
        $incident->load(['reporter', 'assignee', 'relatedAsset', 'relatedTicket', 'rootCauseAnalysis.analyst']);
        return view('incidents.show', compact('incident'));
    }

    public function edit(Incident $incident)
    {
        $assets = Asset::active()->get();
        $tickets = Ticket::all();
        $technicians = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();

        return view('incidents.edit', compact('incident', 'assets', 'tickets', 'technicians'));
    }

    public function update(UpdateIncidentRequest $request, Incident $incident)
    {
        $this->incidentService->update($incident, $request->validated());

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Incident updated.');
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();
        return redirect()->route('incidents.index')->with('success', 'Incident deleted.');
    }

    public function createRca(Request $request, Incident $incident)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'root_cause' => 'required|string',
            'contributing_factors' => 'nullable|string',
            'corrective_action' => 'required|string',
            'preventive_action' => 'nullable|string',
        ]);

        $this->incidentService->createRca($incident, $data, $request->user());

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Root Cause Analysis created.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new IncidentsExport($request->all()),
            'incidents-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
