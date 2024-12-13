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

        $token = new Token;

        $token->account()->associate(auth()->user());
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
    public function destroy(Token $token)
    {
        $this->authorize('forceDelete', $token);

        $token->delete();

        return redirect()->route('user.edit', ['user' => auth()->user()])
            ->withSuccess('Token deleted successfully.');
    }
}
