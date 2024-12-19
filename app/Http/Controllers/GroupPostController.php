<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPost;
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

        if ($group->is_public != $post->is_public) {
            return redirect()->route('group.show', $group->id)->withError('Group and post must have the same visibility.');
        }

        try {
            DB::transaction(function () use ($post, $request) {
                $post->title = $request->input('title');
                $post->text = $request->input('text');
                $post->author_id = Auth::id();
                $post->is_public = $request->input('is_public', false);
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
}
