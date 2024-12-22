<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupJoinRequest;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Group::class);

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'is_public' => 'boolean',
        ]);

        $group = new Group;

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->is_public = $request->input('is_public') ?? true;
        $group->owner_id = auth()->id();
        $group->save();

        return redirect()->route('group.show', $group->id)->withSuccess('Group created successfully.');
    }

    public function create()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Group::class);

        return view('pages.create-group');
    }

    public function show(Request $request, int $id)
    {
        $group = Group::findOrFail($id);
        $this->authorize('viewAny', $group);

        $request->validate([
            'announcements' => 'nullable|boolean',
        ]);

        $posts = $group->posts()->visibleTo(Auth::user())->where('is_announcement', false);
        $announcements = $group->posts()->visibleTo(Auth::user())->where('is_announcement', true);

        if ($request->ajax()) {
            $this->authorize('viewContent', $group);

            if ($request->input('announcements')) {
                return view('partials.post-list', ['posts' => $announcements->paginate(15), 'showEmpty' => false]);
            } else {
                return view('partials.post-list', ['posts' => $posts->paginate(15), 'showEmpty' => false]);
            }
        }

        return view('pages.group', ['group' => $group, 'posts' => $posts->paginate(15), 'announcements' => $announcements->paginate(15)]);
    }

    public function showMembers(int $groupId)
    {

        $group = Group::findOrFail($groupId);
        $this->authorize('viewContent', $group);

        $members = $group->members()->where('id', '!=', $group->owner->id)->paginate(15); // Exclude the owner

        return view('pages.group-members', ['group' => $group, 'members' => $members]);
    }

    public function showRequests($groupId)
    {
        $group = Group::findOrFail($groupId);
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('manageRequests', $group);
        $usersWhoWantToJoin = $group->joinRequests()->where('status', 'pending')->paginate(15);

        return view('pages.group-requests', ['group' => $group, 'usersWhoWantToJoin' => $usersWhoWantToJoin]);
    }

    public function showInvites($groupId)
    {
        $group = Group::findOrFail($groupId);
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('invite', $group);
        $searchQuery = request()->query('invite_query');
        $ownerId = $group->owner->id;
        $searched = false;

        if ($searchQuery) {
            $searched = true;
            $usersSearched = User::where('id', '!=', $ownerId)
                ->whereNotIn('id', function ($query) use ($groupId) {
                    $query->select('user_id')
                        ->from('group_member')
                        ->where('group_id', $groupId);
                })
                ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$searchQuery])
                ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$searchQuery])
                ->paginate(15);
            $usersInvited = [];
        } else {
            $usersSearched = [];
            $usersInvited = $group->invitedUsers()->paginate(15);
        }

        return view('pages.group-invites', [
            'group' => $group,
            'usersSearched' => $usersSearched,
            'usersInvited' => $usersInvited,
            'searched' => $searched,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $group = Group::findOrFail($id);

        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $group);
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'is_public' => 'nullable|boolean',
        ]);

        if ($group->is_public != $request->filled('is_public')) {
            foreach ($group->posts as $post) {
                $post->is_public = $request->filled('is_public');
                $post->save();
            }
        }
        if ($request->filled('is_public')) {
            GroupJoinRequest::where('group_id', $id)->where('status', 'pending')->update(['status' => 'accepted']);
        }

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->is_public = $request->filled('is_public');
        $group->owner_id = auth()->id();
        $group->save();

        return redirect()->route('group.show', $group->id);
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);

        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $group);

        return view('pages.edit-group', ['group' => $group]);
    }

    public function join(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('join', $group);
        $user = Auth::user();

        if ($group->is_public) {
            $groupMember = new GroupMember;
            $groupMember->group_id = $group_id;
            $groupMember->user_id = $user->id;
            $groupMember->join_timestamp = now();
            $groupMember->save();
        } else {
            $groupJoinRequest = new GroupJoinRequest;
            $groupJoinRequest->status = 'pending';
            $groupJoinRequest->group_id = $group_id;
            $groupJoinRequest->requester_id = $user->id;
            $groupJoinRequest->save();
        }

        return redirect()->route('group.show', ['id' => $group_id])
            ->withSuccess('You have joined the group.');
    }

    public function leave(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);

        $this->authorize('leave', $group);

        GroupMember::where('group_id', $group_id)->where('user_id', auth()->id())->delete();

        return redirect()->route('group.show', ['id' => $group_id])
            ->withSuccess('You have left the group.');
    }
}
