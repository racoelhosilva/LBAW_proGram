<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupPost;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiPostController extends Controller
{
    // Retrieve all posts
    public function index()
    {
        $posts = Post::all();

        return response()->json($posts);
    }

    // Retrieve a single post by ID
    public function show($id)
    {
        $post = Post::findOrFail($id);

        return response()->json($post);
    }

    public function store(Request $request)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'You must be logged in to create a post.'], 401);
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
                'is_public' => $request->input('is_public', true),
                'is_announcement' => $request->input('is_announcement', false),
                'likes' => 0,
            ]);

            $post->tags()->sync($request->input('tags'));

            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create post.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'You must be logged in to update a post.'], 401);
        }

        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->author_id) {
            return response()->json(['error' => 'You are not authorized to update this post.'], 403);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        $post->update($request->all());

        $post->tags()->sync($request->input('tags'));

        return response()->json($post);
    }

    public function like(Request $request, int $id)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'You must be logged in to like a post.'], 401);
        }

        $post = Post::findOrFail($id);

        if (! Auth::id() === $post->author_id) {
            return response()->json(['error' => 'You cannot like your own post.'], 403);
        }

        try {
            $like = PostLike::create([
                'liker_id' => auth()->id(),
                'post_id' => $post->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to like post.'], 500);
        }

        return response()->json($like, 201);
    }

    public function unlike(Request $request, int $id)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'You must be logged in to update a post.'], 401);
        }

        $post = Post::findOrFail($id);

        if (! Auth::id() === $post->author_id) {
            return response()->json(['error' => 'You cannot like your own post.'], 403);
        }

        $like = PostLike::where('post_id', $id)
            ->where('liker_id', Auth::id())
            ->first();

        if (! $like) {
            return response()->json(['error' => 'You have not liked this post.'], 400);
        }

        DB::transaction(function () use ($like) {
            Notification::where('post_like_id', $like->id)->delete();
            $like->delete();
        });

        return response()->json($like);
    }

    public function indexComments($id)
    {
        $post = Post::findOrFail($id);

        $comments = $post->allComments;

        return response()->json($comments);
    }

    public function indexLikes($id)
    {
        $post = Post::findOrFail($id);

        $likes = [];
        foreach ($post->allLikes as $like) {
            $likes[] = $like->liker;
        }

        return response()->json($likes);
    }

    public function indexTags($id)
    {
        $post = Post::findOrFail($id);

        $tags = [];
        foreach ($post->tags as $tag) {
            $tags[] = $tag->tag;
        }

        return response()->json($tags);
    }

    public function indexAttachments($id)
    {
        $post = Post::findOrFail($id);

        $attachments = $post->attachments;

        return response()->json($attachments);
    }

    public function destroy(Request $request, $postId)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'You must be logged in to update a post.'], 401);
        }

        $post = Post::findOrFail($postId);

        if (auth()->id() !== $post->author_id) {
            return response()->json(['error' => 'You are not authorized to update this post.'], 403);
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

            return response()->json(['message' => 'Post deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the post.', 'details' => $e->getMessage()], 500);
        }
    }
}
