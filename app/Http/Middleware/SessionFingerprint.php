<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionFingerprint
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $fingerprint = md5($request->ip() . $request->userAgent());
            
            if (!$request->session()->has('fingerprint')) {
                $request->session()->put('fingerprint', $fingerprint);
            } elseif ($request->session()->get('fingerprint') !== $fingerprint) {
                // Sessão sequestrada ou mudança brusca de IP
                auth()->logout();
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
