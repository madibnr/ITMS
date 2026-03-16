<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;

class IncidentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    public function view(User $user, Incident $incident): bool
    {
        if ($user->hasAnyRole(['admin', 'it-staff', 'manager'])) {
            return true;
        }

        return $user->id === $incident->reported_by;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    public function update(User $user, Incident $incident): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }

    public function delete(User $user, Incident $incident): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Admin or IT Staff can create Root Cause Analysis.
     */
    public function createRca(User $user, Incident $incident): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }
}
