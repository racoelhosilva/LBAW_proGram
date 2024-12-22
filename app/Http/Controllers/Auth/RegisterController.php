<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display a registration form.
     */
    public function show(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'handle' => 'required|alpha_dash:ascii|max:20|unique:users',
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            $user = new User;

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->handle = $request->input('handle');
            $user->is_public = true;

            $user->save();

            $userStats = new UserStats;
            $userStats->user_id = $user->id;
            $userStats->save();
        });

        $credentials = $request->only('email', 'password');
        auth()->attempt($credentials);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
