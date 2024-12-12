<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCommentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Comment::class);

        $comments = Comment::whereHas('post', function ($query) {
            $query->where('is_public', true);
        })->get();

        return response()->json($comments);
    }

    public function show(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('view', $comment);

        return response()->json($comment);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Comment::class);

        $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer|exists:post,id',
            'author_id' => 'nullable|exists:users,id',
        ]);
        try {
            $comment = Comment::create([
                'content' => $request->input('content'),
                'post_id' => $request->input('post_id'),
                'author_id' => $request->input('author_id'),
            ]);

            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create comment.'.$e,
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($request->all());

        return response()->json($comment);
    }

    public function like(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('like', $comment);

        if (CommentLike::where('comment_id', $id)->where('liker_id', Auth::id())->exists()) {
            return response()->json(['error' => 'You have already liked this comment'], 400);
        }

        $like = new CommentLike;

        $like->liker_id = Auth::id();
        $like->comment_id = $id;

        $like->save();

        return response()->json($like, 201);
    }

    public function unlike(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('like', $comment);

        $like = CommentLike::where('comment_id', $id)
            ->where('liker_id', Auth::id())
            ->first();

        if (! $like) {
            return response()->json(['error' => 'You have not liked this comment'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Comment unliked successfully'], 200);
    }

    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.'], 200);
    }
}
