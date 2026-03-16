<?php

namespace App\Http\Controllers;

use App\Exports\TicketHistoryExport;
use App\Exports\TicketsExport;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private
        TicketService $ticketService,
        )
    {
    }

    public function index(Request $request)
    {
        $tickets = $this->ticketService->listActive($request->all());
        $categories = Category::ticketCategories()->get();
        $technicians = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();

        return view('tickets.index', compact('tickets', 'categories', 'technicians'));
    }

    public function history(Request $request)
    {
        $tickets = $this->ticketService->listHistory($request->all());
        $categories = Category::ticketCategories()->get();

        return view('tickets.history', compact('tickets', 'categories'));
    }

    public function create()
    {
        $categories = Category::ticketCategories()->get();
        $technicians = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();

        return view('tickets.create', compact('categories', 'technicians'));
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->ticketService->create($request->validated(), $request->user());

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully. Number: ' . $ticket->ticket_number);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['creator', 'assignee', 'closedByUser', 'category', 'comments.user', 'attachments', 'reporter']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $categories = Category::ticketCategories()->get();
        $technicians = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'it-staff']))->get();

        return view('tickets.edit', compact('ticket', 'categories', 'technicians'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->ticketService->update($ticket, $request->validated());

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $this->ticketService->delete($ticket);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'body' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $this->ticketService->addComment($ticket, $request->all(), $request->user());

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Comment added successfully.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $this->ticketService->assign($ticket, $request->assigned_to);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket assigned successfully.');
    }

    public function resolve(Request $request, Ticket $ticket)
    {
        $request->validate(['resolution_note' => 'required|string']);
        $this->ticketService->resolve($ticket, $request->resolution_note);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket resolved.');
    }

    public function close(Ticket $ticket)
    {
        /** @var User $user */
        $user = auth()->user();
        $this->ticketService->close($ticket, $user);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket closed.');
    }

    public function markCompleted(Ticket $ticket)
    {
        $this->authorize('close', $ticket);
        /** @var User $user */
        $user = auth()->user();
        $this->ticketService->close($ticket, $user);

        return redirect()->back()
            ->with('success', 'Ticket marked as completed.');
    }

    public function updateSla(Request $request, Ticket $ticket)
    {
        $request->validate(['sla_deadline' => 'required|date']);
        $ticket->update(['sla_deadline' => $request->sla_deadline]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'SLA deadline updated successfully.');
    }


    public function export(Request $request)
    {
        return Excel::download(
            new TicketsExport($request->all()),
            'tickets-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportHistory(Request $request)
    {
        return Excel::download(
            new TicketHistoryExport($request->all()),
            'ticket-history-export-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
