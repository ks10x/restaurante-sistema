<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Require2FA
{
    public function handle(Request $request, Closure $next): Response
    {
        // 2FA desativado: não bloquear fluxo de login/navegação.
        return $next($request);

        $user = auth()->user();

        // 2FA obrigatório apenas para admins/cozinha
        if ($user && in_array((int) $user->role, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_COZINHA], true)) {
            $has2fa = (bool) ($user->two_factor_secret ?? null) && (bool) ($user->two_factor_confirmed_at ?? null);

            // Obriga a configurar 2FA
            if (!$has2fa) {
                if (!$request->routeIs('2fa.setup') && !$request->routeIs('2fa.confirm') && !$request->routeIs('logout')) {
                    return redirect()->route('2fa.setup');
                }
            }

            // Obriga a verificar 2FA a cada login
            if ($has2fa && !$request->session()->has('2fa_verified')) {
                if (
                    !$request->routeIs('2fa.index') &&
                    !$request->routeIs('2fa.verify') &&
                    !$request->routeIs('2fa.setup') &&
                    !$request->routeIs('2fa.confirm')
                ) {
                    return redirect()->route('2fa.index');
                }
            }
        }

        return $next($request);
    }
}
