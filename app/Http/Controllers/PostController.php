<?php

namespace App\Http\Controllers;

use App\Models\GroupPost;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        return view('pages/create-post', [
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

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
    public function show(Post $post): View
    {
        return view('pages/post',
            ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::id() !== $post->author_id) {
            return redirect()->route('home');
        }

        return view('pages/edit-post', [
            'post' => $post,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
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
    public function destroy(Post $post)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::id() !== $post->author_id) {
            return redirect()->route('home');
        }

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

            return redirect('user/'.auth()->id())->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            dd($e);

            return redirect()->back()->withErrors(['error' => 'Failed to delete post.']);
        }
    }
}
