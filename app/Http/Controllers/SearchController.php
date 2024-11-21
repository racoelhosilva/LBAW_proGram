<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function fullTextSearchUsers(Request $request): array
    {
        $query = $request->input('query');

        $posts = Post::where('is_public', true)
            ->whereRaw("ts_vector @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(ts_vector, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return $posts;
    }
}
