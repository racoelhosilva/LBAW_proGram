<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Technology;
use App\Models\User;

class DashboardController extends Controller
{
    public function show()
    {
        $bannedCount = User::whereHas('bans', function ($query) {
            $query->active();
        })->count();

        return view('admin.pages.dashboard', [
            'userCount' => User::count(),
            'bannedCount' => $bannedCount,
            'postCount' => Post::count(),
            'tagCount' => Tag::count(),
            'languageCount' => Language::count(),
            'technologyCount' => Technology::count(),
        ]);
    }
}
