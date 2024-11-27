<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminBanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $bans = Ban::query();

        if (! empty($request->input('query'))) {
            $pattern = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->input('query')).'%';

            $bans = $bans->where('reason', 'ILIKE', $pattern)
                ->orWhereHas('administrator', function ($query) use ($pattern) {
                    $query->where('name', 'ILIKE', $pattern)
                        ->orWhere('email', 'ILIKE', $pattern);
                })
                ->orWhereHas('user', function ($query) use ($pattern) {
                    $query->where('handle', 'ILIKE', $pattern)
                        ->orWhere('name', 'ILIKE', $pattern)
                        ->orWhere('email', 'ILIKE', $pattern);
                });

            if (is_numeric($request->input('query'))) {
                $bans = $bans->orWhere('id', $request->input('query'));
            }
        }

        $bans = $bans->orderBy('id')->paginate(20);

        return view('admin.user.bans', ['bans' => $bans]);
    }

    public function revoke(int $id): RedirectResponse
    {
        $ban = Ban::findOrFail($id);

        $ban->is_active = false;
        $ban->save();

        return redirect()->route('admin.ban.index')->withSuccess('Ban revoked successfully.');
    }
}
