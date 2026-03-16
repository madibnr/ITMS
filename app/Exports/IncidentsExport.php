<?php

namespace App\Exports;

use App\Models\Incident;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncidentsExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Incident::with(['reporter', 'relatedTicket']);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('incident_number', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['severity'])) {
            $query->where('severity', $this->filters['severity']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['Incident Number', 'Title', 'Severity', 'Status', 'Reporter', 'Related Ticket', 'Created At'];
    }

    public function map($incident): array
    {
        return [
            $incident->incident_number,
            $incident->title,
            $incident->severity,
            $incident->status,
            $incident->reporter->name ?? '-',
            $incident->relatedTicket->ticket_number ?? '-',
            $incident->created_at->format('Y-m-d H:i'),
        ];
    }
}
