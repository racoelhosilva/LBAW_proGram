<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DenyBannedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isBanned()) {
            $ban = Auth::user()->lastActiveBan();

            return response()->view('errors.banned', [
                'reason' => $ban->reason,
                'expires' => ! $ban->isPermanent() ? $ban->end()->diffForHumans() : 'Indefinitely',
            ], 403);
        }

        return $next($request);
    }
}
