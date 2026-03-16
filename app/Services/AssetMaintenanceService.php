<?php

namespace App\Services;

use App\Models\AssetMaintenance;
use App\Models\AssetLog;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AssetMaintenanceService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return AssetMaintenance::with('asset')
            ->when($filters['asset_id'] ?? null, fn($q, $v) => $q->where('asset_id', $v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['maintenance_type'] ?? null, fn($q, $v) => $q->where('maintenance_type', $v))
            ->latest('start_date')
            ->paginate($perPage);
    }

    public function create(array $data, User $performer): AssetMaintenance
    {
        return DB::transaction(function () use ($data, $performer) {
            $maintenance = AssetMaintenance::create($data);

            AssetLog::create([
                'asset_id' => $data['asset_id'],
                'action' => 'Maintenance Created',
                'user_id' => $performer->id,
                'changes' => [
                    'type' => $data['maintenance_type'],
                    'status' => $data['status'] ?? 'Scheduled',
                ],
            ]);

            return $maintenance;
        });
    }

    public function update(AssetMaintenance $maintenance, array $data, User $performer): AssetMaintenance
    {
        return DB::transaction(function () use ($maintenance, $data, $performer) {
            $oldStatus = $maintenance->status;
            $maintenance->update($data);

            AssetLog::create([
                'asset_id' => $maintenance->asset_id,
                'action' => 'Maintenance Updated',
                'user_id' => $performer->id,
                'changes' => [
                    'old_status' => $oldStatus,
                    'new_status' => $maintenance->status,
                ],
            ]);

            return $maintenance->fresh();
        });
    }

    public function complete(AssetMaintenance $maintenance, User $performer): AssetMaintenance
    {
        return $this->update($maintenance, [
            'status' => 'Completed',
            'end_date' => now()->toDateString(),
        ], $performer);
    }

    public function delete(AssetMaintenance $maintenance): bool
    {
        return $maintenance->delete();
    }
}
