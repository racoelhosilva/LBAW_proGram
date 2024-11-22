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

        $fullTextResults = User::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        $exactSearchResults = User::where('is_public', true)
            ->whereRaw('name ILIKE ?', ["%$query%"])
            ->orWhereRaw('handle ILIKE ?', ["%$query%"]);

        $users = $exactSearchResults->union($fullTextResults)->get();

        return $users;
    }

    public function searchPosts(Request $request)
    {
        $query = $request->input('query');

        $fullTextResults = Post::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        $exactSearchResults = Post::with('author')
            ->where('is_public', true)
            ->whereRaw('title ILIKE ?', ["%$query%"])
            ->orWhereRaw('text ILIKE ?', ["%$query%"]);

        $posts = $exactSearchResults->union($fullTextResults)->get();

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
