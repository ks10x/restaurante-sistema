<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class TenantThrottle
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = auth()->check() ? auth()->user()->tenant_id : 'guest';
        $key = 'tenant:' . $tenantId . ':' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 300)) { // 300 requisições por minuto por tenant/ip
            abort(429, 'Muitas requisições. O sistema de proteção do tenant limitou seu acesso.');
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
