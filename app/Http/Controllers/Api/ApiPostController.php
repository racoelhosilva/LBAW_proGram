<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupPost;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiPostController extends Controller
{
    // Retrieve all posts
    public function list()
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

    public function create(Request $request)
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
                'message' => $e->getMessage(),
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

    public function listComments($id)
    {
        $post = Post::findOrFail($id);

        $comments = $post->allComments;

        return response()->json($comments);
    }

    public function listLikes($id)
    {
        $post = Post::findOrFail($id);

        $likes = [];
        foreach ($post->allLikes as $like) {
            $likes[] = $like->liker;
        }

        return response()->json($likes);
    }

    public function listTags($id)
    {
        $post = Post::findOrFail($id);

        $tags = [];
        foreach ($post->tags as $tag) {
            $tags[] = $tag->tag;
        }

        return response()->json($tags);
    }

    public function listAttachments($id)
    {
        $post = Post::findOrFail($id);

        $attachments = $post->attachments;

        return response()->json($attachments);
    }

    public function delete(Request $request, $postId)
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
