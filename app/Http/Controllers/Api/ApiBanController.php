<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiBanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bans = Ban::all();

        return response()->json($bans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'administrator_id' => 'required|exists:administrator,id',
            'reason' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
        ]);
        $duration = "{$validated['duration']} days";

        $ban = Ban::create([
            'user_id' => $validated['user_id'],
            'administrator_id' => $validated['administrator_id'],
            'reason' => $validated['reason'],
            'duration' => DB::raw("INTERVAL '$duration'"),
        ]);

        return response()->json($ban, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Ban $ban)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ban $ban)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ban $ban)
    {
        //
    }
}
