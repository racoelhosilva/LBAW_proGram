<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function fullTextSearchUsers(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return $posts;
    }

    public function show(Request $request)
    {
        $posts = $this->fullTextSearchUsers($request);

        return view('auth.login');
    }
}
