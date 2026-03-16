<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()
            ->with('roles')
            ->when($filters['role'] ?? null, fn(Builder $q, $v) => $q->whereHas('roles', fn($r) => $r->where('slug', $v)))
            ->when($filters['search'] ?? null, fn(Builder $q, $v) => $q->where(function ($q) use ($v) {
            $q->where('name', 'like', "%{$v}%")
                ->orWhere('email', 'like', "%{$v}%");
        }
        ))
            ->latest()
            ->paginate($perPage);
    }
}
