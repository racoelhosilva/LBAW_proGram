<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupPostController extends Controller
{
    public function showCreatePostForm(Request $request, int $groupId)
    {
        $group = Group::findOrFail($groupId);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', [GroupPost::class, $group]);

        return view('pages.create-group-post', ['group' => $group,  'tags' => Tag::all()]);
    }
}
