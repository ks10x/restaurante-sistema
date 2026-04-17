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

        if (!$user) {
            return $next($request);
        }

        // Só exige 2FA se o PRÓPRIO usuário ATIVOU voluntariamente
        $has2fa = !empty($user->two_factor_secret) && !empty($user->two_factor_confirmed_at);

        if ($has2fa && !$request->session()->has('2fa_verified')) {
            // Permitir acesso às rotas de 2FA e logout sem loop
            if (
                !$request->routeIs('2fa.*') &&
                !$request->routeIs('logout')
            ) {
                return redirect()->route('2fa.index');
            }
        }

        return $next($request);
    }
}
