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
            $users = $visitedUser->followers()
                ->whereNotIn('follower_id', $currentUser->following()->pluck('followed_id')->toArray())
                ->where('is_public', true)
                ->orderBy('num_followers', 'desc')
                ->take(20)
                ->get();

            return $users->random(min($users->count(), 5))
                ->sortByDesc('num_followers')
                ->values();
        } else {

            $users = User::whereNotIn('id', $currentUser->following()->pluck('followed_id')->toArray())
                ->where('is_public', true)
                ->where('id', '!=', $currentUser->id)
                ->orderBy('num_followers', 'desc')
                ->take(20)
                ->get();

            return $users->random(min($users->count(), 5))
                ->sortByDesc('num_followers')
                ->values();
        }

    }
}
