<?php

namespace App\Http\Controllers\Garcom;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PainelController extends Controller
{
    public function index()
    {
        // View dashboard configuration
        $restaurantConfig = \App\Models\RestaurantConfig::storefront();
        
        // Pega todas as mesas com seus pedidos não-pagos associados para determinar as cores

        $mesas = Mesa::with(['pedidos' => function ($q) {
            $q->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
              ->whereNotIn('status', ['entregue', 'cancelado', 'reembolsado']);
        }])->orderBy('numero')->get();

        return view('garcom.painel.index', compact('mesas', 'restaurantConfig'));
    }

    public function pedidos()
    {
        $restaurantConfig = \App\Models\RestaurantConfig::storefront();
        $pedidos = Pedido::with(['itens.prato', 'mesa'])
            ->whereNotNull('mesa_id')
            ->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
            ->whereNotIn('status', ['entregue', 'cancelado', 'reembolsado'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('garcom.painel.pedidos', compact('pedidos', 'restaurantConfig'));
    }

    public function show(Mesa $mesa)
    {
        // Traz os pedidos pendentes
        $pedidos = $mesa->pedidos()
            ->with('itens.prato')
            ->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
            ->whereNotIn('status', ['cancelado', 'reembolsado'])
            ->get();

        $historicoHoje = $mesa->pedidos()
            ->with('itens.prato')
            ->where('pagamento_status', 'aprovado')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalGeral = $pedidos->sum('total');

        $restaurantConfig = \App\Models\RestaurantConfig::storefront();
        return view('garcom.painel.show', compact('mesa', 'pedidos', 'historicoHoje', 'totalGeral', 'restaurantConfig'));
    }

    public function fecharConta(Mesa $mesa)
    {
        DB::transaction(function () use ($mesa) {
            // Marca todos os pedidos da mesa como pagos e entregues/concluidos
            $pedidosPendentes = $mesa->pedidos()
                ->with(['itens.prato.insumos'])
                ->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
                ->whereNotIn('status', ['cancelado', 'reembolsado'])
                ->get();

            foreach ($pedidosPendentes as $pedido) {
                // Dar baixa automática no estoque para os insumos vinculados ao prato pedido
                foreach ($pedido->itens as $item) {
                    if ($item->prato) {
                        foreach ($item->prato->insumos as $insumo) {
                            $quantidadeDeduzir = $item->quantidade * $insumo->pivot->quantidade;
                            $insumo->movimentar(
                                'saida', 
                                $quantidadeDeduzir, 
                                auth()->id() ?? 1, 
                                ['observacao' => "Baixa autom. Mesa {$mesa->numero} - Pedido #{$pedido->codigo}"] // Observação da movimentação
                            );
                        }
                    }
                }

                $pedido->update([
                    'status' => 'entregue',
                    'pagamento_status' => 'aprovado',
                    'pagamento_metodo' => 'dinheiro', // Indicando que foi cobrado no local
                ]);
            }

            // Libera a mesa
            $mesa->update(['status' => 'livre']);
        });

        return redirect()->route('garcom.index')->with('success', 'Conta da Mesa ' . $mesa->numero . ' fechada com sucesso!');
    }

    public function limparMesa(Mesa $mesa)
    {
        DB::transaction(function () use ($mesa) {
            // Cancela todos os pedidos pendentes da mesa
            $mesa->pedidos()
                ->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
                ->whereNotIn('status', ['cancelado', 'reembolsado', 'entregue'])
                ->update([
                    'status' => 'cancelado',
                ]);

            // Libera a mesa
            $mesa->update(['status' => 'livre']);
        });

        return redirect()->route('garcom.index')->with('success', 'Mesa ' . $mesa->numero . ' limpa e pedidos cancelados!');
    }
}


