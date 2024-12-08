<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\GroupMember;
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
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $this->authorize('viewAny', User::class);

        $users = User::query();

        if (! empty($request->input('query'))) {
            $pattern = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->input('query')).'%';
            $users = User::where('name', 'ILIKE', $pattern)
                ->orWhere('email', 'ILIKE', $pattern)
                ->orWhere('handle', 'ILIKE', $pattern);

            if (is_numeric($request->input('query'))) {
                $users = $users->orWhere('id', $request->input('query'));
            }
        }

        $users = $users->orderBy('id')->paginate(20);

        return view('admin.pages.user', ['users' => $users]);
    }

    public function banUser(Request $request, int $id): RedirectResponse
    {
        $this->authorize('create', Ban::class);

        $user = User::findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:255',
            'duration' => 'integer|min:1',
        ]);

        $duration = $request->filled('permanent')
            ? '0 days'
            : $request->input('duration').' days';

        $ban = new Ban;

        $ban->user_id = $user->id;
        $ban->administrator_id = Auth::guard('admin')->id();
        $ban->reason = $request->input('reason');
        $ban->duration = DB::raw("INTERVAL '$duration'");

        $ban->save();

        return redirect()->route('admin.user.index')->withSuccess('User banned successfully');
    }

    public function deleteUser(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        DB::transaction(function () use ($id, $user) {
            // Delete notifications.
            $user->notifications()->delete();
            // Delete follow requests.
            $user->followRequests()->delete();
            // Delete group join requests.
            $user->groupJoinRequests()->delete();
            // Delete group invitations.
            $user->groupInvitations()->delete();
            // Delete group memberships.
            GroupMember::where('user_id', $id)
                ->delete();
            // Delete bans.
            $user->bans()->delete();
            // Delete user token.
            $user->tokens()->delete();
            // Delete user stats.
            $userStats = $user->stats;
            DB::table('user_stats_language')
                ->where('user_stats_id', $userStats->id)
                ->delete();
            DB::table('user_stats_technology')
                ->where('user_stats_id', $userStats->id)
                ->delete();
            DB::table('top_project')
                ->where('user_stats_id', $userStats->id)
                ->delete();
            $userStats->delete();

            // Delete user info.
            $user->update([
                'name' => $id,
                'email' => $id,
                'password' => $id,
                'handle' => $id,
                'is_public' => false,
                'description' => null,
                'profile_picture_url' => null,
                'banner_image_url' => null,
                'is_deleted' => true,
            ]);

        });

        return redirect()->route('admin.user.index')->withSuccess('User deleted successfully.');
    }
}
