<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupJoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiGroupController extends Controller
{
    public function join(Request $request, int $group_id)
    {
        $group = Group::findOrFail($group_id);
        $this->authorize('join', $group);
        $user = Auth::user();
        $groupJoinRequest = new GroupJoinRequest;
        $groupJoinRequest->status = 'pending';
        $groupJoinRequest->group_id = $group_id;
        $groupJoinRequest->requester_id = $user->id;
        $groupJoinRequest->save();

        return response()->json(['message' => 'You have joined the group.']);
    }
}
