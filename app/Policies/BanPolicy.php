<?php

namespace App\Policies;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BanPolicy
{
    /**
     * Determine whether the user can view any bans.
     */
    public function viewAny(?User $user): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can view the ban.
     */
    public function view(?User $user, Ban $ban): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can create bans.
     */
    public function create(?User $user): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can update the ban.
     */
    public function update(?User $user, Ban $ban): bool
    {
        return Auth::guard('admin')->check();
    }
}
