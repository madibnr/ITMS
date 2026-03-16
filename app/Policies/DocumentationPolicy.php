<?php

namespace App\Policies;

use App\Models\Documentation;
use App\Models\User;

class DocumentationPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // all authenticated users can view list
    }

    public function view(User $user, Documentation $documentation): bool
    {
        // Staff can only view published; admin/manager/it-staff can view drafts too
        if ($documentation->status === 'draft') {
            return $user->hasAnyRole(['admin', 'manager', 'it-staff']);
        }
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'it-staff']);
    }

    public function update(User $user, Documentation $documentation): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasAnyRole(['manager', 'it-staff'])) {
            return true; // can edit any document
        }
        return false;
    }

    public function delete(User $user, Documentation $documentation): bool
    {
        return $user->hasRole('admin');
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'it-staff']);
    }
}
