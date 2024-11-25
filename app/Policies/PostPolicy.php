<?php

namespace App\Policies;

use App\Models\Administrator;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post): bool
    {
        // Grant access if the post is public.
        if ($post->is_public) {
            return true;
        }

        // Grant access if the user is admin.
        if ($user instanceof Administrator) {
            return true;
        }

        // Grant access if the user is following the author.
        if ($user instanceof User && $user->following->contains($post->author->id)) {
            return true;
        }

        // Grant access if the user is the author.
        return $user instanceof User && $user->id === $post->author->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user instanceof User && $user->id === $post->author->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user instanceof Administrator) {
            return true;
        }

        return $user instanceof User && $user->id === $post->author->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        //
    }

    public function like(User $user, Post $post): bool
    {
        return $user->id !== $post->author_id;
    }
}
