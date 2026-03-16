<?php

namespace App\Exports;

use App\Models\Documentation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DocumentationsExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Documentation::with(['category', 'tags', 'creator']);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['tag_id'])) {
            $query->whereHas('tags', fn($q) => $q->where('documentation_tags.id', $this->filters['tag_id']));
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Category',
            'Tags',
            'Status',
            'Description',
            'Created By',
            'Created At',
            'Updated At',
        ];
    }

    public function map($doc): array
    {
        return [
            $doc->id,
            $doc->title,
            $doc->category->name ?? '-',
            $doc->tags->pluck('name')->join(', ') ?: '-',
            ucfirst($doc->status),
            $doc->description ?? '-',
            $doc->creator->name ?? '-',
            $doc->created_at?->format('Y-m-d H:i'),
            $doc->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
