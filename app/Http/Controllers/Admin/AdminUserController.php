<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function index(Request $request): View|Factory
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);
        if (empty($validated['query'])) {
            $users = User::orderBy('id')->paginate(20);
        } elseif (is_numeric($validated['query'])) {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $users = User::where('id', $validated['query'])
                ->orderBy('id')
                ->paginate(20);
        } else {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $users = User::where('name', 'ILIKE', $query)
                ->orWhere('email', 'ILIKE', $query)
                ->orWhere('handle', 'ILIKE', $query)
                ->orderBy('id')
                ->paginate(20);
        }

        return view('admin.user.index', ['users' => $users]);
    }

    public function banUser(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'duration' => 'integer|min:1',
        ]);

        if ($request->filled('permanent')) {
            $duration = '0 days';
        } else {
            $duration = "{$validated['duration']} days";
        }

        Ban::create([
            'user_id' => $user->id,
            'administrator_id' => Auth::guard('admin')->id(),
            'reason' => $validated['reason'],
            'duration' => DB::raw("INTERVAL '$duration'"),
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User banned successfully');
    }
}
