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

        return view('pages/create-post', [
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
                'is_public' => $request->input('is_public', false),
                'is_announcement' => $request->input('is_announcement', false),
                'likes' => 0,
            ]);

            $post->tags()->sync($request->input('tags'));

            return redirect('post/'.$post->id);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create post.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $post = Post::findOrFail($id);

        $this->authorize('view', $post);
        $this->authorize('viewAny', Comment::class);

        return view('pages/post', ['post' => $post]);
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

        return view('pages/edit-post', [
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
            'title' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        try {
            $post->update([
                'title' => $request->input('title', $post->title),
                'text' => $request->input('text', $post->text),
                'is_public' => $request->input('is_public', false),
                'is_announcement' => $request->input('is_announcement', false),
            ]);

            $post->tags()->sync($request->input('tags'));

            return redirect('post/'.$post->id);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update post.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        $this->authorize('forceDelete', $post);

        try {
            DB::transaction(function () use ($post) {
                $postId = $post->id;

                Notification::where('post_id', $postId)
                    ->orWhereIn('comment_id', function ($query) use ($postId) {
                        $query->select('id')
                            ->from('comment')
                            ->where('post_id', $postId);
                    })
                    ->orWhereIn('post_like_id', function ($query) use ($postId) {
                        $query->select('id')
                            ->from('post_like')
                            ->where('post_id', $postId);
                    })
                    ->orWhereIn('comment_like_id', function ($query) use ($postId) {
                        $query->select('comment_like.id')
                            ->from('comment_like')
                            ->join('comment', 'comment_like.comment_id', '=', 'comment.id')
                            ->where('comment.post_id', $postId);
                    })
                    ->delete();

                foreach ($post->allComments as $comment) {
                    $comment->allLikes()->delete();
                    $comment->delete();
                }

                $post->allLikes()->delete();

                $post->tags()->detach();

                $post->attachments()->delete();

                GroupPost::where('post_id', $postId)->delete();

                $post->delete();

            });
            $referer = url()->previous();

            // if the previous page was the post page, then redirect to profile, otherwise go to the previous url.
            if ($referer === route('post.edit', $post)) {
                return redirect()->route('user.show', $post->author->id)->with('success', 'Post deleted successfully.');
            }

            return redirect()->back()->with('success', 'Post deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete post.']);
        }
    }
}
