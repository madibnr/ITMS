<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Asset::with(['category', 'assignedUser', 'assetModel.manufacturer', 'locationRef']);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_code', 'like', "%{$search}%")
                    ->orWhere('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Asset Tag',
            'Asset Code',
            'Name',
            'Category',
            'Model',
            'Serial Number',
            'Status',
            'Assigned To',
            'Location',
            'Purchase Date',
            'Purchase Cost',
            'Supplier',
            'Warranty Expiration',
            'Notes',
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->asset_tag ?? '-',
            $asset->asset_code,
            $asset->name,
            $asset->category->name ?? '-',
            $asset->display_model,
            $asset->serial_number ?? '-',
            $asset->status,
            $asset->assignedUser->name ?? 'Unassigned',
            $asset->display_location,
            $asset->purchase_date?->format('Y-m-d') ?? '-',
            $asset->purchase_cost ? number_format($asset->purchase_cost, 2) : '-',
            $asset->supplier ?? '-',
            $asset->warranty_date?->format('Y-m-d') ?? '-',
            $asset->notes ?? '-',
        ];
    }
}
