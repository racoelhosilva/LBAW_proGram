<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
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
        $this->authorize('acceptRequest', $group);
        $groupJoinRequest = GroupJoinRequest::where('group_id', $group_id)->where('requester_id', $requester_id)->where('status', 'pending')->firstOrFail();
        $groupJoinRequest->status = 'accepted';
        $groupJoinRequest->save();

        return response()->json(['message' => 'Request accepted.']);
    }

    public function rejectRequest(Request $request, int $group_id, int $requester_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('rejectRequest', $group);
        $groupJoinRequest = GroupJoinRequest::where('group_id', $group_id)->where('requester_id', $requester_id)->where('status', 'pending')->firstOrFail();
        $groupJoinRequest->status = 'rejected';
        $groupJoinRequest->save();

        return response()->json(['message' => 'Request rejected.']);
    }
}
