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
use Illuminate\Support\Facades\Http;

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

    /*
    |--------------------------------------------------------------------------
    | Ban
    |--------------------------------------------------------------------------
    */
    public function listBans(): View|Factory
    {
        $bans = Ban::simplePaginate(20);

        return view('admin.user.bans', ['bans' => $bans]);
    }

    public function banUser(Request $request, User $user): RedirectResponse
    {

        $data = [
            'user_id' => $user->id,
            'administrator_id' => Auth::guard('admin')->id(),
            'reason' => $request['reason'],
            'duration' => $request['duration'],
        ];

        $response = Http::post(route('temp'), $data);

        if ($response->successful()) {
            return redirect()->route('admin.bans.index')->with('success', 'User banned successfully.');
        } else {
            return redirect()->route('admin.bans.index')->with('error', 'Failed to ban the user.');
        }

    }

    public function revokeBan($id)
    {
        $ban = Ban::findOrFail($id);
    }
}
