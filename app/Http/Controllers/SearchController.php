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
        $users = User::when($queryStr, function ($query, $queryStr) {
            $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$queryStr]);
        });

        return $this->orderUsers($users, $queryStr, $orderBy, $includeTotal);
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
        $groups = Group::when($queryStr, function ($query, $queryStr) {
            $query->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$queryStr])
                ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$queryStr]);
        });

        return $this->orderGroups($groups, $queryStr, $orderBy, $includeTotal);
    }

    public function orderUsers(Builder $users, ?string $queryStr, ?string $orderBy, bool $includeTotal)
    {
        switch ($orderBy) {
            case 'name':
                $users = $users->orderBy('users.name');
                break;
            case 'followers':
                $users = $users->orderByDesc('users.num_followers');
                break;
            case 'relevance':
            default:
                $users = $users->when($queryStr, function ($query) use ($queryStr) {
                    $query->orderByRaw("ts_rank(users.tsvectors, plainto_tsquery('english', ?)) DESC", [$queryStr]);
                });
                break;
        }

        return $includeTotal ? [$users->simplePaginate(25), $users->count()] : $users->simplePaginate(25);
    }

    public function orderPosts(Builder $posts, ?string $queryStr, ?string $orderBy, bool $includeTotal)
    {
        switch ($orderBy) {
            case 'newest':
                $posts = $posts->orderByDesc('post.creation_timestamp');
                break;
            case 'oldest':
                $posts = $posts->orderBy('post.creation_timestamp');
                break;
            case 'likes':
                $posts = $posts->orderByDesc('post.likes');
                break;
            case 'comments':
                $posts = $posts->orderByDesc('post.comments');
                break;
            case 'title':
                $posts = $posts->orderBy('post.title');
                break;
            case 'relevance':
            default:
                $posts = $posts->when($queryStr, function ($query) use ($queryStr) {
                    $query->orderByRaw("ts_rank(post.tsvectors, plainto_tsquery('english', ?)) DESC", [$queryStr]);
                });
                break;
        }

        return $includeTotal ? [$posts->simplePaginate(15), $posts->count()] : $posts->simplePaginate(15);
    }

    public function orderGroups(Builder $posts, ?string $queryStr, ?string $orderBy, bool $includeTotal)
    {
        switch ($orderBy) {
            case 'name':
                $posts = $posts->orderBy('groups.name');
                break;
            case 'members':
                $posts = $posts->orderByDesc('groups.member_count');
                break;
            case 'relevance':
            default:
                $posts = $posts->when($queryStr, function ($query) use ($queryStr) {
                    $query->orderByRaw("ts_rank(groups.tsvectors, plainto_tsquery('english', ?)) DESC", [$queryStr]);
                });
                break;
        }

        return $includeTotal ? [$posts->simplePaginate(15), $posts->count()] : $posts->simplePaginate(15);
    }

    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tag,id',
            'search_type' => 'nullable|string|in:posts,users,groups',
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
                    $results = $this->searchUsers($request->input('query'), $request->input('order_by'));
                    if ($request->ajax()) {
                        return view('partials.user-list', ['users' => $results, 'showEmpty' => false]);
                    }
                    break;

                case 'groups':
                    $this->authorize('viewAny', Group::class);
                    $results = $this->searchGroup($request->input('query'), $request->input('order_by'));
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
