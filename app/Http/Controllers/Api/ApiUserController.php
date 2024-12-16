<?php

namespace App\Http\Controllers\Api;

use App\Events\FollowEvent;
use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\FollowRequest;
use App\Models\User;
use App\Models\UserStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiUserController extends Controller
{
    public function list()
    {
        $users = User::where('is_deleted', false)->get();

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->where('is_deleted', false)->firstOrFail();

        return response()->json($user);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'handle' => 'nullable|string|unique:users,handle|max:50',
            'is_public' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        // Create user with the provided fields
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'handle' => $request->input('handle'),
                'is_public' => $request->input('is_public', true), // Defaults to true if not provided
                'description' => $request->input('description'),
                'register_timestamp' => now(), // Auto-set registration timestamp
            ]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create user.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->where('is_deleted', false)->firstOrFail();

        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email',
            'password' => 'string|min:8',
            'handle' => 'string|unique:users,handle|max:50',
            'is_public' => 'boolean',
            'description' => 'string|max:500',
        ]);

        try {
            $user->update($request->all());

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update user.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listUserStats($id)
    {

        $userStats = UserStats::where('user_id', $id)
            ->with(['technologies', 'languages', 'projects'])
            ->first();

        if (! $userStats) {
            return response()->json(['message' => 'User stats not found'], 404);
        }

        return response()->json($userStats);
    }

    public function listFollowers($id)
    {
        $user = User::where('id', $id)->where('is_deleted', false)->firstOrFail();

        return response()->json($user->followers);
    }

    public function listFollowing($id)
    {
        $user = User::where('id', $id)->where('is_deleted', false)->firstOrFail();

        return response()->json($user->following);
    }

    public function listPosts($id)
    {
        $user = User::where('id', $id)->where('is_deleted', false)->firstOrFail();

        return response()->json($user->posts);
    }

    public function listFollowRequests($id)
    {
        $user = User::where('id', $id)->where('is_deleted', false)->firstOrFail();

        return response()->json($user->followRequests);

    }

    public function readAllNotifications($id)
    {
        $user = User::findOrFail($id);

        // TODO: Check policies @HenriqueSFernandes

        $user->notifications()->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications read']);
    }

    public function readNotification($userId, $notificationId)
    {
        $user = User::findOrFail($userId);

        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (! $notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        // TODO: Check policies @HenriqueSFernandes

        $notification->is_read = true;
        $notification->save();

        return response()->json($notification);
    }

    public function follow($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth()->user();

        if ($currentUser->follows($targetUser)) {
            return response()->json([
                'action' => 'none',
                'message' => 'User already followed.',
            ], 409);
        }

        $requestStatus = $currentUser->getFollowRequestStatus($targetUser);
        if ($requestStatus === 'accepted' || $requestStatus === 'pending') {
            return response()->json([
                'action' => 'none',
                'message' => 'Follow request already sent.',
            ], 409);
        }

        if ($targetUser->is_public) {
            $follow = new Follow;
            $follow->follower_id = $currentUser->id;
            $follow->followed_id = $targetUser->id;
            $follow->save();

            event(new FollowEvent($targetUser->id));

            return response()->json([
                'action' => 'follow',
                'message' => 'User followed successfully.',
            ], 200);
        } else {
            $followRequest = new FollowRequest;
            $followRequest->follower_id = $currentUser->id;
            $followRequest->followed_id = $targetUser->id;
            $followRequest->save();

            return response()->json([
                'action' => 'request',
                'message' => 'Follow request sent.',
            ], 200);
        }
    }

    public function unfollow($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth()->user();
        $message = '';

        if ($currentUser->follows($targetUser)) {

            DB::transaction(function () use ($currentUser, $targetUser) {
                $follow = Follow::where('follower_id', $currentUser->id)
                    ->where('followed_id', $targetUser->id)
                    ->first();

                $notification = $targetUser->notifications()
                    ->where('type', 'follow')
                    ->where('follow_id', $follow->id)->latest('timestamp')->first();

                $notification->delete();

                $follow = Follow::where('follower_id', $currentUser->id)
                    ->where('followed_id', $targetUser->id)
                    ->first();

                $follow->delete();
            });
            $message = 'User unfollowed.';
        }

        $request = FollowRequest::where('follower_id', $currentUser->id)
            ->where('followed_id', $targetUser->id)
            ->first();

        if ($request) {
            $request->delete();
            if ($message !== '') {
                $message = 'Follow request cancelled.';
            }
        }

        return response()->json([
            'message' => $message,
        ], 200);
    }

    public function removeFollower($id)
    {
        $follower = User::findOrFail($id);
        $currentUser = Auth()->user()->id;

        if ($follower->follows($currentUser)) {
            DB::transaction(function () use ($currentUser, $follower) {
                $follow = Follow::where('follower_id', $follower->id)
                    ->where('followed_id', $currentUser->id)
                    ->first();

                $notification = $currentUser->notifications()
                    ->where('type', 'follow')
                    ->where('follow_id', $follow->id)->latest('timestamp')->first();

                $notification->delete();

                $follow = Follow::where('follower_id', $follower->id)
                    ->where('followed_id', $currentUser->id)
                    ->first();

                $follow->delete();
            });
        }

        $request = FollowRequest::where('follower_id', $follower->id)
            ->where('followed_id', $currentUser->id)
            ->first();

        if ($request) {
            $request->delete();
        }

        return response()->json(['message' => 'Follower removed.'], 200);
    }

    public function accept($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth()->user();

        $followRequest = FollowRequest::where('follower_id', $targetUser->id)
            ->where('followed_id', $currentUser->id)
            ->first();

        if (! $followRequest) {
            return response()->json(['message' => 'Follow request not found.'], 404);
        }

        $followRequest->status = 'accepted';
        $followRequest->save();

        return response()->json(['message' => 'Follow request accepted.'], 200);
    }

    public function reject($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth()->user();

        $followRequest = FollowRequest::where('follower_id', $targetUser->id)
            ->where('followed_id', $currentUser->id)
            ->first();

        if (! $followRequest) {
            return response()->json(['message' => 'Follow request not found.'], 404);
        }

        $followRequest->status = 'rejected';
        $followRequest->save();

        return response()->json(['message' => 'Follow request rejected.'], 200);
    }
}
