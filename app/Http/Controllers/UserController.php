<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Follow;
use App\Models\GroupMember;
use App\Models\Language;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Technology;
use App\Models\Token;
use App\Models\TopProject;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show(Request $request, int $id): View|Factory
    {
        $user = User::findOrFail($id);

        $this->authorize('view', $user);
        $this->authorize('viewAny', Post::class);

        $posts = $user->posts()
            ->visibleTo(auth()->user())
            ->orderBy('is_announcement', 'DESC')
            ->orderBy('creation_timestamp', 'DESC')
            ->orderBy('likes', 'DESC')
            ->paginate(15);

        $recommendedUsers = auth()->check() ? $this->recommendedUsers(auth()->user(), $user) : null;
        $numRequests = auth()->check() ? auth()->user()->followRequests()->where('status', 'pending')->count() : 0;

        if ($request->ajax()) {
            return view('partials.post-list', ['posts' => $posts, 'showEmpty' => false]);
        }

        return view('pages.user', [
            'user' => $user,
            'posts' => $posts,
            'recommendedUsers' => $recommendedUsers,
            'numRequests' => $numRequests,
        ]);
    }

    public function showGroups(int $id): View|Factory
    {
        $user = User::findOrFail($id);

        $this->authorize('view', $user);

        $groups = $user->groups()
            ->orderBy('name', 'ASC')
            ->paginate(10);

        return view('pages.user-groups', [
            'user' => $user,
            'groups' => $groups,
        ]);
    }

    public function showInvites(int $id): View|Factory
    {
        $user = User::findOrFail($id);

        $this->authorize('view', $user);

        $invites = $user->groupsInvitedTo()->orderBy('name', 'ASC')->paginate(10);

        return view('pages.user-group-invites', [
            'user' => $user,
            'invites' => $invites,
        ]);
    }

    public function edit(int $id): RedirectResponse|View|Factory
    {
        $user = User::findOrFail($id);

        if (! auth()->check()) {
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

        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $user);

        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'nullable|string',
            'is_public' => 'nullable',
            'handle' => ['required', 'alpha_dash:ascii', 'max:20', Rule::unique('users')->ignore($user->id)],
            'github_url' => 'nullable|url',
            'gitlab_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
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

    public function notifications(int $id): View|Factory
    {
        $user = User::findOrFail($id);

        $this->authorize('viewNotifications', $user);

        $notifications = $user->notifications()->orderBy('timestamp', 'desc')->paginate(10);

        foreach ($notifications as $notification) {
            switch ($notification->type) {
                case 'post_like':
                    $postLike = PostLike::findOrFail($notification->post_like_id);
                    $notification->user = $postLike->liker;
                    $notification->post = $postLike->post;
                    break;
                case 'comment_like':
                    $commentLike = CommentLike::findOrFail($notification->comment_like_id);
                    $notification->user = $commentLike->user;
                    $notification->comment = $commentLike->comment;
                    $notification->post = $commentLike->comment->post;
                    break;
                case 'comment':
                    $notification->comment = Comment::findOrFail($notification->comment_id);
                    $notification->user = $notification->comment->author;
                    $notification->post = $notification->comment->post;
                    break;
                case 'follow':
                    $follow = Follow::findOrFail($notification->follow_id);
                    $notification->user = $follow->follower;
                    break;
                default:
                    break;
            }
        }

        return view('pages.notifications', [
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function followers(int $id): View|Factory
    {
        $user = User::findOrFail($id);

        $this->authorize('viewContent', $user);

        return view('pages.followers', ['user' => $user, 'followers' => $user->followers()->paginate(30)]);
    }

    public function following(int $id): View|Factory
    {
        $user = User::findOrFail($id);

        $this->authorize('viewContent', $user);

        return view('pages.following', ['user' => $user, 'following' => $user->following()->paginate(30)]);
    }

    public function requests(int $id): View|Factory
    {
        $user = User::findOrFail($id);

        $this->authorize('viewRequests', $user);

        $followRequests = $user->followRequests()
            ->where('status', 'pending')
            ->paginate(16);

        return view('pages.requests', ['user' => $user, 'requests' => $followRequests]);
    }

    public function destroy(Request $request, int $id)
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

            $user->name = $id;
            $user->email = $id;
            $user->password = $id;
            $user->handle = $id;
            $user->is_public = false;
            $user->description = null;
            $user->profile_picture_url = null;
            $user->banner_image_url = null;
            $user->is_deleted = true;

            $user->save();
        });

        // If the user deleted is own account, log out.
        if (auth()->check() && auth()->id() === $id) {
            auth()->logout();
        }

        return redirect()->route('home')->withSuccess('User deleted successfully.');
    }

    public function showTokenSettings(): RedirectResponse|View|Factory
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }
        $this->authorize('view', [Token::class, auth()->user()->token]);

        return view('pages.user-token', ['user' => auth()->user()]);
    }

    public function showChangePassword(): RedirectResponse|View|Factory
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        return view('pages.user-change-password', ['user' => auth()->user()]);
    }

    public function changePassword(Request $request)
    {

        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (! password_verify($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['error' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->input('new_password'));

        $user->save();

        return redirect()->back()->withSuccess('Password changed successfully.');
    }
}
