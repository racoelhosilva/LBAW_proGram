<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    public function index()
    {
        return view('group.index');
    }

    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Group::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'is_public' => 'boolean',
            'owner_id' => 'required|integer',
        ]);

        $group = new Group;

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->is_public = $request->input('is_public') ?? true;
        $group->owner_id = $request->input('owner_id');
        $group->save();

        return redirect()->route('group.show', $group->id)->withSuccess('Group created successfully.');
    }

    public function create()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Group::class);

        return view('pages.create-group');
    }

    public function show(int $id)
    {
        $group = Group::findOrFail($id);
        $posts = $group->posts()->visibleTo(Auth::user())->where('is_announcement', false)->get();
        $announcements = $group->posts()->visibleTo(Auth::user())->where('is_announcement', true)->get();
        $isOwner = Auth::check() && Auth::id() === $group->owner_id;
        $isMember = Auth::check() && $group->members()->where('user_id', Auth::id())->exists();
        $user = Auth::user();

        return view('pages.group', ['group' => $group, 'posts' => $posts, 'announcements' => $announcements, 'isOwner' => $isOwner, 'isMember' => $isMember, 'user' => $user]);
    }

    public function showMembers(int $groupId)
    {
        $group = Group::findOrFail($groupId);
        $members = $group->members->where('id', '!=', $group->owner->id); // Exclude the owner

        return view('pages.group-members', ['group' => $group, 'members' => $members]);
    }

    public function showRequests($groupId)
    {
        $group = Group::findOrFail($groupId);
        $usersWhoWantToJoin = $group->joinRequests()->where('status', 'pending')->paginate(10);

        return view('pages.group-requests', ['group' => $group, 'usersWhoWantToJoin' => $usersWhoWantToJoin]);
    }

    public function showInvites($groupId)
    {
        $group = Group::findOrFail($groupId);

        $userIds = request()->query('users');
        if ($userIds) {
            $userIdsArray = explode(',', $userIds);
            Log::info($userIdsArray);
            $usersSearched = User::whereIn('users.id', $userIdsArray)->get();
        } else {
            $usersSearched = $group->invitedUsers;
        }

        return view('pages.group-invites', ['group' => $group, 'usersSearched' => $usersSearched]);
    }

    public function update(Request $request, int $id)
    {

        $group = Group::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $group);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'is_public' => 'boolean',
            'owner_id' => 'required|integer',
        ]);
        if ($group->is_public != $request->filled('is_public')) {
            foreach ($group->posts as $post) {
                $post->is_public = $request->filled('is_public');
                $post->save();
            }
        }

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->is_public = $request->filled('is_public');
        $group->owner_id = $request->input('owner_id');
        $group->save();

        return redirect()->route('group.show', $group->id)->withSuccess('Group edited successfully.');
    }

    public function destroy($id)
    {
        // Delete the group...
    }

    public function edit($id)
    {

        $group = Group::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $group);

        return view('pages.edit-group', ['group' => $group]);
    }

    public function manage($id)
    {
        $group = Group::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $group);
        $usersWhoWantToJoin = $group->joinRequests()->where('status', 'pending')->paginate(10);

        $userIds = request()->query('users');
        if ($userIds) {
            $userIdsArray = explode(',', $userIds);
            $usersSearched = $group->invitedUsers()->whereIn('id', $userIdsArray)->get();
        } else {
            $usersSearched = $group->invitedUsers;
        }
        Log::info($usersSearched);

        return view('pages.manage-group', ['group' => $group, 'usersWhoWantToJoin' => $usersWhoWantToJoin, 'usersSearched' => $usersSearched]);
    }

    public function showCreatePostForm(Request $request, int $groupId)
    {
        $group = Group::findOrFail($groupId);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        return view('pages.create-group-post', ['group' => $group,  'tags' => Tag::all()]);
    }

    public function createPost(Request $request, int $groupId)
    {
        $group = Group::findOrFail($groupId);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Post::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        try {
            $post = Post::create([
                'title' => $request->input('title'),
                'text' => $request->input('text'),
                'author_id' => auth()->id(),
                'is_public' => $request->input('is_public', true),
                'is_announcement' => $request->input('is_announcement', false),
                'likes' => 0,
            ]);

            $post->tags()->sync($request->input('tags'));
            $group->posts()->attach($post->id);

            return redirect()->route('group.show', $group->id)->withSuccess('Post created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('group.show', $group->id)->withError('Failed to create post.');
        }
    }
}
