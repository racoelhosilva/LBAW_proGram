<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPostPolicy
{
    public function create(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists();
    }
}
