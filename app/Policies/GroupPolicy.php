<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function view(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists() || $group->is_public;
    }

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
        return $user && ! $user->isBanned() && ! $group->members()->where('user_id', $user->id)->exists() && $user->id !== $group->owner_id;
    }

    public function leave(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists() && $user->id !== $group->owner_id;
    }

    public function remove(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }

    public function manageRequests(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }

    public function invite(?User $user, Group $group, ?User $invitee): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id && ! $group->members()->where('user_id', $invitee->id)->exists();
    }

    public function createPost(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists();
    }

    public function acceptInvite(?User $user, Group $group): bool
    {

        return $user && ! $user->isBanned() && $group->invitedUsers()->where('users.id', $user->id)->exists();
    }

    public function rejectInvite(?User $user, Group $group): bool
    {

        return $user && ! $user->isBanned() && $group->invitedUsers()->where('users.id', $user->id)->exists();
    }

    /*
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return ! $user || ! $user->isBanned();
    }
}
