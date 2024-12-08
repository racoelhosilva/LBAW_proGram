<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiPostController extends Controller
{
    // Retrieve all posts
    public function index()
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::where('is_public', true);

        if (Auth::check()) {
            $followingIds = Auth::user()->following->pluck('id');
            $posts = $posts->orWhereIn('author_id', $followingIds);
        }

        $posts = $posts->get();

        return response()->json($posts);
    }

    // Retrieve a single post by ID
    public function show($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('view', $post);

        return response()->json($post);
    }

    public function store(Request $request)
    {
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

        $post->update($request->all());

        $post->tags()->sync($request->input('tags'));

        return response()->json($post);
    }

    public function like(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('like', $post);

        if (PostLike::where('post_id', $id)->where('liker_id', Auth::id())->exists()) {
            return response()->json(['error' => 'You have already liked this post.'], 400);
        }

        $like = new PostLike;

        $like->liker_id = Auth::id();
        $like->post_id = $post->id;

        $like->save();

        return response()->json($like, 201);
    }

    public function unlike(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('like', $post);

        $like = PostLike::where('post_id', $id)
            ->where('liker_id', Auth::id())
            ->first();

        if (! $like) {
            return response()->json(['error' => 'You have not liked this post.'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Post unliked successfully'], 200);
    }

    public function indexComments($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('view', $post);
        $this->authorize('viewAny', Comment::class);

        $comments = $post->allComments;

        return response()->json($comments);
    }

    public function indexLikes($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('view', $post);

        $likes = $post->allLikes;

        return response()->json($likes);
    }

    public function indexTags($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('view', $post);

        $tags = $post->tags;

        return response()->json($tags);
    }

    public function indexAttachments($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('view', $post);

        $attachments = $post->attachments;

        return response()->json($attachments);
    }

    public function destroy(Request $request, $postId)
    {

        $post = Post::findOrFail($postId);

        $this->authorize('forceDelete', $post);

        try {
            $post->delete();

            return response()->json(['message' => 'Post deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the post.'], 500);
        }
    }
}
