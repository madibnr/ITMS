<?php

namespace App\Exports;

use App\Models\ChangeRequest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ChangeRequestsExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = ChangeRequest::with(['requester', 'approver']);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('change_number', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['Change Number', 'Title', 'Type', 'Priority', 'Status', 'Requester', 'Approver', 'Created At'];
    }

    public function map($cr): array
    {
        return [
            $cr->change_number,
            $cr->title,
            $cr->type ?? '-',
            $cr->priority,
            $cr->status,
            $cr->requester->name ?? '-',
            $cr->approver->name ?? '-',
            $cr->created_at->format('Y-m-d H:i'),
        ];
    }
}
