<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiSearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query') ?? '';

        $users = User::where('is_public', true)
            ->whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->get();

        return response()->json($users);
    }
}
