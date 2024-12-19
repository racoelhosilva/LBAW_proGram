<?php

namespace App\Policies;

use App\Models\Token;
use App\Models\User;

class TokenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return $user && ! $user->isBanned();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, ?Token $token): bool
    {
        if (! $user || $user->isBanned()) {
            return false;
        }
        if (! $token) {
            return true;
        }

        return $token->account && $user->id == $token->account->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return $user && ! $user->isBanned();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Token $token): bool
    {
        $tokenAccount = $token->account;

        return $user && $tokenAccount && ! $user->isBanned() && $user->id === $tokenAccount->id;
    }
}
