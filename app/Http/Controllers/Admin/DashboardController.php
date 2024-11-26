<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function show()
    {
        $bannedCount = User::whereHas('bans', function ($query) {
            $query->active();
        })->count();

        return view('admin.dashboard', [
            'userCount' => User::count(),
            'bannedCount' => $bannedCount,
            'postCount' => Post::count(),
        ]);
    }
}
