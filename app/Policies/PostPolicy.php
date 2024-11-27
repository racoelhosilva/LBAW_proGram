<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostPolicy
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
    public function view(?User $user, Post $post): bool
    {
        // Deny access if the user is banned.
        if ($user && $user->isBanned()) {
            return false;
        }

        // Grant access if the post is public.
        if ($post->is_public) {
            return true;
        }

        // Grant access if the user is admin.
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // Grant access if the user is following the author.
        if ($user && $user->following->contains('id', $post->author->id)) {
            return true;
        }

        // Grant access if the user is the author.
        return $user && $user->id === $post->author->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return $user && ! $user->isBanned();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Post $post): bool
    {
        return $user && ! $user->isBanned() && $user->id === $post->author->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Post $post): bool
    {
        $isAdmin = Auth::guard('admin')->check();

        return $isAdmin || ($user && ! $user->isBanned() && $user->id === $post->author->id);
    }

    public function like(?User $user, Post $post): bool
    {
        return $user && ! $user->isBanned() && $user->id !== $post->author->id;
    }
}
