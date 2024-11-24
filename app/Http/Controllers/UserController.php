<?php

namespace App\Http\Controllers;

use App\Models\Language;
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
            'languages' => Language::all(),
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
            'languages.*' => 'exists:language,id',

        ]);

        try {
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->is_public = $request->input('is_public', true);
            $user->handle = $request->input('handle');
            $user->stats->languages()->sync($request->input('languages'));

            $user->save();

            return redirect()->route('users.show', $user->id);
        } catch (\Exception $e) {
            return redirect()->route('error.page')->with('error', 'Failed to update user. Please try again.');
        }
    }
}
