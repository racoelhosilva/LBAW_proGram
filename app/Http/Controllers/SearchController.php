<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function searchUsers(?string $queryStr, ?string $orderBy, bool $includeTotal = false)
    {
        $users = User::where('is_public', true)
            ->when($queryStr, function ($query, $queryStr) {
                $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$queryStr])
                    ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$queryStr]);
            });

        return $includeTotal ? [$users->simplePaginate(10), $users->count()] : $users->simplePaginate(10);
    }

    public function searchPosts(?string $queryStr, ?array $tags, ?string $orderBy, bool $includeTotal = false)
    {
        $posts = Post::visibleTo(Auth::user())
            ->when($tags, function ($query, $tags) {
                $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags);
                });
            })
            ->when($queryStr, function ($query, $queryStr) {
                $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$queryStr]);
            });

        return $this->orderPosts($posts, $queryStr, $orderBy, $includeTotal);
    }

    public function searchPostsByAuthor(?string $queryStr, ?array $tags, ?string $orderBy, bool $includeTotal = false)
    {
        $users = User::when($queryStr, function ($query, $queryStr) {
            $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$queryStr]);
        });

        $posts = Post::visibleTo(Auth::user())
            ->when($tags, function ($query, $tags) {
                $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags);
                });
            })
            ->joinSub($users, 'users', function (JoinClause $join) {
                $join->on('post.author_id', '=', 'users.id');
            });

        return $this->orderPosts($posts, $queryStr, $orderBy, $includeTotal);
    }

    public function searchPostsByGroup(?string $queryStr, ?array $tags, ?string $orderBy, bool $includeTotal = false)
    {
        $groups = Group::where('is_public', true)
            ->when($queryStr, function ($query, $queryStr) {
                $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$queryStr]);
            });

        $posts = Post::visibleTo(Auth::user())
            ->when($tags, function ($query, $tags) {
                $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags);
                });
            })
            ->joinSub($groups, 'groups', function (JoinClause $join) {
                // TODO: Refactor this
                $join->whereExists(function ($query) {
                    $query->select('group_post.post_id')
                        ->from('group_post')
                        ->whereColumn('group_post.post_id', 'post.id')
                        ->whereColumn('group_post.group_id', 'groups.id');
                });
            });

        return $this->orderPosts($posts, $queryStr, $orderBy, $includeTotal);
    }

    public function searchGroup(?string $queryStr, ?string $orderBy, bool $includeTotal = false)
    {
        $groups = Group::where('is_public', true)
            ->when($queryStr, function ($query, $queryStr) {
                $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$queryStr])
                    ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$queryStr]);
            });

        return $includeTotal ? [$groups->simplePaginate(10), $groups->count()] : $groups->simplePaginate(10);
    }

    public function orderPosts(Builder $posts, ?string $queryStr, ?string $orderBy, bool $includeTotal)
    {
        switch ($orderBy) {
            case 'newest':
                $posts = $posts->orderByDesc('creation_timestamp');
            case 'oldest':
                $posts = $posts->orderBy('creation_timestamp');
            case 'likes':
                $posts = $posts->orderByDesc('likes');
            case 'comments':
                $posts = $posts->orderByDesc('comments');
            case 'title':
                $posts = $posts->orderBy('title');
            case 'relevance':
            default:
                $posts = $posts->when($queryStr, function ($query) use ($queryStr) {
                    $query->orderByRaw("ts_rank(post.tsvectors, plainto_tsquery('english', ?)) DESC", [$queryStr]);
                });
        }

        return $includeTotal ? [$posts->simplePaginate(10), $posts->count()] : $posts->simplePaginate(10);
    }

    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'search_type' => 'nullable|string',
            'search_attr' => 'nullable|string',
            'order_by' => 'nullable|string',
        ]);

        if ($request->ajax()) {
            switch ($request->input('search_type')) {
                case 'posts':
                default:
                    $this->authorize('viewAny', Post::class);
                    switch ($request->input('search_attr')) {
                        case 'author':
                            $results = $this->searchPostsByAuthor($request->input('query'),
                                $request->input('tags'), $request->input('order_by'));
                            break;
                        case 'group':
                            $results = $this->searchPostsByGroup($request->input('query'),
                                $request->input('tags'), $request->input('order_by'));
                            break;
                        default:
                            $results = $this->searchPosts($request->input('query'),
                                $request->input('tags'), $request->input('order_by'));
                            break;
                    }

                    if ($request->ajax()) {
                        return view('partials.post-list', ['posts' => $results, 'showEmpty' => false]);
                    }
                    break;

                case 'users':
                    $this->authorize('viewAny', User::class);
                    $results = $this->searchUsers($request->input('query'));
                    if ($request->ajax()) {
                        return view('partials.user-list', ['users' => $results, 'showEmpty' => false]);
                    }
                    break;

                case 'groups':
                    $this->authorize('viewAny', Group::class);
                    $results = $this->searchGroup($request->input('query'), $request->input('tags'));
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
                            [$results, $numResults] = $this->searchPostsByAuthor($request->input('query'),
                                $request->input('tags'), $request->input('order_by'), true);
                            break;
                        case 'group':
                            [$results, $numResults] = $this->searchPostsByGroup($request->input('query'),
                                $request->input('tags'), $request->input('order_by'), true);
                            break;
                        default:
                            [$results, $numResults] = $this->searchPosts($request->input('query'),
                                $request->input('tags'), $request->input('order_by'), true);
                            break;
                    }
                    break;

                case 'users':
                    $this->authorize('viewAny', User::class);
                    [$results, $numResults] = $this->searchUsers($request->input('query'), $request->input('order_by'), true);
                    break;

                case 'groups':
                    $this->authorize('viewAny', Group::class);
                    [$results, $numResults] = $this->searchGroup($request->input('query'), $request->input('order_by'), true);
                    break;
            }

            return view('pages.search', [
                'results' => $results,
                'numResults' => $numResults,
                'tags' => Tag::all(),
            ]);
        }
    }
}
