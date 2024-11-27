<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\GroupPost;
use App\Models\Notification;
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

        DB::transaction(function () use ($post, $request) {
            $post->title = $request->input('title');
            $post->text = $request->input('text');
            $post->author_id = Auth::id();
            $post->is_public = $request->input('is_public', false);
            $post->is_announcement = $request->input('is_announcement', false);

            $post->save();

            $post->tags()->sync($request->input('tags'));
        });

        return redirect(route('post.show', $post->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $post = Post::findOrFail($id);

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
            'title' => 'string|max:255',
            'text' => 'string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        $post = Post::findOrFail($id);

        DB::transaction(function () use ($post, $request) {
            $post->title = $request->input('title', $post->title);
            $post->text = $request->input('text', $post->text);
            $post->is_public = $request->filled('is_public');
            $post->is_announcement = $request->filled('is_announcement');

            $post->save();

            $post->tags()->sync($request->input('tags'));
        });

        return redirect()->route('post.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        $this->authorize('forceDelete', $post);

        DB::transaction(function () use ($post) {
            Notification::where('post_id', $post->id)
                ->orWhereIn('comment_id', function ($query) use ($post) {
                    $query->select('id')
                        ->from('comment')
                        ->where('post_id', $post->id);
                })
                ->orWhereIn('post_like_id', function ($query) use ($post) {
                    $query->select('id')
                        ->from('post_like')
                        ->where('post_id', $post->id);
                })
                ->orWhereIn('comment_like_id', function ($query) use ($post) {
                    $query->select('comment_like.id')
                        ->from('comment_like')
                        ->join('comment', 'comment_like.comment_id', '=', 'comment.id')
                        ->where('comment.post_id', $post->id);
                })
                ->delete();

            foreach ($post->allComments as $comment) {
                $comment->allLikes()->delete();
                $comment->delete();
            }

            $post->allLikes()->delete();
            $post->tags()->detach();
            $post->attachments()->delete();

            GroupPost::where('post_id', $post->id)->delete();

            $post->delete();
        });

        if (url()->previous() === route('post.edit', $post->id)) {
            return redirect()->route('user.show', $post->author->id)->withSuccess('Post deleted successfully.');
        }

        return redirect()->back()->withSuccess('Post deleted successfully.');
    }
}
