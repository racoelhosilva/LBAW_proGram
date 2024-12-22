<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        $isBanned = $user && $user->isBanned();

        return ! $isBanned;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, User $model): bool
    {
        if ($model->is_deleted) {
            return false;
        }
        $isBanned = $user && $user->isBanned();

        return ! $isBanned;
    }

    /**
     * Allows viewing the content of a user profile if the user is an admin, follows the user, is the user themselves or is a public profile.
     */
    public function viewContent(?User $user, User $model): bool
    {
        // Grant access if the user is admin.
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // Deny access if the user is banned.
        if ($user && $user->isBanned()) {
            return false;
        }

        // Grant access if the user is the author.
        if ($user && $user->id === $model->id) {
            return true;
        }

        // Grant access if the user is following the author.
        if ($user && $user->following->contains('id', $model->id)) {
            return true;
        }

        // Grant access if the user is public
        if ($model->is_public) {
            return true;
        }

        if ($model->is_deleted) {
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        $isBanned = $user && $user->isBanned();

        return ! $isBanned;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, User $model): bool
    {
        return $user && ! $user->isBanned() && $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, User $model): bool
    {
        if ($user && ! $user->isBanned() && $user->id === $model->id) {
            return true;
        }

        if (Auth::guard('admin')->check()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view notifications.
     */
    public function viewNotifications(?User $user, User $model): bool
    {
        if ($user && ! $user->isBanned() && $user->id === $model->id) {
            return true;
        }

        if (Auth::guard('admin')->check()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view follow requests.
     */
    public function viewRequests(?User $user, User $model): bool
    {
        if ($user && ! $user->isBanned() && $user->id === $model->id) {
            return true;
        }

        if (Auth::guard('admin')->check()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the notifications.
     */
    public function updateNotification(?User $user, User $model): bool
    {
        return $user && ! $user->isBanned() && $user->id === $model->id;
    }

    /**
     * Determine whether the user can update the follow requests.
     */
    public function updateRequest(?User $user, User $model): bool
    {
        return $user && ! $user->isBanned() && $user->id === $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    public function follow(User $user, User $other): bool
    {
        return ! $user->isBanned() && $user->id !== $other->id && ! $other->is_deleted;
    }

    public function viewInvites(User $user, User $other): bool
    {
        return ! $user->isBanned() && $user->id === $other->id;
    }

    public function acceptFollowRequests(User $user): bool
    {
        return ! $user->isBanned();
    }
}
