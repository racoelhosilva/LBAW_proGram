<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return view('group.index');
    }

    public function store(Request $request)
    {
        // Validate the request...
    }

    public function create()
    {
        return view('group.create');
    }

    public function show(int $id)
    {
        $group = Group::findOrFail($id);
        $posts = $group->posts()->paginate(10);

        return view('pages.group', ['group' => $group, 'posts' => $posts]);
    }

    public function showMembers(int $id)
    {
        $group = Group::findOrFail($id);
        $members = $group->members()->paginate(10);

        return view('pages.group-members', ['group' => $group, 'members' => $members]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request...
    }

    public function destroy($id)
    {
        // Delete the group...
    }

    public function edit($id)
    {
        return view('group.edit', ['id' => $id]);
    }
}
