<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
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
        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'is_public' => 'nullable|boolean',
            'is_announcement' => 'nullable|boolean',
        ]);

        // Create post with the provided or default author_id and other fields
        try {
            $post = Post::create([
                'title' => $request->input('title'),
                'text' => $request->input('text'),
                'author_id' => $request->input('author_id'),
                'is_public' => $request->input('is_public', true),
                'is_announcement' => $request->input('is_announcement', false),
            ]);

            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create post.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //WORK IN PROGRESS
    public function delete($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            // Return a 200 OK response with a success message
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            // If an error occurs, return a 500 Internal Server Error response
            return response()->json([
                'error' => 'Failed to delete post.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //WORK IN PROGRESS
    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $post = Post::findOrFail($id);

        $post->update($request->only(['title', 'content']));

        return response()->json($post);
    }
}
