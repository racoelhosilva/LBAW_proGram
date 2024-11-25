<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function show(): View
    {
        return view('pages.home', [
            'users' => User::orderBy('num_followers', 'DESC')->limit(5)->get(),
            'posts' => Post::with(['author', 'tags'])
                ->when(auth()->check(), function ($query) {
                    $followedUserIds = Follow::where('follower_id', auth()->id())->pluck('followed_id');

                    $query->where('author_id', '!=', auth()->id())
                        ->orderByRaw('CASE WHEN author_id IN ('.$followedUserIds->join(',').') THEN 1 ELSE 2 END')
                        ->orderByRaw('(likes / POW((EXTRACT(EPOCH FROM (NOW() - creation_timestamp)) / 3600) + 2, 1.5)) DESC');
                    // we used the formula: likes / (time_since_post + constant) ^ decay
                    // as a decay formula to order posts on the homepage so that posts that are more recent
                    // and have a higher number of likes are considered more popular and are shown first
                }, function ($query) {
                    $query->orderByRaw('(likes / POW((EXTRACT(EPOCH FROM (NOW() - creation_timestamp)) / 3600) + 2, 1.5)) DESC');
                })
                ->get(),
            // with used to prevent the N+1 problem (see https://laravel-news.com/laravel-n1-query-problems for more details)
            'tags' => Tag::withCount('posts')->orderBy('posts_count', 'DESC')->limit(10)->get(),
        ]);
    }
}
