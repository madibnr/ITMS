<?php

namespace App\Repositories;

use App\Models\MaintenanceSchedule;
use App\Repositories\Contracts\MaintenanceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class MaintenanceRepository extends BaseRepository implements MaintenanceRepositoryInterface
{
    public function __construct(MaintenanceSchedule $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()
            ->with(['asset', 'assignee'])
            ->when($filters['type'] ?? null, fn(Builder $q, $v) => $q->where('type', $v))
            ->when($filters['status'] ?? null, fn(Builder $q, $v) => $q->where('status', $v))
            ->when($filters['asset_id'] ?? null, fn(Builder $q, $v) => $q->where('asset_id', $v))
            ->when($filters['search'] ?? null, fn(Builder $q, $v) => $q->where(
                function ($q) use ($v) {
                    $q->where('description', 'like', "%{$v}%");
                }
            ))
            ->latest()
            ->paginate($perPage);
    }
}
