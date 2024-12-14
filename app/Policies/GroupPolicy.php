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

    public function join(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && ! $group->members()->where('user_id', $user->id)->exists();
    }

    public function leave(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists();
    }

    public function remove(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }

    public function manage(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }

    public function addPostToGroup(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists();
    }

    public function isInvited(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->invitations()->where('invitee_id', $user->id)->exists();
    }
}
