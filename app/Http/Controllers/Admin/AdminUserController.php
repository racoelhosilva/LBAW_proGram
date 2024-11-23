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
            $users = User::orderBy('id')->simplePaginate(20);
        } elseif (is_numeric($validated['query'])) {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $users = User::where('id', $validated['query'])
                ->orderBy('id')
                ->simplePaginate(20);
        } else {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $users = User::where('name', 'ILIKE', $query)
                ->orWhere('email', 'ILIKE', $query)
                ->orWhere('handle', 'ILIKE', $query)
                ->orderBy('id')
                ->simplePaginate(20);

        }

        return view('admin.user.index', ['users' => $users]);

    }
}
