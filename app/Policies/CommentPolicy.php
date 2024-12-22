<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CommentPolicy
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
    public function view(?User $user, Comment $comment): bool
    {
        $isBanned = $user && $user->isBanned();
        $isAdmin = Auth::guard('admin')->check();
        $postPublic = $comment->post->is_public;
        $followsPost = $user && $user->following->contains('id', $comment->post->author->id);

        return ! $isBanned && ($isAdmin || $postPublic || $followsPost);
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
    public function update(?User $user, Comment $comment): bool
    {
        return $user && ! $user->isBanned() && $user->id === $comment->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Comment $comment): bool
    {
        return $user && ! $user->isBanned() && $user->id === $comment->author_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Comment $comment): bool
    {
        $isAdmin = Auth::guard('admin')->check();

        return $isAdmin || ($user && ! $user->isBanned() && $user->id === $comment->author_id);
    }

    public function like(?User $user, Comment $comment): bool
    {

        return $user && ! $user->isBanned() && $user->id != $comment->author_id;
    }
}
