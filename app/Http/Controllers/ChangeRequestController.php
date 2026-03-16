<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChangeRequestRequest;
use App\Http\Requests\UpdateChangeRequestRequest;
use App\Models\ChangeRequest;
use App\Services\ChangeRequestService;
use Illuminate\Http\Request;

class ChangeRequestController extends Controller
{
    public function __construct(private
        ChangeRequestService $changeRequestService,
        )
    {
    }

    public function index(Request $request)
    {
        $changeRequests = $this->changeRequestService->list($request->all());
        return view('change-requests.index', compact('changeRequests'));
    }

    public function create()
    {
        return view('change-requests.create');
    }

    public function store(StoreChangeRequestRequest $request)
    {
        $cr = $this->changeRequestService->create($request->validated(), $request->user());

        return redirect()->route('change-requests.show', $cr)
            ->with('success', 'Change Request created. Number: ' . $cr->change_number);
    }

    public function show(ChangeRequest $changeRequest)
    {
        $changeRequest->load(['requester', 'approver']);
        return view('change-requests.show', compact('changeRequest'));
    }

    public function edit(ChangeRequest $changeRequest)
    {
        return view('change-requests.edit', compact('changeRequest'));
    }

    public function update(UpdateChangeRequestRequest $request, ChangeRequest $changeRequest)
    {
        $this->changeRequestService->update($changeRequest, $request->validated());

        return redirect()->route('change-requests.show', $changeRequest)
            ->with('success', 'Change Request updated.');
    }

    public function destroy(ChangeRequest $changeRequest)
    {
        $changeRequest->delete();
        return redirect()->route('change-requests.index')->with('success', 'Change Request deleted.');
    }

    public function submit(ChangeRequest $changeRequest)
    {
        $this->changeRequestService->submit($changeRequest);
        return redirect()->route('change-requests.show', $changeRequest)->with('success', 'Change Request submitted for approval.');
    }

    public function approve(ChangeRequest $changeRequest, Request $request)
    {
        $this->changeRequestService->approve($changeRequest, $request->user());
        return redirect()->route('change-requests.show', $changeRequest)->with('success', 'Change Request approved.');
    }

    public function reject(ChangeRequest $changeRequest, Request $request)
    {
        $this->changeRequestService->reject($changeRequest, $request->user());
        return redirect()->route('change-requests.show', $changeRequest)->with('success', 'Change Request rejected.');
    }

    public function implement(ChangeRequest $changeRequest)
    {
        $this->changeRequestService->implement($changeRequest);
        return redirect()->route('change-requests.show', $changeRequest)->with('success', 'Change Request marked as implemented.');
    }

    public function export(Request $request)
    {
        return (new \App\Exports\ChangeRequestsExport($request->all()))
            ->download('change-requests-export-' . now()->format('Y-m-d') . '.xlsx');
    }
}
