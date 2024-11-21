<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function fullTextSearchUsers(Request $request)
    {
        $query = $request->input('query');

        $posts = User::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return $posts;
    }

    public function listUsers(Request $request)
    {
        $users = $this->fullTextSearchUsers($request);

        return view('pages.user-search', ['users' => $users]);
    }
}
