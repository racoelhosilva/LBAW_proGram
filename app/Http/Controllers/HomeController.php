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
                    $query->orderByRaw('CASE WHEN author_id IN ('.$followedUserIds->join(',').') THEN 1 ELSE 2 END')->orderBy('likes', 'DESC');
                }, function ($query) {
                    $query->orderBy('likes', 'DESC');
                })
                ->get(),
            // with used to prevent the N+1 problem (see https://laravel-news.com/laravel-n1-query-problems for more details)
            'tags' => Tag::withCount('posts')->orderBy('posts_count', 'DESC')->limit(10)->get(),
        ]);
    }
}
