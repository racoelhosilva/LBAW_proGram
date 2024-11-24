<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();

        $bannedCount = User::whereHas('bans', function ($query) {
            $query->active();
        })->count();

        return view('admin.dashboard', ['userCount' => User::count(), 'bannedCount' => $bannedCount]);
    }
}
