<?php

namespace App\Repositories;

use App\Models\Incident;
use App\Repositories\Contracts\IncidentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class IncidentRepository extends BaseRepository implements IncidentRepositoryInterface
{
    public function __construct(Incident $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()
            ->with(['reporter', 'relatedAsset', 'relatedTicket'])
            ->when($filters['severity'] ?? null, fn(Builder $q, $v) => $q->where('severity', $v))
            ->when($filters['status'] ?? null, fn(Builder $q, $v) => $q->where('status', $v))
            ->when($filters['search'] ?? null, fn(Builder $q, $v) => $q->where(function ($q) use ($v) {
            $q->where('title', 'like', "%{$v}%")
                ->orWhere('incident_number', 'like', "%{$v}%");
        }
        ))
            ->latest()
            ->paginate($perPage);
    }
}
