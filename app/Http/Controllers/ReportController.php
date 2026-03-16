<?php

namespace App\Http\Controllers;

use App\Exports\ReportTicketsExport;
use App\Services\AssetService;
use App\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(
        private
        TicketService $ticketService,
        private
        AssetService $assetService,
    ) {
    }

    public function ticketReport(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'category_id', 'priority', 'assigned_to', 'status']);
        $tickets = $this->ticketService->list($filters, 50);
        $slaPerformance = $this->ticketService->getSlaPerformance(
            $request->date_from,
            $request->date_to,
        );

        return view('reports.tickets', compact('tickets', 'slaPerformance', 'filters'));
    }

    public function assetReport()
    {
        $summary = $this->assetService->getSummaryReport();
        return view('reports.assets', compact('summary'));
    }

    public function slaReport(Request $request)
    {
        $sla = $this->ticketService->getSlaPerformance(
            $request->date_from,
            $request->date_to,
        );
        return view('reports.sla', compact('sla'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ReportTicketsExport($request->all()),
            'report-tickets-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
