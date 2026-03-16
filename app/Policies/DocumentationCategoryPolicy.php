<?php

namespace App\Policies;

use App\Models\DocumentationCategory;
use App\Models\User;

class DocumentationCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, DocumentationCategory $category): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, DocumentationCategory $category): bool
    {
        return $user->hasRole('admin');
    }
}
