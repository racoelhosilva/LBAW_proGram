<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        $authuser = Auth::user();
        $isOwnProfile = $authuser && $authuser->id === $user->id;
        $isFollowing = $authuser && $authuser->following()->where('followed_id', $user->id)->exists();

        return view('pages.user', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
            'recommendedUsers' => $this->recommendedUsers($authuser, $user),
            'isFollowing' => $isFollowing,
        ]);
    }

    public function recommendedUsers($currentUser, $visitedUser)
    {
        if ($currentUser->id !== $visitedUser->id) {
            // Case 1: If the IDs are different, focus on visitedUser's follows (user is not in its profile)
            return $visitedUser->followers()
                ->whereNotIn('follower_id', $currentUser->following()->pluck('followed_id')->toArray())
                ->where('is_public', true)
                ->orderBy('num_followers', 'desc')
                ->take(5)
                ->get();
        } else {
            // Case 2: If the IDs are the same, focus on currentUser's follows (user is in its profile)
            return User::whereNotIn('id', $currentUser->following()->pluck('followed_id')->toArray())
                ->where('is_public', true)
                ->orderBy('num_followers', 'desc')
                ->take(5)
                ->get();
        }
    }
}
