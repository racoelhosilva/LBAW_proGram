<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiCommentController extends Controller
{
    public function list(Request $request)
    {
        // Retrieve all comments
        $comments = Comment::all();

        return response()->json($comments);
    }

    public function show(Request $request, $id)
    {
        $comment = Comment::findOrFail($id); // Throws 404 if not found

        return response()->json($comment);
    }

    public function create(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer|exists:post,id',
            'author_id' => 'nullable|exists:users,id',
        ]);

        try {
            // Create comment with the provided or default author_id
            $comment = Comment::create([
                'content' => $request->input('content'),
                'post_id' => $request->input('post_id'),
                'author_id' => $request->input('author_id'),
                'likes' => 0,
            ]);

            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create comment.',
            ], 500);
        }
    }

    //WORK IN PROGRESS
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $request->validate([
            'content' => 'sometimes|required|string',
            'likes' => 'sometimes|required|integer',
        ]);

        $comment->update($request->all());

        return response()->json($comment);
    }

    public function like(Request $request, $id)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'You must be logged in to like a comment.'], 401);
        }

        $comment = Comment::findOrFail($id);

        if (! Auth::id() === $comment->author_id) {
            return response()->json(['error' => 'You cannot like your own comment'], 403);
        }

        try {
            $like = CommentLike::create([
                'comment_id' => $id,
                'liker_id' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to like comment.',
            ], 400);
        }

        return response()->json($like, 201);
    }

    public function dislike(Request $request, $id)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'You must be logged in to unlike a comment.'], 401);
        }

        $comment = Comment::findOrFail($id);

        if (! Auth::id() === $comment->author_id) {
            return response()->json(['error' => 'You cannot unlike your own comment'], 403);
        }

        $like = CommentLike::where('comment_id', $id)
            ->where('liker_id', Auth::id())
            ->first();

        if (! $like) {
            return response()->json(['error' => 'You have not liked this comment'], 400);
        }

        DB::transaction(function () use ($like) {
            Notification::where('comment_like_id', $like->id)->delete();

            $like->delete();
        });

        return response()->json(['message' => 'You have unliked the comment.']);
    }
}
