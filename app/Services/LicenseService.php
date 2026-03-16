<?php

namespace App\Services;

use App\Models\License;
use App\Models\Software;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class LicenseService
{
    public function listSoftware(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Software::withCount('licenses')
            ->when($filters['search'] ?? null, fn($q, $v) => $q->where('software_name', 'like', "%{$v}%"))
            ->when($filters['vendor'] ?? null, fn($q, $v) => $q->where('vendor', $v))
            ->latest()
            ->paginate($perPage);
    }

    public function listLicenses(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return License::with(['software', 'users'])
            ->when($filters['software_id'] ?? null, fn($q, $v) => $q->where('software_id', $v))
            ->when($filters['search'] ?? null, fn($q, $v) => $q->where('license_key', 'like', "%{$v}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function createLicense(array $data): License
    {
        return License::create($data);
    }

    public function updateLicense(License $license, array $data): License
    {
        $license->update($data);
        return $license->fresh();
    }

    public function deleteLicense(License $license): bool
    {
        $license->users()->detach();
        return $license->delete();
    }

    public function assignUser(License $license, int $userId): void
    {
        if ($license->available_seats <= 0) {
            throw new \RuntimeException('No available seats for this license.');
        }

        $license->users()->syncWithoutDetaching([
            $userId => ['assigned_at' => now()],
        ]);
    }

    public function revokeUser(License $license, int $userId): void
    {
        $license->users()->detach($userId);
    }
}
