<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pedido, User, Prato, Insumo};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();
        $hasPedidoItensTable = Schema::hasTable('pedido_itens');

        $kpis = [
            'faturamento_hoje' => Pedido::whereDate('created_at',$hoje)->where('pagamento_status','aprovado')->sum('total'),
            'pedidos_hoje'     => Pedido::whereDate('created_at',$hoje)->count(),
            'ticket_medio'     => Pedido::whereDate('created_at',$hoje)->where('pagamento_status','aprovado')->avg('total') ?? 0,
            'pedidos_ativos'   => Pedido::whereIn('status',['confirmado','em_producao','saindo_entrega'])->count(),
            'clientes_hoje'    => Pedido::whereDate('created_at',$hoje)->distinct('user_id')->count('user_id'),
            'cancelados_hoje'  => Pedido::whereDate('created_at',$hoje)->where('status','cancelado')->count(),
            'estoque_critico'  => Insumo::whereColumn('quantidade_atual','<=','quantidade_minima')->where('ativo',1)->count(),
            'custo_total_estoque' => (float) Insumo::where('ativo', 1)->selectRaw('COALESCE(SUM(quantidade_atual * preco_unitario), 0) as total')->value('total'),
            'pratos_comprometidos' => Prato::query()->whereNull('deleted_at')->comprometidos()->count(),
        ];

        $vendas7dias = DB::table('pedidos')
            ->select(DB::raw('DATE(created_at) as data'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as pedidos'))
            ->where('pagamento_status','aprovado')
            ->whereBetween('created_at',[now()->subDays(6)->startOfDay(),now()->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))->orderBy('data')->get();

        $topPratos = collect();
        $ingredientesMaisUsados = collect();
        $pedidosRecentesQuery = Pedido::with(['usuario'])->latest()->limit(8);

        if ($hasPedidoItensTable) {
            $topPratos = DB::table('pedido_itens as pi')
                ->join('pratos as p','pi.prato_id','=','p.id')
                ->join('pedidos as pd','pi.pedido_id','=','pd.id')
                ->select('p.nome', DB::raw('SUM(pi.quantidade) as total'), DB::raw('SUM(pi.subtotal) as receita'))
                ->where('pd.status','entregue')
                ->whereDate('pd.created_at','>=',now()->subDays(30))
                ->groupBy('p.id','p.nome')->orderByDesc('total')->limit(5)->get();

            $ingredientesMaisUsados = DB::table('pedido_itens as pi')
                ->join('pedidos as pd', 'pi.pedido_id', '=', 'pd.id')
                ->join('prato_insumos as piv', 'pi.prato_id', '=', 'piv.prato_id')
                ->join('insumos as i', 'piv.insumo_id', '=', 'i.id')
                ->select(
                    'i.nome',
                    'i.unidade',
                    DB::raw('SUM(pi.quantidade * piv.quantidade) as consumo_estimado'),
                    DB::raw('SUM(pi.quantidade * piv.quantidade * i.preco_unitario) as custo_estimado')
                )
                ->where('pd.pagamento_status', 'aprovado')
                ->whereDate('pd.created_at', '>=', now()->subDays(30))
                ->groupBy('i.id', 'i.nome', 'i.unidade')
                ->orderByDesc('consumo_estimado')
                ->limit(5)
                ->get();

            $pedidosRecentesQuery->with('itens');
        }

        $pedidosRecentes = $pedidosRecentesQuery->get();

        $pratosComprometidos = Prato::query()
            ->with(['categoria:id,nome', 'insumos' => function ($query) {
                $query->whereColumn('insumos.quantidade_atual', '<=', 'insumos.quantidade_minima');
            }])
            ->whereNull('deleted_at')
            ->comprometidos()
            ->limit(6)
            ->get();

        $clientesRecentes = User::query()
            ->where(function ($q) {
                $q->where('role', User::ROLE_CLIENTE)->orWhere('role', 'cliente');
            })
            ->orderByDesc('id')
            ->limit(6)
            ->get(['id', 'name', 'last_name', 'email', 'phone', 'status', 'created_at']);

        $clientesBloqueados = User::query()
            ->where(function ($q) {
                $q->where('role', User::ROLE_CLIENTE)->orWhere('role', 'cliente');
            })
            ->where('status', '!=', 'ativo')
            ->count();

        return view('admin.dashboard', compact(
            'kpis',
            'vendas7dias',
            'topPratos',
            'pedidosRecentes',
            'ingredientesMaisUsados',
            'pratosComprometidos',
            'hasPedidoItensTable',
            'clientesRecentes',
            'clientesBloqueados'
        ));
    }
}
