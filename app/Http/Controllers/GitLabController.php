<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStats;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class GitLabController extends Controller
{
    public function sanitizeGitLabData($data)
    {
        $data['handle'] = preg_replace('/[^a-zA-Z0-9_-]/', '', $data['handle']);
        $data['handle'] = substr($data['handle'], 0, 20);

        $data['name'] = trim($data['name']);
        $data['name'] = substr($data['name'], 0, 250);

        return $data;
    }

    public function redirect()
    {
        return Socialite::driver('gitlab')->redirect();
    }

    public function callbackGitLab()
    {

        $gitlab_user = Socialite::driver('gitlab')->stateless()->user();
        $user = User::where('email', $gitlab_user->getEmail())->first();

        if ($user) {
            $user->google_id = $gitlab_user->getId();
            $user->save();
            Auth::login($user);

            return redirect()->route('home');
        }

        $user = User::where('gitlab_id', $gitlab_user->getId())->first();

        // If the user does not exist, create one
        if (! $user) {

            $data = [
                'name' => $gitlab_user->getName(),
                'email' => $gitlab_user->getEmail(),
                'handle' => $gitlab_user->getNickname() ?? $gitlab_user->getName(),
                'is_public' => true,
                'gitlab_id' => $gitlab_user->getId(),
            ];

            $data = $this->sanitizeGitLabData($data);

            $new_user = new User;

            DB::transaction(function () use ($new_user, $data) {
                $new_user->name = $data['name'];
                $new_user->email = $data['email'];
                $new_user->handle = $data['handle'];
                $new_user->is_public = $data['is_public'];
                $new_user->gitlab_id = $data['gitlab_id'];

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
