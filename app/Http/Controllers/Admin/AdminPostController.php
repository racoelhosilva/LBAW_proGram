<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    public function index(Request $request): Factory|View
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        if (empty($validated['query'])) {
            $posts = Post::orderBy('id')->paginate(20);
        } elseif (is_numeric($validated['query'])) {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $posts = Post::where('id', $validated['query'])
                ->orderBy('id')
                ->paginate(20);
        } else {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $posts = Post::where('title', 'ILIKE', $query)
                ->orWhere('text', 'ILIKE', $query)
                ->orWhereHas('author', function ($q) use ($query) {
                    $q->where('handle', 'ILIKE', $query)
                        ->orWhere('name', 'ILIKE', $query)
                        ->orWhere('email', 'ILIKE', $query);
                })
                ->orderBy('id')
                ->paginate(20);
        }

        return view('admin.post.index', ['posts' => $posts]);
    }
}
