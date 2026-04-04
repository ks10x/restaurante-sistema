<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireVerifiedContacts
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $shouldRequire = in_array((int) ($user->role ?? -1), [
            \App\Models\User::ROLE_ADMIN,
            \App\Models\User::ROLE_COZINHA,
        ], true) || $request->routeIs('2fa.*');

        if (!$shouldRequire) {
            return $next($request);
        }

        // Avoid redirect loops.
        if ($request->routeIs('verification.*') || $request->routeIs('phone.verification.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
            // Admin não precisa de verificação de e-mail para acessar o painel.
            if ((int) ($user->role ?? -1) !== \App\Models\User::ROLE_ADMIN) {
                return redirect()->route('verification.notice');
            }
        }

        if (!$user->phone_verified_at) {
            return redirect()->route('phone.verification.notice');
        }

        return $next($request);
    }
}
