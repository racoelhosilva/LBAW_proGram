<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use App\Models\User;

class HomeController extends Controller
{
    public function show(): View
    {
        return view('pages.home', [
            'users' => User::all(),
        ]);
    }
}
