<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public const SLA_HOURS = [
        'Critical' => 4,
        'High' => 8,
        'Medium' => 24,
        'Low' => 48,
    ];

    public function __construct(private
        TicketRepositoryInterface $ticketRepo,
        )
    {
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->ticketRepo->getFiltered($filters, $perPage);
    }

    public function listActive(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->ticketRepo->getActive($filters, $perPage);
    }

    public function listHistory(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->ticketRepo->getHistory($filters, $perPage);
    }

    public function create(array $data, User $creator): Ticket
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['ticket_number'] = Ticket::generateTicketNumber();
            $data['user_id'] = $creator->id;
            $data['status'] = 'Open';
            $data['sla_deadline'] = $this->calculateSlaDeadline($data['priority'] ?? 'Medium');

            return $this->ticketRepo->create($data);
        });
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        return DB::transaction(function () use ($ticket, $data) {
            if (isset($data['priority']) && $data['priority'] !== $ticket->priority) {
                $data['sla_deadline'] = $this->calculateSlaDeadline($data['priority']);
            }

            if (isset($data['status']) && $data['status'] === 'Resolved' && $ticket->status !== 'Resolved') {
                $data['resolved_at'] = now();
            }

            return $this->ticketRepo->update($ticket, $data);
        });
    }

    public function assign(Ticket $ticket, int $userId): Ticket
    {
        return $this->ticketRepo->update($ticket, [
            'assigned_to' => $userId,
            'status' => $ticket->status === 'Open' ? 'In Progress' : $ticket->status,
        ]);
    }

    public function addComment(Ticket $ticket, array $data, User $user): TicketComment
    {
        return $ticket->comments()->create([
            'user_id' => $user->id,
            'body' => $data['body'],
            'is_internal' => $data['is_internal'] ?? false,
        ]);
    }

    public function resolve(Ticket $ticket, string $resolutionNote): Ticket
    {
        return $this->ticketRepo->update($ticket, [
            'status' => 'Resolved',
            'resolved_at' => now(),
            'resolution_note' => $resolutionNote,
        ]);
    }

    public function close(Ticket $ticket, User $closedBy): Ticket
    {
        return $this->ticketRepo->update($ticket, [
            'status' => 'Closed',
            'closed_by' => $closedBy->id,
        ]);
    }

    public function delete(Ticket $ticket): bool
    {
        return $this->ticketRepo->delete($ticket);
    }

    // ─── SLA Helpers ────────────────────────────────────

    public function calculateSlaDeadline(string $priority): \DateTimeInterface
    {
        $hours = self::SLA_HOURS[$priority] ?? 24;
        return now()->addHours($hours);
    }

    public function getSlaPerformance(string $dateFrom = null, string $dateTo = null): array
    {
        $query = Ticket::query()
            ->whereIn('status', ['Resolved', 'Closed'])
            ->when($dateFrom, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($dateTo, fn($q, $v) => $q->whereDate('created_at', '<=', $v));

        $total = (clone $query)->count();
        $metSla = (clone $query)->whereColumn('resolved_at', '<=', 'sla_deadline')->count();
        $breached = $total - $metSla;

        return [
            'total' => $total,
            'met_sla' => $metSla,
            'breached_sla' => $breached,
            'compliance_rate' => $total > 0 ? round(($metSla / $total) * 100, 2) : 0,
        ];
    }
}
