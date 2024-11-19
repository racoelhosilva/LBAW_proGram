<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //
    public function list()
    {
        $groups = Group::all();

        return response()->json($groups);
    }

    public function show($id)
    {
        $group = Group::findOrFail($id);

        return response()->json($group);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_public' => 'nullable|boolean',
            'owner_id' => 'nullable|exists:users,id',
        ]);

        // Create group with the provided fields
        try {
            $group = Group::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'is_public' => $request->input('is_public', true), // Defaults to true if not provided
                'creation_timestamp' => now(), // Auto-set creation timestamp
                'owner_id' => $request->input('owner_id'),
            ]);

            return response()->json($group, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create group.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $group = Group::findOrFail($id);
            $group->delete();

            return response()->json($group);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete group.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
