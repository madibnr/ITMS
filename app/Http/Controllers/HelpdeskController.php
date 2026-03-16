<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Services\HelpdeskService;
use Illuminate\Http\Request;

class HelpdeskController extends Controller
{
    public function __construct(private
        HelpdeskService $helpdeskService,
        )
    {
    }

    /**
     * Landing page.
     */
    public function index()
    {
        $activeCount = Ticket::active()->count();
        $resolvedTodayCount = Ticket::whereDate('resolved_at', today())->count();

        return view('helpdesk.index', compact('activeCount', 'resolvedTodayCount'));
    }

    /**
     * Panduan / FAQ page.
     */
    public function guide()
    {
        $tips = \App\Models\GuideTip::active()->ordered()->get();
        $faqs = \App\Models\GuideFaq::active()->ordered()->get();

        return view('helpdesk.guide', compact('tips', 'faqs'));
    }

    /**
     * Show public ticket form.
     */
    public function create()
    {
        $categories = Category::ticketCategories()->get();
        $priorities = ['Low', 'Medium', 'High', 'Critical'];

        return view('helpdesk.create', compact('categories', 'priorities'));
    }

    /**
     * Handle public ticket submission.
     */
    public function store(StorePublicTicketRequest $request)
    {
        $result = $this->helpdeskService->createPublicTicket(
            $request->validated(),
            $request,
        );

        return redirect()->route('helpdesk.success', [
            'ticket' => $result['ticket']->ticket_number,
        ])->with('tracking_url', $result['tracking_url']);
    }

    /**
     * Success page after ticket submission.
     */
    public function success(Request $request)
    {
        $ticketNumber = $request->query('ticket');
        $trackingUrl = session('tracking_url');

        if (!$ticketNumber) {
            return redirect()->route('helpdesk.index');
        }

        return view('helpdesk.success', compact('ticketNumber', 'trackingUrl'));
    }

    /**
     * Ticket tracking form.
     */
    public function track()
    {
        return view('helpdesk.track');
    }

    /**
     * Show tracking result (signed URL).
     */
    public function trackResult(Request $request)
    {
        // Validate signed URL
        if (!$request->hasValidSignature()) {
            abort(403, 'Link tracking tidak valid atau sudah kedaluwarsa.');
        }

        $ticket = $this->helpdeskService->trackTicket(
            $request->query('ticket'),
        );

        if (!$ticket) {
            return redirect()->route('helpdesk.track')
                ->with('error', 'Ticket tidak ditemukan.');
        }

        return view('helpdesk.track-result', compact('ticket'));
    }

    /**
     * Handle track form submission — generates signed URL and redirects.
     */
    public function trackSubmit(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string',
        ]);

        // Verify ticket exists
        $ticket = $this->helpdeskService->trackTicket(
            $request->ticket_number,
        );

        if (!$ticket) {
            return redirect()->route('helpdesk.track')
                ->with('error', 'Nomor ticket tidak ditemukan.')
                ->withInput();
        }

        // Generate signed URL and redirect
        $url = $this->helpdeskService->generateTrackingUrl(
            $request->ticket_number,
        );

        return redirect($url);
    }
}
