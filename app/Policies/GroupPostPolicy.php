<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPostPolicy
{
    /**
     * Determine whether the user can view any group posts.
     */
    public function create(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists();
    }
}
