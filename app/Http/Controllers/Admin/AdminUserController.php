<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function search(Request $request): View|Factory
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);
        if (empty($validated['query'])) {
            $users = User::orderBy('id')->paginate(20);
        } elseif (is_numeric($validated['query'])) {
            $users = User::where('name', $validated['query'])
                ->orWhere('email', $validated['query'])
                ->orWhere('handle', $validated['query'])
                ->orWhere('id', $validated['query'])
                ->orderBy('id')
                ->paginate(20);
        } else {
            $users = User::where('name', $validated['query'])
                ->orWhere('email', $validated['query'])
                ->orWhere('handle', $validated['query'])
                ->orderBy('id')
                ->paginate(20);

        }

        return view('admin.users.index', ['users' => $users]);

    }
}
