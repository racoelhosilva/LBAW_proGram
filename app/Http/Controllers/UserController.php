<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('pages.user', ['user' => $user]);
    }
}
