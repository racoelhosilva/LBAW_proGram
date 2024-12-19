<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupPostController extends Controller
{
    public function store(Request $request, int $groupId)
    {

        $group = Group::findOrFail($groupId);

        if (! Auth::check()) {
            return redirect()->route('login');
        }
        $this->authorize('create', [GroupPost::class, $group]);

        $request->validate([
            'title' => 'required|string',
            'text' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        if ($group->is_public != $request->input('is_public')) {
            return redirect()->route('group.show', $group->id)->withError('Group and post must have the same visibility.');
        }

        try {
            $post = new Post;
            DB::transaction(function () use ($post, $request) {
                $post->title = $request->input('title');
                $post->text = $request->input('text');
                $post->author_id = Auth::id();
                $post->is_public = $request->input('is_public');
                $post->is_announcement = $request->input('is_announcement', false);

                $post->save();
                $post->tags()->sync($request->input('tags'));
            });
            $group->posts()->attach($post->id);

            return redirect()->route('group.show', $group->id)->withSuccess('Post created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('group.show', $group->id)->withError('Failed to create post.');
        }
    }

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
