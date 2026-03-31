<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Require2FA
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 2FA obrigatório apenas para admins/cozinha
        if ($user && in_array($user->role, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_COZINHA])) {
            if ($user->two_factor_secret && !$request->session()->has('2fa_verified')) {
                // Prevent redirection loop
                if (!$request->routeIs('2fa.index') && !$request->routeIs('2fa.verify')) {
                    return redirect()->route('2fa.index');
                }
            }
        }

        return $next($request);
    }
}
