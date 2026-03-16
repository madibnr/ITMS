<?php

namespace App\Policies;

use App\Models\MaintenanceSchedule;
use App\Models\User;

class MaintenanceSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    public function view(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }

    public function update(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }

    public function delete(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasRole('admin');
    }
}
