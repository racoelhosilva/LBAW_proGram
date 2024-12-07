<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return $user && ! $user->isBanned();
    }

    public function update(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }
}
