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

    public function revoke(int $id): RedirectResponse
    {
        $ban = Ban::findOrFail($id);
        $ban->is_active = false;

        $ban->save();

        return redirect()->route('admin.ban.index')->with('success', 'Ban revoked successfully.');
    }
}
