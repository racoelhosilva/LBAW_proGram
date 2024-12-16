<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function searchUsers(string $query)
    {
        $users = User::whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return $users;
    }

    public function searchPosts(string $query)
    {
        $posts = Post::visibleTo(Auth::user())
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return $posts;
    }

    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string',
        ]);

        $query = $request->input('query') ?? '';
        $posts = $this->searchPosts($query);
        $users = $this->searchUsers($query);

        return view('pages.search', ['posts' => $posts, 'users' => $users]);
    }
}
