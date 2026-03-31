<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantIsolation
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se houver multi-tenancy configurado via Auth
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->tenant_id) {
                // Definir globalmente o tenant ativo de escopo da aplicação
                // (por exemplo, aplicando binds de repositório, app->instance ou Global Scopes)
                // Isso protege que Admin visualize pedidos de outros
                // Isso pode ser configurado em models via trait, mas setamos a variável global aqui
                app()->instance('tenant.id', $user->tenant_id);
            }
        }
        
        return $next($request);
    }
}
