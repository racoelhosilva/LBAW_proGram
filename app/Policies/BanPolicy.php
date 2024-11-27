<?php

namespace App\Policies;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Ban $ban): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Ban $ban): bool
    {
        return Auth::guard('admin')->check();
    }
}
