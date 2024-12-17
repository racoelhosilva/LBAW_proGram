<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function searchUsers(string $query, bool $includeTotal = false)
    {
        $users = User::whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        return $includeTotal ? [$users->simplePaginate(10), $users->count()] : $users->simplePaginate(10);
    }

    public function searchPosts(string $query, bool $includeTotal = false)
    {
        $posts = Post::visibleTo(Auth::user())
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        return $includeTotal ? [$posts->simplePaginate(10), $posts->count()] : $posts->simplePaginate(10);
    }

    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string',
            'search_type' => 'nullable|string|in:posts,users,groups',
        ]);

        $query = $request->input('query') ?? '';

        if ($request->ajax()) {
            switch ($request->input('search_type')) {
                case 'posts':
                    $this->authorize('viewAny', Post::class);
                    $results = $this->searchPosts($query);
                    if ($request->ajax()) {
                        return view('partials.post-list', ['posts' => $results, 'showEmpty' => false]);
                    }
                    break;
                case 'users':
                    $this->authorize('viewAny', User::class);
                    $results = $this->searchUsers($query);
                    if ($request->ajax()) {
                        return view('partials.user-list', ['users' => $results, 'showEmpty' => false]);
                    }
                    break;
            }
        } else {
            switch ($request->input('search_type')) {
                case 'posts':
                    $this->authorize('viewAny', Post::class);
                    [$results, $numResults] = $this->searchPosts($query, true);
                    break;
                case 'users':
                    $this->authorize('viewAny', User::class);
                    [$results, $numResults] = $this->searchUsers($query, true);
                    break;
            }

            return view('pages.search', [
                'type' => $request->input('search_type'),
                'results' => $results,
                'numResults' => $numResults,
            ]);
        }
    }
}
