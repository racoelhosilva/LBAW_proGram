<?php

namespace App\Http\Controllers\Api;

use App\Events\FollowEvent;
use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\FollowRequest;
use App\Models\User;
use App\Models\UserStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ApiUserController extends Controller
{
    public function list()
    {
        $authenticatedUser = auth()->user();
        $users = User::all();
        $this->authorize('viewAny', User::class);
        if (! $authenticatedUser) {
            $users = $users->filter(function ($user) {
                return $user->is_public;
            })->map(function ($user) {
                return $user->only([
                    'id',
                    'name',
                    'register_timestamp',
                    'handle',
                    'is_public',
                ]);
            });

            return response()->json($users->values());
        }
        $response = $users->map(function ($user) use ($authenticatedUser) {
            if ($authenticatedUser->can('viewContent', $user)) {
                return $user->only([
                    'id',
                    'name',
                    'register_timestamp',
                    'handle',
                    'is_public',
                    'description',
                    'num_followers',
                    'num_following',
                ]);
            } elseif ($authenticatedUser->can('view', $user)) {
                return $user->only([
                    'id',
                    'name',
                    'register_timestamp',
                    'handle',
                    'is_public',
                ]);
            } else {

                return null;
            }
        })->filter();

        return response()->json($response->values());
    }

    public function show($id)
    {
        $authenticatedUser = auth()->user();
        $user = User::findOrFail($id);
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if ($authenticatedUser->can('viewContent', $user)) {
            $userObject = $user->only(['id', 'name', 'register_timestamp', 'handle', 'is_public', 'description', 'num_followers', 'num_following']);

            return response()->json($userObject);
        } elseif ($authenticatedUser->can('view', $user)) {
            $userObject = $user->only(['id', 'name', 'register_timestamp', 'handle', 'is_public']);

            return response()->json($userObject);

        } else {
            return response()->json(['message' => 'You do not have permission to view this user'], 403);
        }

    }

    public function create(Request $request)
    {

        $request->validate([
            'handle' => 'required|alpha_dash:ascii|max:20|unique:users',
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = new User;
        DB::transaction(function () use ($request, $user) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->handle = $request->input('handle');
            $user->is_public = true;

            $user->save();
            $userStats = new UserStats;
            $userStats->user_id = $user->id;
            $userStats->save();
        });

        return response()->json($user, 201);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $user);

        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'nullable|string|max:200',
            'is_public' => 'nullable',
            'handle' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'github_url' => 'nullable|url',
            'gitlab_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'languages' => 'nullable|array',
            'languages.*' => 'exists:language,id',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technology,id',
            'top_projects' => 'nullable|array|max:10',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->is_public = $request->filled('is_public');
            $user->handle = $request->input('handle');

            $user->stats->languages()->sync($request->input('languages') ?? []);
            $user->stats->technologies()->sync($request->input('technologies') ?? []);

            $user->save();

            $user->stats->github_url = $request->input('github_url');
            $user->stats->gitlab_url = $request->input('gitlab_url');
            $user->stats->linkedin_url = $request->input('linkedin_url');
            $user->stats->save();

            $user->stats->topProjects()->delete();

            if (! $request->filled('top_projects')) {
                return;
            }

            foreach ($request->input('top_projects') as $project) {
                if (! isset($project['name'], $project['url'])) {
                    continue;
                }

                $newProject = new TopProject;

                $newProject->user_stats_id = $user->stats->id;
                $newProject->name = $project['name'];
                $newProject->url = $project['url'];

                $newProject->save();
            }
        });

        $user->save();

        return response()->json($user->only([
            'id',
            'name',
            'handle',
            'description',
            'is_public',
            'github_url',
            'gitlab_url',
            'linkedin_url',
            'languages',
            'technologies',
            'top_projects',
        ]));
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

        $this->authorize('updateNotification', $user);

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

        $this->authorize('updateNotification', $user);

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

        if (! Auth()->check()) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $currentUser = Auth()->user();

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

        $this->authorize('acceptFollowRequests', [User::class]);

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

        $this->authorize('acceptFollowRequests', [User::class]);

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
