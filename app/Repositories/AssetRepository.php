<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetRepository extends BaseRepository implements AssetRepositoryInterface
{
    public function __construct(Asset $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()
            ->with(['category', 'assignedUser', 'assetModel.manufacturer', 'locationRef'])
            ->when($filters['status'] ?? null, fn(Builder $q, $v) => $q->where('status', $v))
            ->when($filters['category_id'] ?? null, fn(Builder $q, $v) => $q->where('category_id', $v))
            ->when($filters['model_id'] ?? null, fn(Builder $q, $v) => $q->where('model_id', $v))
            ->when($filters['location_id'] ?? null, fn(Builder $q, $v) => $q->where('location_id', $v))
            ->when($filters['location'] ?? null, fn(Builder $q, $v) => $q->where('location', 'like', "%{$v}%"))
            ->when($filters['assigned_user_id'] ?? null, fn(Builder $q, $v) => $q->where('assigned_user_id', $v))
            ->when($filters['warranty_expiring'] ?? null, fn(Builder $q) => $q->warrantyExpiringSoon(30))
            ->when($filters['search'] ?? null, fn(Builder $q, $v) => $q->where(function ($q) use ($v) {
                $q->where('name', 'like', "%{$v}%")
                    ->orWhere('asset_code', 'like', "%{$v}%")
                    ->orWhere('asset_tag', 'like', "%{$v}%")
                    ->orWhere('serial_number', 'like', "%{$v}%");
            }))
            ->latest()
            ->paginate($perPage);
    }
}
