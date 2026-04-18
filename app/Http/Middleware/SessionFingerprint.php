<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SessionFingerprint
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ignora verificação para rotas da Mesa Digital (Cliente)
        if ($request->is('m/*')) {
            return $next($request);
        }

        if (Auth::guard('web')->check()) {
            $fingerprint = md5($request->ip() . $request->userAgent());
            
            if (!$request->session()->has('fingerprint')) {
                $request->session()->put('fingerprint', $fingerprint);
            } elseif ($request->session()->get('fingerprint') !== $fingerprint) {
                // Sessão sequestrada ou mudança brusca de IP
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->withErrors([
                    'email' => 'Sessão invalidada por motivos de segurança (Mudança de IP/Aparelho).'
                ]);
            }
        }

        return $next($request);
    }
}
