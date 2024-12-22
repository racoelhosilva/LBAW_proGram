<?php

namespace App\Http\Controllers\Api;

use App\Events\PostLikeEvent;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiPostController extends Controller
{
    const ALLOWED_TAGS = '<p><br><strong><em><u><code><ol><ul><li><span><div><a><img><iframe>';

    // Retrieve all posts
    public function index()
    {
        $user = auth()->user();

        $posts = Post::visibleTo($user)
            ->select(['id', 'author_id', 'title', 'text', 'creation_timestamp', 'is_announcement', 'is_public', 'likes', 'comments'])
            ->get();

        return response()->json($posts);
    }

    // Retrieve a single post by ID
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('view', $post);

        $postData = $post->only(['id', 'author_id', 'title', 'text', 'creation_timestamp', 'is_announcement', 'is_public', 'likes', 'comments']);

        return response()->json($postData);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);
        $request->validate([
            'title' => 'required|string',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        $text = strip_tags($request->input('text'), self::ALLOWED_TAGS);

        $post = new Post;

        DB::transaction(function () use ($post, $text, $request) {
            $post->title = $request->input('title');
            $post->text = $text;
            $post->author_id = Auth::id();
            $post->is_public = $request->input('is_public', false);
            $post->is_announcement = $request->input('is_announcement', false);

            $post->save();

            $post->tags()->sync($request->input('tags'));
        });

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string',
            'text' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($post, $text, $request) {
            $post->title = $request->input('title');
            $post->text = $text;
            $post->author_id = Auth::id();
            $post->is_public = $request->input('is_public', false);
            $post->is_announcement = $request->input('is_announcement', false);

            $post->save();

            $post->tags()->sync($request->input('tags'));
        });

        $postData = $post->only(['id', 'author_id', 'title', 'text', 'creation_timestamp', 'is_announcement', 'is_public', 'likes', 'comments']);

        return response()->json($postData);
    }

    public function like(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('like', $post);

        if (PostLike::where('post_id', $id)->where('liker_id', Auth::id())->exists()) {
            return response()->json(['error' => 'You have already liked this post.'], 409);
        }

        $like = new PostLike;

        $like->liker_id = Auth::id();
        $like->post_id = $post->id;

        $like->save();
        event(new PostLikeEvent($id, $post->author_id));

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
            return response()->json(['error' => 'You have not liked this post.'], 409);
        }

        $like->delete();

        return response()->json(['message' => 'Post unliked successfully']);
    }

    public function indexComments($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('view', $post);

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

        $tags = $post->tags->select(['id', 'name']);

        return response()->json($tags);
    }

    public function destroy(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $this->authorize('forceDelete', $post);

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }
}
