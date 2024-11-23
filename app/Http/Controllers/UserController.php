<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        $authuser = Auth::user();

        $isOwnProfile = $authuser && $authuser->id === $user->id;

        return view('pages.user', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
        ]);
    }
}
