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
            'users' => User::orderBy('num_followers', 'DESC')->limit(5)->get(),
            'posts' => Post::with('author')->orderBy('likes', 'DESC')->get(),   // with used to prevent the N+1 problem (see https://laravel-news.com/laravel-n1-query-problems for more details)
            'tags' => Tag::withCount('posts')->orderBy('posts_count', 'DESC')->limit(10)->get(),
        ]);
    }
}
