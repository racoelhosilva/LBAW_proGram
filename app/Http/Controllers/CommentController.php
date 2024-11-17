<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
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
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        $comment = Comment::create($request->all());

        return response()->json($comment, 201); // 201 = Created
    }

    public function delete(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        $comment->update($request->all());

        return response()->json($comment);
    }
}
