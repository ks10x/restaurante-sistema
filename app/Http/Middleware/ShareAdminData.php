<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ShareAdminData
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && in_array((int)auth()->user()->role, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_COZINHA])) {
            $pedidosAtivos = DB::table('pedidos')->whereIn('status', ['confirmado', 'em_producao'])->count();
            $estoqueCritico = DB::table('insumos')->whereColumn('quantidade_atual', '<=', 'quantidade_minima')->where('ativo', 1)->count();
            
            View::share('pedidosAtivos', $pedidosAtivos);
            View::share('estoqueCritico', $estoqueCritico);
            View::share('estoqueAlertas', DB::table('insumos')->whereColumn('quantidade_atual', '<=', 'quantidade_minima')->limit(5)->get());
        }
        return $next($request);
    }
}
