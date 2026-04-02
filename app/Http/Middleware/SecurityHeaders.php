<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(16));
        view()->share('csp_nonce', $nonce);

        $response = $next($request);

        if (method_exists($response, 'header')) {

            $csp = implode('; ', [
                "default-src 'self' *",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' *",
                "style-src 'self' 'unsafe-inline' *",
                "font-src 'self' *",
                "img-src 'self' data: blob: *",
                "connect-src 'self' *",
                "frame-ancestors 'none'",
            ]);
            $response->header('Content-Security-Policy', $csp);
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            $response->header('X-Frame-Options', 'DENY');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');
            
            // Remover headers que revelam tecnologias
            $response->headers->remove('X-Powered-By');
            $response->headers->remove('Server');
        }

        return $response;
    }
}
