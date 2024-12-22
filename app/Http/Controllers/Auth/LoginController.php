<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class LoginController extends Controller
{
    /**
     * Display a login form.
     */
    public function show(): Redirector|RedirectResponse|View|Factory
    {
        if (auth()->check()) {
            return redirect()->route('home');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            if (auth()->user()->is_deleted) {
                auth()->logout();

                return back()->withErrors([
                    'email' => 'That account has been deleted.',
                ])->onlyInput('email');
            }

            return redirect()->intended()->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log out the user from application.
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->intended()->withSuccess('You have successfully logged out!');
    }
}
