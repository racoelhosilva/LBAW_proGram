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
        var_dump($authuser->following()->where('followed_id', $user->id));
        var_dump($isFollowing);

        return view('pages.user', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
            'recommendedUsers' => $this->recommendedUsers($authuser, $user),
            'isFollowing' => $isFollowing,
        ]);
    }

    public function recommendedUsers($currentUser, $visitedUser)
    {

        $followingIds = $currentUser->following()->pluck('followed_id')->toArray();

        $excludedIds = array_merge($followingIds, [$currentUser->id, $visitedUser->id]);

        $users = User::where('is_public', true)
            ->whereNotIn('id', $excludedIds)
            ->orderBy('num_followers', 'desc') // Example criteria: sort by popularity
            ->take(5)
            ->get();

        return $users;
    }
}
