<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $posts = $group->posts()->paginate(10);
        $isOwner = Auth::check() && Auth::id() === $group->owner_id;
        $isMember = Auth::check() && $group->members()->where('user_id', Auth::id())->exists();

        return view('pages.group', ['group' => $group, 'posts' => $posts, 'isOwner' => $isOwner, 'isMember' => $isMember]);
    }

    public function showMembers(int $id)
    {
        $group = Group::findOrFail($id);
        $members = $group->members()->paginate(10);

        return view('pages.group-members', ['group' => $group, 'members' => $members]);
    }

    public function update(Request $request, $group)
    {

        $group = Group::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $group);

        // Validate the request...
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
}
