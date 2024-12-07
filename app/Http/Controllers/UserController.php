<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\FollowRequest;
use App\Models\GroupInvitation;
use App\Models\GroupJoinRequest;
use App\Models\GroupMember;
use App\Models\Language;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Technology;
use App\Models\Token;
use App\Models\TopProject;
use App\Models\User;
use App\Models\UserStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show(int $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('view', $user);
        $this->authorize('viewAny', Post::class);

        $posts = $user->posts()
            ->when(Auth::check() && ! Auth::user()->follows($user) && Auth::id() !== $id, function ($query) {
                $query->where('is_public', true);
            })
            ->orderBy('likes', 'DESC')
            ->paginate(10);

        $isOwnProfile = Auth::check() && Auth::id() === $user->id;
        $isFollowing = Auth::check() && Auth::user()->following()->where('followed_id', $user->id)->exists();
        $recommendedUsers = Auth::check() ? $this->recommendedUsers(Auth::user(), $user) : null;

        return view('pages.user', [
            'user' => $user,
            'posts' => $posts,
            'isOwnProfile' => $isOwnProfile,
            'recommendedUsers' => $recommendedUsers,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $user);

        return view('pages.edit-user', [
            'user' => $user,
            'languages' => Language::all(),
            'technologies' => Technology::all(),
        ]);
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
            'languages' => 'nullable|array',
            'languages.*' => 'exists:language,id',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technology,id',
            'top_projects' => 'nullable|array|max:10',
            'banner_picture' => 'image|mimes:jpeg,png,jpg|max:10240',
            'profile_picture' => 'image|mimes:jpeg,png,jpg|max:10240',

        ]);

        DB::transaction(function () use ($request, $user) {
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->is_public = $request->filled('is_public');
            $user->handle = $request->input('handle');

            $user->stats->languages()->sync($request->input('languages') ?? []);
            $user->stats->technologies()->sync($request->input('technologies') ?? []);

            $user->save();

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

        if ($request->hasFile('profile_picture')) {

            // Make a request to the file upload route
            $profilePicture = $request->file('profile_picture');

            $res = $user->updateProfileImage($profilePicture);
            if ($res !== 'success') {
                return redirect()->back()->withErrors(['error' => 'Failed to update user.']);
            }

        }

        if ($request->hasFile('banner_picture')) {
            $bannerPicture = $request->file('banner_picture');

            $res = $user->updateBannerImage($bannerPicture);

            if ($res !== 'success') {
                return redirect()->back()->withErrors(['error' => 'Failed to update user.']);
            }

        }
        $user->save();

        return redirect()->route('user.show', $user->id)->withSuccess('Profile updated successfully.');
    }

    public function recommendedUsers($currentUser, $visitedUser)
    {
        if ($currentUser->id !== $visitedUser->id) {
            $users = $visitedUser->followers()
                ->whereNotIn('follower_id', $currentUser->following()->pluck('followed_id')->toArray())
                ->orderBy('num_followers', 'DESC')
                ->take(20)
                ->get();

            return $users->random(min($users->count(), 5))
                ->sortByDesc('num_followers')
                ->values();

        } else {
            $users = User::whereNotIn('id', $currentUser->following()->pluck('followed_id')->toArray())
                ->where('id', '<>', $currentUser->id)
                ->orderBy('num_followers', 'DESC')
                ->take(20)
                ->get();

            return $users->random(min($users->count(), 5))
                ->sortByDesc('num_followers')
                ->values();
        }

    }

    public function destroy(Request $request, int $id)
    {

        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        DB::transaction(function () use ($id) {
            // Delete notifications.
            Notification::where('receiver_id', $id)
                ->delete();
            // Delete follow requests.
            FollowRequest::where('follower_id', $id)
                ->orWhere('followed_id', $id)
                ->delete();
            // Delete group join requests.
            GroupJoinRequest::where('requester_id', $id)
                ->delete();
            // Delete group invitations.
            GroupInvitation::where('invitee_id', $id)
                ->delete();
            // Delete group memberships.
            GroupMember::where('user_id', $id)
                ->delete();
            // Delete bans.
            Ban::where('user_id', $id)
                ->delete();
            // Delete user token.
            Token::where('user_id', $id)
                ->delete();
            // Delete user stats.
            $userStats = UserStats::where('user_id', $id)->first();
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
            DB::table('users')
                ->where('id', $id)
                ->update([
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

        // If the user deleted is own account, log out.
        if (Auth::check() && Auth::id() === $id) {
            Auth::logout();
        }

        return redirect()->route('home')->withSuccess('User deleted successfully.');
    }
}
