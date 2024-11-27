<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function show(): View
    {
        return view('pages.home', [
            'users' => $this->getTopUsers(),
            'posts' => $this->getHomepagePosts()->filter(function ($post) {
                return auth()->user()->can('view', $post);
            }),
            'tags' => Tag::withCount('posts')->orderBy('posts_count', 'DESC')->limit(10)->get(),
        ]);
    }

    private function getTopUsers()
    {
        if (auth()->check()) {
            $followedUserIds = auth()->user()->following->pluck('id');

            return User::whereNotIn('id', $followedUserIds)
                ->where('id', '!=', auth()->id())
                ->where('is_public', true)
                ->orderBy('num_followers', 'DESC')
                ->limit(5)
                ->get();
        }

        return User::orderBy('num_followers', 'DESC')
            ->where('id', '!=', auth()->id())
            ->where('is_public', true)
            ->limit(5)
            ->get();
    }

    private function getHomepagePosts()
    {
        // we used the formula: likes / (time_since_post + constant) ^ decay
        // as a decay formula to order posts on the homepage so that posts that are more recent
        // and have a higher number of likes are considered more popular and are shown first
        return Post::with(['author', 'tags'])
            ->when(auth()->check() && (count(auth()->user()->following) > 0), function ($query) {
                $followedUserIds = auth()->user()->following->pluck('id');

                $query->where('author_id', '!=', auth()->id())
                    ->orderByRaw('CASE WHEN author_id IN ('.$followedUserIds->join(',').') THEN 1 ELSE 2 END')
                    ->orderByRaw('(likes / POW((EXTRACT(EPOCH FROM (NOW() - creation_timestamp)) / 3600) + 2, 1.5)) DESC');
            }, function ($query) {
                $query->orderByRaw('(likes / POW((EXTRACT(EPOCH FROM (NOW() - creation_timestamp)) / 3600) + 2, 1.5)) DESC');
            })
            ->get();
    }
}
