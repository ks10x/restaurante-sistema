<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $userRole = (string) auth()->user()->role;
        
        // As roles nas rotas serão passadas como '0', '1', '2'
        if (!in_array($userRole, $roles)) {
            abort(403, 'Acesso não autorizado ao painel solicitado.');
        }
        
        return $next($request);
    }
}
