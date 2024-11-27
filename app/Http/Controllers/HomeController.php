<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function show(): View
    {
        $this->authorize('viewAny', Post::class);
        $this->authorize('viewAny', User::class);

        return view('pages.home', [
            'users' => $this->getTopUsers(),
            'posts' => $this->getHomepagePosts()->filter(function ($post) {
                return Auth::check() ? Auth::user()->can('view', $post) : $post->is_public;
            }),
            'tags' => $this->getTrendingTags(),
        ]);
    }

    private function getTopUsers()
    {
        $users = User::where('id', '<>', Auth::id())
            ->where('is_public', true);

        if (Auth::check()) {
            $followedUserIds = Auth::user()->following->pluck('id');

            $users = $users->whereNotIn('id', $followedUserIds);
        }

        $users = $users->orderBy('num_followers', 'DESC')
            ->limit(5)
            ->get();

        return $users;
    }

    private function getHomepagePosts()
    {
        // We used the formula: likes / (time_since_post + constant) ^ decay
        // as a decay formula to order posts on the homepage so that posts that are more recent
        // and have a higher number of likes are considered more popular and are shown first
        return Post::with(['author', 'tags'])
            ->when(Auth::check(), function ($query) {
                $query->where('author_id', '<>', Auth::id());
            })
            ->when(Auth::check() && Auth::user()->following->isNotEmpty(), function ($query) {
                $followedUserIds = Auth::user()->following->pluck('id');

                $query->orderByRaw('CASE WHEN author_id IN ('.implode(',', array_fill(0, count($followedUserIds), '?')).') THEN 1 ELSE 2 END', $followedUserIds);
            })
            ->orderByRaw('(likes / POW((EXTRACT(EPOCH FROM (NOW() - creation_timestamp)) / 3600) + 2, 1.5)) DESC')
            ->get();
    }

    private function getTrendingTags()
    {
        return Tag::withCount('posts')
            ->orderBy('posts_count', 'DESC')
            ->limit(10)
            ->get();
    }
}
