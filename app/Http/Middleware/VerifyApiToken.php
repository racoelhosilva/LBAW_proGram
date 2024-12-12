<?php

namespace App\Http\Middleware;

use App\Models\Administrator;
use App\Models\Token;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->findApiToken($request);
        if (! isset($token)) {
            return $this->verifyCsrfToken($request, $next);
        }

        $account = $token->account;
        switch (get_class($account)) {
            case User::class:
                Auth::setUser($account);
                break;
            case Administrator::class:
                Auth::guard('admin')->setUser($account);
                break;
        }

        return $next($request);
    }

    public function findApiToken(Request $request): ?Token
    {
        $authToken = $request->header('Authorization');
        if (! isset($authToken) || ! preg_match('/Bearer ([A-Za-z0-9]+)/', $authToken, $matches)) {
            return null;
        }

        $tokenStr = $matches[1];
        $token = Token::find($tokenStr);
        if (! isset($token) || ! $token->isValid()) {
            return null;
        }

        return $token;
    }

    public function verifyCsrfToken(Request $request, Closure $next): Response
    {
        return csrf_token() === $request->session()->token()
            ? $next($request)
            : response()->json(['error' => 'CSRF Token Mismatch'], 419);
    }
}
