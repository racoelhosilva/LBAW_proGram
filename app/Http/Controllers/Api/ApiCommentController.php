<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

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
                'message' => $e->getMessage(),
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
}
