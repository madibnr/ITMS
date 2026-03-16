<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use App\Services\TicketService;
use App\Models\Incident;
use App\Models\MaintenanceSchedule;
use App\Models\Ticket;
use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private
        TicketService $ticketService,
        private
        AssetService $assetService,
    ) {
    }

    public function index()
    {
        $stats = [
            'open_tickets' => Ticket::active()->count(), // Open + In Progress
            'overdue_tickets' => Ticket::active()->overdue()->count(), // Active + past SLA
            'total_assets' => Asset::count(),
            'active_assets' => Asset::active()->count(),
            'open_incidents' => Incident::open()->count(),
            'upcoming_maintenance' => MaintenanceSchedule::upcoming(7)->count(),
        ];

        $recentTickets = Ticket::with(['creator', 'reporter', 'assignee'])->latest()->limit(5)->get();
        $slaPerformance = $this->ticketService->getSlaPerformance();
        $assetSummary = $this->assetService->getSummaryReport();

        return view('dashboard', compact('stats', 'recentTickets', 'slaPerformance', 'assetSummary'));
    }
}
