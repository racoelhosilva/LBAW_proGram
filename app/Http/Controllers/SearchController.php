<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return $users;
    }

    public function searchPosts(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return $posts;
    }

    public function list(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $posts = $this->searchPosts($request);
        $users = $this->searchUsers($request);

        return view('pages.search', ['posts' => $posts, 'users' => $users]);
    }
}
