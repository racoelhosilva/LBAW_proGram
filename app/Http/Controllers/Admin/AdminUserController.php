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
    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
    public function search(Request $request): View|Factory
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

    /*
    |--------------------------------------------------------------------------
    | Ban
    |--------------------------------------------------------------------------
    */
    public function listBans(): View|Factory
    {
        $bans = Ban::with('administrator')->with('user')->simplePaginate(20);

        return view('admin.user.bans', ['bans' => $bans]);
    }

    public function banUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
        ]);

        $duration = "{$validated['duration']} days";

        $ban = Ban::create([
            'user_id' => $user->id,
            'administrator_id' => Auth::guard('admin')->id(),
            'reason' => $validated['reason'],
            'duration' => DB::raw("INTERVAL '$duration'"),
        ]);

        return redirect()->route('admin.ban.index')->with('success', 'User banned successfully');

    }

    public function revokeBan(int $id): RedirectResponse
    {
        $ban = Ban::findOrFail($id);
        $ban->is_active = false;

        $ban->save();

        return redirect()->route('admin.ban.index')->with('success', 'Ban revoked successfully.');
    }
}
