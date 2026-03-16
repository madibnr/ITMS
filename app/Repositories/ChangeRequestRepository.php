<?php

namespace App\Repositories;

use App\Models\ChangeRequest;
use App\Repositories\Contracts\ChangeRequestRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ChangeRequestRepository extends BaseRepository implements ChangeRequestRepositoryInterface
{
    public function __construct(ChangeRequest $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()
            ->with(['requester', 'approver'])
            ->when($filters['status'] ?? null, fn(Builder $q, $v) => $q->where('status', $v))
            ->when($filters['priority'] ?? null, fn(Builder $q, $v) => $q->where('priority', $v))
            ->when($filters['search'] ?? null, fn(Builder $q, $v) => $q->where(function ($q) use ($v) {
            $q->where('title', 'like', "%{$v}%")
                ->orWhere('change_number', 'like', "%{$v}%");
        }
        ))
            ->latest()
            ->paginate($perPage);
    }
}
