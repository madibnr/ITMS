<?php

namespace App\Policies;

use App\Models\DocumentationTag;
use App\Models\User;

class DocumentationTagPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function update(User $user, DocumentationTag $tag): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function delete(User $user, DocumentationTag $tag): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }
}
