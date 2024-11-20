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
            'users' => User::all(),
            'posts' => Post::all(),
            'tags' => Tag::all(),
        ]);
    }
}
