<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $authuser = Auth::user();

        $isOwnProfile = $authuser && $authuser->id === $user->id;

        return view('pages.edit-user', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate incoming request data
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'string|max:500',
            'is_public' => 'boolean',
            'handle' => 'string|unique:users,handle,'.$user->id,
            'languages' => 'array',
        ]);

        $newLanguages = [];

        if ($request->has('languages')) {
            foreach ($request->input('languages') as $language) {
                if (! $user->stats->languages->contains('name', $language)) {
                    $newLanguages[] = $language;
                }
            }
        }

        try {
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->is_public = $request->input('is_public', true);
            $user->handle = $request->input('handle');

            foreach ($newLanguages as $language) {
                $user->stats->languages()->create(['name' => $language]);
            }

            $user->save();

            return redirect()->route('users.show', $user->id);
        } catch (\Exception $e) {
            return redirect()->route('error.page')->with('error', 'Failed to update user. Please try again.');
        }
    }
}
