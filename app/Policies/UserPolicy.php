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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, User $model): bool
    {
        return true;
    }

    public function viewContent(?User $user, User $model): bool
    {
        $isAdmin = Auth::guard('admin')->check();
        $followsUser = $user && $user->following->contains('id', $model->id);
        $isSelf = $user && $user->id === $model->id;

        return $isAdmin || $followsUser || $isSelf;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, User $model): bool
    {
        return $user && $user->id === $model->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, User $model): bool
    {
        $isAdmin = Auth::guard('admin')->check();

        return $isAdmin || ($user && $user->id === $model->id);
    }
}
