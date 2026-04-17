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
        // Pega todas as mesas com seus pedidos não-pagos associados para determinar as cores
        $mesas = Mesa::with(['pedidos' => function ($q) {
            $q->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
              ->whereNotIn('status', ['entregue', 'cancelado', 'reembolsado']);
        }])->orderBy('numero')->get();

        return view('garcom.painel.index', compact('mesas'));
    }

    public function show(Mesa $mesa)
    {
        // Traz os pedidos pendentes
        $pedidos = $mesa->pedidos()
            ->with('itens.prato')
            ->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
            ->whereNotIn('status', ['cancelado', 'reembolsado'])
            ->get();

        $totalGeral = $pedidos->sum('total');

        return view('garcom.painel.show', compact('mesa', 'pedidos', 'totalGeral'));
    }

    public function fecharConta(Mesa $mesa)
    {
        DB::transaction(function () use ($mesa) {
            // Marca todos os pedidos da mesa como pagos e entregues/concluidos
            $pedidosPendentes = $mesa->pedidos()
                ->whereNotIn('pagamento_status', ['aprovado', 'reembolsado'])
                ->whereNotIn('status', ['cancelado', 'reembolsado'])
                ->get();

            foreach ($pedidosPendentes as $pedido) {
                $pedido->update([
                    'status' => 'entregue',
                    'pagamento_status' => 'aprovado',
                    'pagamento_metodo' => 'fisico_garcom', // Indicando que foi cobrado no local
                ]);
            }

            // Libera a mesa
            $mesa->update(['status' => 'livre']);
        });

        return redirect()->route('garcom.index')->with('success', 'Conta da Mesa ' . $mesa->numero . ' fechada com sucesso!');
    }
}
