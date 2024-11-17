<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    //WORK IN PROGRESS
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'post_id' => 'required|integer|exists:posts,id',
            ]);

            $comment = new Comment;
            $comment->content = $request->input('content');
            $comment->post_id = $request->input('post_id');
            $comment->author_id = Auth::user()->id;

            $comment->save();

            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create comment',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //WORK IN PROGRESS
    public function delete(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    //WORK IN PROGRESS
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
