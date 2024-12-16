<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\GroupJoinRequest;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiGroupController extends Controller
{
    public function join(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('join', $group);
        $user = Auth::user();
        if ($group->is_public) {
            $groupMember = new GroupMember;
            $groupMember->group_id = $group_id;
            $groupMember->user_id = $user->id;
            $groupMember->join_timestamp = now();
            $groupMember->save();
        } else {
            $groupJoinRequest = new GroupJoinRequest;
            $groupJoinRequest->status = 'pending';
            $groupJoinRequest->group_id = $group_id;
            $groupJoinRequest->requester_id = $user->id;
            $groupJoinRequest->save();
        }

        return response()->json(['message' => 'You have joined the group.']);
    }

    public function leave(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('leave', $group);
        $user = Auth::user();
        GroupMember::where('group_id', $group_id)->where('user_id', $user->id)->delete();

        return response()->json(['message' => 'You have left the group.']);
    }

    public function remove(Request $request, int $group_id, int $user_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('remove', $group);
        GroupMember::where('group_id', $group_id)->where('user_id', $user_id)->delete();

        return response()->json(['message' => 'User removed from group.']);
    }

    public function acceptRequest(Request $request, int $group_id, int $requester_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('manage', $group);
        $groupJoinRequest = GroupJoinRequest::where('group_id', $group_id)->where('requester_id', $requester_id)->where('status', 'pending')->firstOrFail();
        $groupJoinRequest->status = 'accepted';
        $groupJoinRequest->save();

        return response()->json(['message' => 'Request accepted.']);
    }

    public function rejectRequest(Request $request, int $group_id, int $requester_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('manage', $group);
        $groupJoinRequest = GroupJoinRequest::where('group_id', $group_id)->where('requester_id', $requester_id)->where('status', 'pending')->firstOrFail();
        $groupJoinRequest->status = 'rejected';
        $groupJoinRequest->save();

        return response()->json(['message' => 'Request rejected.']);
    }

    public function invite(Request $request, int $group_id, int $invitee_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('manage', $group);

        $invitation = GroupInvitation::create(
            [
                'group_id' => $group_id,
                'invitee_id' => $invitee_id,
            ],
            [
                'status' => 'pending',
                'creation_timestamp' => now(),
            ]
        );

        return response()->json(['message' => 'User invited to group.']);
    }

    public function uninvite(Request $request, int $group_id, int $invitee_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('manage', $group);

        GroupInvitation::where('group_id', $group_id)
            ->where('invitee_id', $invitee_id)
            ->where('status', 'pending')
            ->delete();

        return response()->json(['message' => 'User uninvited from group.']);
    }

    public function acceptInvite(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('isInvited', $group);

        $user = Auth::user();

        $invitations = GroupInvitation::where('group_id', $group_id)
            ->where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->get();

        if ($invitations->isEmpty()) {
            return response()->json(['message' => 'No pending invitation found.'], 404);
        }

        foreach ($invitations as $invitation) {
            $invitation->update(['status' => 'accepted']);
        }

        return response()->json(['message' => 'You have joined the group.']);
    }

    public function rejectInvite(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('isInvited', $group);

        $user = Auth::user();

        $invitations = GroupInvitation::where('group_id', $group_id)
            ->where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->get();

        if ($invitations->isEmpty()) {
            return response()->json(['message' => 'No pending invitation found.'], 404);
        }

        foreach ($invitations as $invitation) {
            $invitation->update(['status' => 'rejected']);
        }

        return response()->json(['message' => 'You have rejected the invitation.']);
    }

    public function addPostToGroup(Request $request, int $group_id, int $post_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('addPostToGroup', $group);
        $group->posts()->attach($post_id);

        return response()->json(['message' => 'Post added to group.']);
    }
}
