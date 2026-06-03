<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }

    public function update(User $user, Category $category): bool
    {
        if (! Category::supportsOwnership()) {
            return $user->isSeller() || $user->isAdmin();
        }

        return $user->isAdmin() || $category->user_id === $user->id;
    }

    public function delete(User $user, Category $category): bool
    {
        if (! Category::supportsOwnership()) {
            return $user->isSeller() || $user->isAdmin();
        }

        return $user->isAdmin() || $category->user_id === $user->id;
    }
}
