<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    const ALLOWED_TAGS = '<p><br><strong><em><u><code><ol><ul><li><span><div><a><img><iframe>';

    /**
     * Show the form for creating a new resource.
     */
    public function create(): RedirectResponse|View|Factory
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Post::class);

        return view('pages.create-post', [
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|Redirector
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Post::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable',
            'is_announcement' => 'nullable',
        ]);

        $post = new Post;
        $text = strip_tags($request->input('text'), self::ALLOWED_TAGS);

        DB::transaction(function () use ($post, $request, $text) {
            $post->title = $request->input('title');
            $post->text = $text;
            $post->author_id = Auth::id();
            $post->is_public = $request->input('is_public', false);
            $post->is_announcement = $request->input('is_announcement', false);

            $post->save();

            $post->tags()->sync($request->input('tags'));
        });

        return redirect()->route('post.show', $post->id)->withSuccess('Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $post = Post::with('author')->findOrFail($id);

        $this->authorize('view', $post);
        $this->authorize('viewAny', Comment::class);

        return view('pages.post', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): RedirectResponse|View|Factory
    {
        $post = Post::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('update', $post);

        return view('pages.edit-post', [
            'post' => $post,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): Redirector|RedirectResponse
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        $post = Post::findOrFail($id);

        $text = strip_tags($request->input('text'), self::ALLOWED_TAGS);

        DB::transaction(function () use ($post, $request, $text) {
            $post->title = $request->input('title', $post->title);
            $post->text = $text;
            $post->is_public = $request->filled('is_public');
            $post->is_announcement = $request->filled('is_announcement');

            $post->save();

            $post->tags()->sync($request->input('tags'));
        });

        return redirect()->route('post.show', $post->id)->withSuccess('Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        $this->authorize('forceDelete', $post);

        $post->delete();

        if (url()->previous() === route('post.edit', $post->id)) {
            return redirect()->route('user.show', $post->author->id)->withSuccess('Post deleted successfully.');
        }

        return redirect()->back()->withSuccess('Post deleted successfully.');
    }
}
