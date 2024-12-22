<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Determine whether the user can view any groups.
     */
    public function viewAny(?User $user): bool
    {
        return ! $user || ! $user->isBanned();
    }

    /**
     * Determine whether the user can view the group.
     */
    public function viewContent(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists() || $group->is_public;
    }

    /**
     * Determine whether the user can create groups.
     */
    public function create(?User $user): bool
    {
        return $user && ! $user->isBanned();
    }

    /**
     * Determine whether the user can update the group.
     */
    public function update(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }

    /**
     * Determine whether the user can delete the group.
     */
    public function join(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && ! $group->members()->where('user_id', $user->id)->exists() && $user->id !== $group->owner_id;
    }

    /**
     * Determine whether the user can leave the group.
     */
    public function leave(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists() && $user->id !== $group->owner_id;
    }

    /**
     * Determine whether the user can remove a member from the group.
     */
    public function remove(?User $user, Group $group, ?User $userToBeRemoved): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id && $user->id !== $userToBeRemoved->id;
    }

    /**
     * Determine whether the user can manage group requests.
     */
    public function manageRequests(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }

    /**
     * Determine whether the user can invite a user to the group.
     */
    public function invite(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $user->id === $group->owner_id;
    }

    /**
     * Determine whether the user can create a post in the group.
     */
    public function createPost(?User $user, Group $group): bool
    {
        return $user && ! $user->isBanned() && $group->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can accept an invite to the group.
     */
    public function acceptInvite(?User $user, Group $group): bool
    {

        return $user && ! $user->isBanned() && $group->invitedUsers()->where('users.id', $user->id)->exists();
    }
}
