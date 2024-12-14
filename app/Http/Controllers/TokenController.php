<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Token::class);

        $request->validate([
            'duration' => 'required|string|in:day,week,month,year',
        ]);

        $token = auth()->user()->token();
        if ($token) {
            $token->delete();
        }

        $token = new Token;

        $token->user_id = auth()->id();
        $token->value = uuid_create();
        $token->creation_timestamp = now();
        $token->validity_timestamp = match ($request->input('duration')) {
            'day' => now()->addDay(),
            'week' => now()->addWeek(),
            'month' => now()->addMonth(),
            'year' => now()->addYear(),
        };

        $token->save();

        return redirect()->route('user.edit', ['id' => auth()->id()])
            ->withSuccess('Token created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $token = Token::findOrFail($id);

        $this->authorize('forceDelete', $token);

        $token->delete();

        return redirect()->route('user.edit', ['id' => auth()->id()])
            ->withSuccess('Token deleted successfully.');
    }
}
