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
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $this->authorize('viewAny', Post::class);

        $posts = Post::query();

        if (! empty($request->input('query'))) {
            $pattern = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->input('query')).'%';

            $posts = $posts->where('title', 'ILIKE', $pattern)
                ->orWhere('text', 'ILIKE', $pattern)
                ->orWhereHas('author', function ($query) use ($pattern) {
                    $query->where('handle', 'ILIKE', $pattern)
                        ->orWhere('name', 'ILIKE', $pattern)
                        ->orWhere('email', 'ILIKE', $pattern);
                });

            if (is_numeric($request->input('query'))) {
                $posts = $posts->orWhere('id', $request->input('query'));
            }
        }

        $posts = $posts->orderBy('id')->paginate(20);

        return view('admin.post.index', ['posts' => $posts]);
    }
}
