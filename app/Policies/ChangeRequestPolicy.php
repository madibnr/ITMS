<?php

namespace App\Policies;

use App\Models\ChangeRequest;
use App\Models\User;

class ChangeRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    public function view(User $user, ChangeRequest $changeRequest): bool
    {
        if ($user->hasAnyRole(['admin', 'it-staff', 'manager'])) {
            return true;
        }

        return $user->id === $changeRequest->requested_by;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    public function update(User $user, ChangeRequest $changeRequest): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $changeRequest->requested_by && $changeRequest->status === 'Draft';
    }

    public function delete(User $user, ChangeRequest $changeRequest): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Only admin or manager can approve/reject.
     */
    public function approve(User $user, ChangeRequest $changeRequest): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Only admin or IT Staff can mark as implemented.
     */
    public function implement(User $user, ChangeRequest $changeRequest): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }
}
