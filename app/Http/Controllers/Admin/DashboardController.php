<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pedido, User, Prato, Insumo};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();

        $kpis = [
            'faturamento_hoje' => Pedido::whereDate('created_at',$hoje)->where('pagamento_status','aprovado')->sum('total'),
            'pedidos_hoje'     => Pedido::whereDate('created_at',$hoje)->count(),
            'ticket_medio'     => Pedido::whereDate('created_at',$hoje)->where('pagamento_status','aprovado')->avg('total') ?? 0,
            'pedidos_ativos'   => Pedido::whereIn('status',['confirmado','em_producao','saindo_entrega'])->count(),
            'clientes_hoje'    => Pedido::whereDate('created_at',$hoje)->distinct('user_id')->count('user_id'),
            'cancelados_hoje'  => Pedido::whereDate('created_at',$hoje)->where('status','cancelado')->count(),
            'estoque_critico'  => Insumo::whereColumn('quantidade_atual','<=','quantidade_minima')->where('ativo',1)->count(),
        ];

        $vendas7dias = DB::table('pedidos')
            ->select(DB::raw('DATE(created_at) as data'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as pedidos'))
            ->where('pagamento_status','aprovado')
            ->whereBetween('created_at',[now()->subDays(6)->startOfDay(),now()->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))->orderBy('data')->get();

        $topPratos = DB::table('pedido_itens as pi')
            ->join('pratos as p','pi.prato_id','=','p.id')
            ->join('pedidos as pd','pi.pedido_id','=','pd.id')
            ->select('p.nome', DB::raw('SUM(pi.quantidade) as total'), DB::raw('SUM(pi.subtotal) as receita'))
            ->where('pd.status','entregue')
            ->whereDate('pd.created_at','>=',now()->subDays(30))
            ->groupBy('p.id','p.nome')->orderByDesc('total')->limit(5)->get();

        $pedidosRecentes = Pedido::with(['usuario','itens'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('kpis','vendas7dias','topPratos','pedidosRecentes'));
    }
}