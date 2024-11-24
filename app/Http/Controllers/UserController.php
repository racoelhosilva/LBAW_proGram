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

        $request->validate([
            'name' => 'string|max:255',
            'description' => 'string|max:500',

        ]);

        try {
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->save();

            return redirect()->route('users.show', $user->id);
        } catch (\Exception $e) {
            return redirect()->route('error.page')->with('error', 'Failed to update user. Please try again.');
        }

    }
}
