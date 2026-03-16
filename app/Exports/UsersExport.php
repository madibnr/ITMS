<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::with('roles');

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['role'])) {
            $query->whereHas('roles', fn($q) => $q->where('slug', $this->filters['role']));
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Department', 'Role', 'Active', 'Created At'];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->phone ?? '-',
            $user->department ?? '-',
            $user->roles->pluck('name')->join(', '),
            $user->is_active ? 'Yes' : 'No',
            $user->created_at->format('Y-m-d H:i'),
        ];
    }
}
