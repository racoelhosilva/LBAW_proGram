<?php

namespace App\Http\Controllers\Api;

use App\Events\FollowEvent;
use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\FollowRequest;
use App\Models\GroupMember;
use App\Models\TopProject;
use App\Models\User;
use App\Models\UserStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ApiUserController extends Controller
{
    public function list()
    {
        $user = auth()->user();
        $this->authorize('viewAny', User::class);
        $users = User::visibleTo($user)
            ->select(['id', 'name', 'register_timestamp', 'handle', 'is_public', 'description', 'num_followers', 'num_following'])
            ->get();
        $nonvisibleUsers = User::whereNotIn('id', $users->pluck('id'))
            ->select(['id', 'name', 'handle', 'is_public'])
            ->get();
        $response = $users->merge($nonvisibleUsers);

        return response()->json($response);
    }

    public function show(int $id)
    {
        $this->authorize('viewAny', User::class);
        $user = User::findOrFail($id);

        $this->authorize('view', $user);

        if ($user->can('viewContent', $user)) {
            $userObject = $user->only(['id', 'name', 'register_timestamp', 'handle', 'is_public', 'description', 'num_followers', 'num_following']);

            return response()->json($userObject);

        } else {
            $userObject = $user->only(['id', 'name', 'handle', 'is_public']);

            return response()->json($userObject);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'handle' => 'required|alpha_dash:ascii|max:20|unique:users',
            'name' => 'required|string|max:64',
            'email' => 'required|email|unique:users',
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
        ]));
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        DB::transaction(function () use ($id, $user) {
            // Delete notifications.
            $user->notifications()->delete();
            // Delete follow requests.
            $user->followRequests()->delete();
            // Delete group join requests.
            $user->groupJoinRequests()->delete();
            // Delete group invitations.
            $user->groupInvitations()->delete();
            // Delete group memberships.
            GroupMember::where('user_id', $id)
                ->delete();
            // Delete bans.
            $user->bans()->delete();
            // Delete user token.
            $user->tokens()->delete();
            // Delete user stats.
            $userStats = $user->stats;
            DB::table('user_stats_language')
                ->where('user_stats_id', $userStats->id)
                ->delete();
            DB::table('user_stats_technology')
                ->where('user_stats_id', $userStats->id)
                ->delete();
            DB::table('top_project')
                ->where('user_stats_id', $userStats->id)
                ->delete();
            $userStats->delete();

            // Delete user info.
            $user->update([
                'name' => $id,
                'email' => $id,
                'password' => $id,
                'handle' => $id,
                'is_public' => false,
                'description' => null,
                'profile_picture_url' => null,
                'banner_image_url' => null,
                'is_deleted' => true,
            ]);
        });

        return response()->json(['message' => 'User deleted successfully.']);
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
        $user = User::findOrFail($id);
        $this->authorize('viewContent', $user);

        return response()->json($user->followers->select('id', 'name', 'handle', 'is_public'));
    }

    public function listFollowing($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('viewContent', $user);

        return response()->json($user->following->select('id', 'name', 'handle', 'is_public'));
    }

    public function listPosts($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('viewContent', $user);

        return response()->json($user->posts->select(['id', 'author_id', 'title', 'text', 'creation_timestamp', 'is_announcement', 'is_public', 'likes', 'comments']));
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
        $currentUser = auth()->user();

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
            ]);
        } else {
            $followRequest = new FollowRequest;
            $followRequest->follower_id = $currentUser->id;
            $followRequest->followed_id = $targetUser->id;
            $followRequest->save();

            return response()->json([
                'action' => 'request',
                'message' => 'Follow request sent.',
            ]);
        }
    }

    public function unfollow($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = auth()->user();
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
        ]);
    }

    public function removeFollower($id)
    {
        $follower = User::findOrFail($id);

        $currentUser = auth()->user();

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

        return response()->json(['message' => 'Follower removed.']);
    }

    public function accept($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = auth()->user();

        $this->authorize('acceptFollowRequests', [User::class]);

        $followRequest = FollowRequest::where('follower_id', $targetUser->id)
            ->where('followed_id', $currentUser->id)
            ->first();

        if (! $followRequest) {
            return response()->json(['message' => 'Follow request not found.'], 404);
        }

        $followRequest->status = 'accepted';
        $followRequest->save();

        return response()->json(['message' => 'Follow request accepted.']);
    }

    public function reject($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = auth()->user();

        $this->authorize('acceptFollowRequests', [User::class]);

        $followRequest = FollowRequest::where('follower_id', $targetUser->id)
            ->where('followed_id', $currentUser->id)
            ->first();

        if (! $followRequest) {
            return response()->json(['message' => 'Follow request not found.'], 409);
        }

        $followRequest->status = 'rejected';
        $followRequest->save();

        return response()->json(['message' => 'Follow request rejected.']);
    }
}
