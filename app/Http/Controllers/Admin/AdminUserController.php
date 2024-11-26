<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\Post;
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
    public function searchUser(Request $request): View|Factory
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

    public function searchPost(Request $request): View|Factory
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);
        if (empty($validated['query'])) {
            $posts = Post::orderBy('id')->paginate(20);
        } elseif (is_numeric($validated['query'])) {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $posts = Post::where('id', $validated['query'])
                ->orderBy('id')
                ->paginate(20);
        } else {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $posts = Post::where('title', 'ILIKE', $query)
                ->orWhere('text', 'ILIKE', $query)
                ->orWhereHas('author', function ($q) use ($query) {
                    $q->where('handle', 'ILIKE', $query)
                        ->orWhere('name', 'ILIKE', $query)
                        ->orWhere('email', 'ILIKE', $query);
                })
                ->orderBy('id')
                ->paginate(20);
        }

        return view('admin.post.index', ['posts' => $posts]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ban
    |--------------------------------------------------------------------------
    */
    public function searchBans(Request $request): View|Factory
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        if (empty($validated['query'])) {
            $bans = Ban::orderBy('id')->paginate(20);
        } elseif (is_numeric($validated['query'])) {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $bans = Ban::where('id', $validated['query'])
                ->orderBy('id')
                ->paginate(20);
        } else {
            $query = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['query']).'%';
            $bans = Ban::whereHas('administrator', function ($q) use ($query) {
                $q->where('name', 'ILIKE', $query)
                    ->orWhere('email', 'ILIKE', $query);
            })
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('handle', 'ILIKE', $query)
                        ->orWhere('name', 'ILIKE', $query)
                        ->orWhere('email', 'ILIKE', $query);
                })
                ->orderBy('id')
                ->paginate(20);
        }

        return view('admin.user.bans', ['bans' => $bans]);
    }

    public function banUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'duration' => 'integer|min:1',
        ]);

        if ($request->filled('permanent')) {
            $duration = '0 days';
        } else {
            $duration = "{$validated['duration']} days";
        }

        $ban = Ban::create([
            'user_id' => $user->id,
            'administrator_id' => Auth::guard('admin')->id(),
            'reason' => $validated['reason'],
            'duration' => DB::raw("INTERVAL '$duration'"),
        ]);

        return redirect()->route('admin.user.search')->with('success', 'User banned successfully');

    }

    public function revokeBan(int $id): RedirectResponse
    {
        $ban = Ban::findOrFail($id);
        $ban->is_active = false;

        $ban->save();

        return redirect()->route('admin.ban.search')->with('success', 'Ban revoked successfully.');
    }
}
