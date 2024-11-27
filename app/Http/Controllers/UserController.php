<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show(User $user)
    {
        $authuser = Auth::user();
        $isOwnProfile = $authuser && $authuser->id === $user->id;
        $isFollowing = $authuser && $authuser->following()->where('followed_id', $user->id)->exists();
        $recommendedUsers = Auth::check() ? $this->recommendedUsers($authuser, $user) : null;

        return view('pages.user', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
            'recommendedUsers' => $recommendedUsers,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function edit(User $user)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::id() !== $user->id) {
            return redirect()->route('home');
        }

        return view('pages.edit-user', [
            'user' => $user,
            'languages' => Language::all(),
            'technologies' => Technology::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::id() !== $user->id) {
            return redirect()->route('home');
        }
        // Validate incoming request data
        $request->validate([
            'name' => 'string|max:30',
            'description' => 'string|max:200',
            'is_public' => 'boolean',
            'handle' => ['string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'languages' => 'array',
            'languages.*' => 'exists:language,id',
            'technologies' => 'array',
            'technologies.*' => 'exists:technology,id',
            'projects' => 'array',
            'new_projects' => 'array',
        ]);

        try {
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->is_public = $request->input('is_public', true);
            $user->handle = $request->input('handle');
            $user->stats->languages()->sync($request->input('languages'));
            $user->stats->technologies()->sync($request->input('technologies'));

            $projectsFromRequest = $request->input('projects'); // array of projects data (name, url)
            $existingProjectIds = [];
            if ($projectsFromRequest !== null) {

                foreach ($projectsFromRequest as $index => $project) {
                    $existingProjectIds[] = $index;
                }
            }
            // Iterate through the user's existing projects and delete if not in the request
            if ($user->stats->projects !== null) {
                foreach ($user->stats->projects as $project) {
                    if (! in_array($project->id, $existingProjectIds)) {
                        $project->delete(); // Delete project if its ID is not in the updated list
                    }

                }
            }
            if ($request->input('new_projects') !== null) {
                foreach ($request->input('new_projects') as $project) {

                    $project = [
                        'user_stats_id' => $user->stats->id,
                        'name' => $project['name'],
                        'url' => $project['url'],
                    ];
                    $createdProject = $user->stats->projects()->create($project);
                }
            }
            $user->save();

            return redirect()->route('user.show', $user->id);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update user.']);
        }
    }

    public function recommendedUsers($currentUser, $visitedUser)
    {
        if ($currentUser->id !== $visitedUser->id) {
            $users = $visitedUser->followers()
                ->whereNotIn('follower_id', $currentUser->following()->pluck('followed_id')->toArray())
                ->orderBy('num_followers', 'desc')
                ->take(20)
                ->get();

            return $users->random(min($users->count(), 5))
                ->sortByDesc('num_followers')
                ->values();
        } else {

            $users = User::whereNotIn('id', $currentUser->following()->pluck('followed_id')->toArray())
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
