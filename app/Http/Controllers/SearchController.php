<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function searchUsers(string $query, bool $includeTotal = false)
    {
        $users = User::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        return $includeTotal ? [$users->simplePaginate(10), $users->count()] : $users->simplePaginate(10);
    }

    public function searchPosts(string $query, ?array $tags, bool $includeTotal = false)
    {
        $posts = Post::visibleTo(Auth::user())
            ->when($tags, function ($query, $tags) {
                $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags);
                });
            })
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        return $includeTotal ? [$posts->simplePaginate(10), $posts->count()] : $posts->simplePaginate(10);
    }

    public function searchPostsByAuthor(string $query, ?array $tags, bool $includeTotal = false)
    {
        $users = User::whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        $posts = Post::visibleTo(Auth::user())
            ->when($tags, function ($query, $tags) {
                $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags);
                });
            })
            ->joinSub($users, 'users', function (JoinClause $join) {
                $join->on('post.author_id', '=', 'users.id');
            })
            ->orderByRaw("ts_rank(users.tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        return $includeTotal ? [$posts->simplePaginate(10), $posts->count()] : $posts->simplePaginate(10);
    }

    public function searchGroup(string $query, ?array $tags, bool $includeTotal = false)
    {
        $groups = Group::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query]);

        return $includeTotal ? [$groups->simplePaginate(10), $groups->count()] : $groups->simplePaginate(10);
    }

    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'search_type' => 'nullable|string',
            'search_attr' => 'nullable|string',
        ]);

        $query = $request->input('query') ?? '';

        if ($request->ajax()) {
            switch ($request->input('search_type')) {
                case 'posts':
                default:
                    $this->authorize('viewAny', Post::class);
                    switch ($request->input('search_attr')) {
                        case 'author':
                            $results = $this->searchPostsByAuthor($query, $request->input('tags'));
                            break;
                        default:
                            $results = $this->searchPosts($query, $request->input('tags'));
                            break;
                    }

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

                case 'groups':
                    $this->authorize('viewAny', Group::class);
                    $results = $this->searchGroup($query, $request->input('tags'));
                    if ($request->ajax()) {
                        return view('partials.group-list', ['groups' => $results, 'showEmpty' => false]);
                    }
                    break;
            }
        } else {
            switch ($request->input('search_type')) {
                case 'posts':
                default:
                    $this->authorize('viewAny', Post::class);
                    switch ($request->input('search_attr')) {
                        case 'author':
                            [$results, $numResults] = $this->searchPostsByAuthor($query, $request->input('tags'), true);
                            break;
                        default:
                            [$results, $numResults] = $this->searchPosts($query, $request->input('tags'), true);
                            break;
                    }
                    break;

                case 'users':
                    $this->authorize('viewAny', User::class);
                    [$results, $numResults] = $this->searchUsers($query, true);
                    break;

                case 'groups':
                    $this->authorize('viewAny', Group::class);
                    [$results, $numResults] = $this->searchGroup($query, $request->input('tags'), true);
                    break;
            }

            return view('pages.search', [
                'type' => $request->input('search_type'),
                'results' => $results,
                'numResults' => $numResults,
                'tags' => Tag::all(),
            ]);
        }
    }
}
