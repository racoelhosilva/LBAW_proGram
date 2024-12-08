<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStats;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function sanitizeGoogleData($data)
    {
        $data['handle'] = preg_replace('/\s+/', '', $data['handle']);
        $data['handle'] = substr($data['handle'], 0, 20);

        $data['name'] = trim($data['name']);
        $data['name'] = substr($data['name'], 0, 250);

        return $data;
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {

        $google_user = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $google_user->getEmail())->first();

        // If there is already an existant user with the same email.
        // NOTE: possible security issue if a user has access to other user's google account.
        if ($user) {
            $user->google_id = $google_user->getId();
            $user->save();
            Auth::login($user);

            return redirect()->route('home');
        }

        $user = User::where('google_id', $google_user->getId())->first();

        // If the user does not exist, create one
        if (! $user) {

            $data = [
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'handle' => $google_user->getNickname() ?? $google_user->getName(),
                'is_public' => true,
                'google_id' => $google_user->getId(),
            ];

            $data = $this->sanitizeGoogleData($data);

            $new_user = new User;

            DB::transaction(function () use ($new_user, $data) {
                $new_user->name = $data['name'];
                $new_user->email = $data['email'];
                $new_user->handle = $data['handle'];
                $new_user->is_public = $data['is_public'];
                $new_user->google_id = $data['google_id'];

                $new_user->save();

                $new_userStats = new UserStats;
                $new_userStats->user_id = $new_user->id;
                $new_userStats->save();
            });

            Auth::login($new_user);

            // Otherwise, simply log in with the existing user
        } else {
            Auth::login($user);
        }

        // After login, redirect to homepage
        return redirect()->route('home');
    }
}
