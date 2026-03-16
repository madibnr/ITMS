<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * Admin and IT Staff can view asset list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    /**
     * Admin and IT Staff can view asset details.
     */
    public function view(User $user, Asset $asset): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff', 'manager']);
    }

    /**
     * Admin and IT Staff can create assets.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }

    /**
     * Admin and IT Staff can update assets.
     */
    public function update(User $user, Asset $asset): bool
    {
        return $user->hasAnyRole(['admin', 'it-staff']);
    }

    /**
     * Only admin can delete assets.
     */
    public function delete(User $user, Asset $asset): bool
    {
        return $user->hasRole('admin');
    }
}
