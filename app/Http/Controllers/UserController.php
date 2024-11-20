<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStats;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function list()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'handle' => 'nullable|string|unique:users,handle|max:50',
            'is_public' => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        // Create user with the provided fields
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'handle' => $request->input('handle'),
                'is_public' => $request->input('is_public', true), // Defaults to true if not provided
                'description' => $request->input('description'),
                'register_timestamp' => now(), // Auto-set registration timestamp
            ]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create user.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete user.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listUserStats($id)
    {

        $userStats = UserStats::where('user_id', $id)
            ->with(['technologies', 'languages', 'projects'])
            ->first();

        if (! $userStats) {
            return response()->json(['message' => 'User stats not found'], 404);
        }

        return response()->json($userStats);
    }
}
