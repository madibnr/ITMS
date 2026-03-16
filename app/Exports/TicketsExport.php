<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Ticket::with(['creator', 'reporter', 'assignee', 'category'])
            ->active();

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['priority'])) {
            $query->where('priority', $this->filters['priority']);
        }
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['No. Tiket', 'Judul', 'Kategori', 'Status', 'Prioritas', 'Dibuat Oleh', 'Ditugaskan Ke', 'SLA Deadline', 'Dibuat'];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->title,
            $ticket->category->name ?? '-',
            $ticket->status,
            $ticket->priority,
            $ticket->creator->name ?? $ticket->reporter->full_name ?? '-',
            $ticket->assignee->name ?? 'Belum ditugaskan',
            $ticket->sla_deadline?->format('Y-m-d H:i') ?? '-',
            $ticket->created_at->format('Y-m-d H:i'),
        ];
    }
}
