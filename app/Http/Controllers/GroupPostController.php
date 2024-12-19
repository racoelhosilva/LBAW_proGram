<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
