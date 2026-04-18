<?php

namespace App\Http\Controllers\Mesa;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\PedidoItem;
use Illuminate\Http\Request;



class ComandaController extends Controller
{
    /**
     * Rota de entrada via QR Code
     */
    public function abrirMesa($hash)
    {
        $mesa = Mesa::where('token_hash', $hash)->firstOrFail();

        // Se estiver livre, muda para ocupada. Se já estiver ocupada, apenas entra na mesa.
        if ($mesa->status === 'livre') {
            $mesa->update(['status' => 'ocupada']);
        }

        // Registra na sessão que a navegação atual é para esta mesa
        session(['mesa_ativa_id' => $mesa->id]);
        session(['mesa_numero' => $mesa->numero]);
        session(['mesa_sessao_inicio' => now()->toDateTimeString()]); // Marca o início desta visita específica
        session()->forget('meus_pedidos_ids'); // Limpa pedidos de visitas anteriores caso a sessão ainda exista


        return redirect()->route('mesa.cardapio', ['hash' => $hash])->with('success', 'Bem-vindo! Você está conectado na Mesa ' . $mesa->numero . '. Faça seu pedido livremente!');
    }

    /**
     * Exibe o cardápio exclusivo para a mesa (clone profissional)
     */
    public function cardapio($hash)
    {
        $mesa = \App\Models\Mesa::where('token_hash', $hash)->firstOrFail();
        
        // Garante que a sessão está correta
        if (session('mesa_ativa_id') != $mesa->id) {
            session(['mesa_ativa_id' => $mesa->id]);
            session(['mesa_numero' => $mesa->numero]);
        }

        $categorias = \App\Models\Categoria::where('ativa', 1)
            ->orderBy('ordem')
            ->with(['pratos' => function($q) {
                $q->where('ativo', 1)
                  ->where('disponivel', 1)
                  ->orderBy('ordem')
                  ->with(['ingredientes', 'insumos']);
            }])
            ->get();

        $restaurantConfig = \App\Models\RestaurantConfig::storefront();

        return view('mesa.cardapio', compact('mesa', 'categorias', 'restaurantConfig'));
    }


    /**
     * Área onde o cliente vê o que já pediu na mesa e solicita o fechamento
     */
    public function verComanda()
    {
        $mesaId = session('mesa_ativa_id');

        if (!$mesaId) {
            return redirect()->route('cardapio.index')->with('error', 'Nenhuma mesa associada a esta sessão.');
        }

        $meusPedidos = session('meus_pedidos_ids', []);
        $inicioSessao = session('mesa_sessao_inicio');

        $mesa = Mesa::with(['pedidos' => function ($query) use ($meusPedidos, $inicioSessao) {
            $query->where('status', '!=', 'cancelado')
                  ->whereNotIn('pagamento_status', ['aprovado', 'reembolsado']) // Crucial: Se já foi pago, desaparece da visão do cliente
                  ->orderBy('created_at', 'desc')
                  ->with('itens.prato');

            // Filtro rígido de Ética/Privacidade:
            $query->where(function($q) use ($meusPedidos, $inicioSessao) {
                if (!empty($meusPedidos)) {
                    $q->whereIn('id', $meusPedidos);
                }
                if ($inicioSessao) {
                    $q->orWhere('created_at', '>=', $inicioSessao);
                }
            });
        }])->findOrFail($mesaId);

        $restaurantConfig = \App\Models\RestaurantConfig::storefront();
        return view('mesa.comanda', compact('mesa', 'restaurantConfig'));
    }


    /**
     * API para o frontend verificar se a mesa foi liberada pelo garçom (Real-time)
     */
    public function statusCheck()
    {
        $mesaId = session('mesa_ativa_id');
        if (!$mesaId) return response()->json(['status' => 'deslogado']);
        
        $mesa = Mesa::find($mesaId);
        if (!$mesa) return response()->json(['status' => 'deslogado']);

        $res = [
            'status' => $mesa->status,
            'sessao_id' => session()->getId()
        ];

        // Se a mesa estiver livre mas o cliente ainda tiver IDs de pedidos na sessão, 
        // vamos verificar se esses pedidos foram cancelados ou pagos.
        $meusPedidosIds = session('meus_pedidos_ids', []);
        if ($mesa->status === 'livre' && !empty($meusPedidosIds)) {
            $canceladosCount = \App\Models\Pedido::whereIn('id', $meusPedidosIds)
                ->where('status', 'cancelado')
                ->count();
            
            if ($canceladosCount > 0) {
                $res['reason'] = 'reseted';
                $res['items'] = PedidoItem::whereIn('pedido_id', $meusPedidosIds)
                    ->join('pedidos', 'pedidos.id', '=', 'pedido_itens.pedido_id')
                    ->where('pedidos.status', 'cancelado')
                    ->pluck('nome_prato')
                    ->unique()
                    ->toArray();

            } else {
                $res['reason'] = 'paid';
            }
        }

        return response()->json($res);
    }






    /**
     * Cliente aperta botão "Pedir a Conta" -> Chama o garçom
     */
    public function chamarGarcom(Request $request)
    {
        $mesaId = session('mesa_ativa_id');
        if (!$mesaId) return redirect()->back();

        $mesa = Mesa::findOrFail($mesaId);
        
        if ($mesa->status !== 'aguardando_pagamento') {
            $mesa->update(['status' => 'aguardando_pagamento']);
        }

        return back()->with('success', 'O garçom foi chamado para trazer a sua conta. Aguarde um instante na mesa!');
    }

    /**
     * Processa o pedido rápido feito na mesa (recebe via API/AJAX do carrinho frontend)
     */
    public function enviarPedido(Request $request)
    {
        $mesaId = session('mesa_ativa_id');
        if (!$mesaId) {
            return response()->json(['success' => false, 'message' => 'Nenhuma mesa ativa no momento.'], 403);
        }

        $validated = $request->validate([
            'nome' => 'nullable|string|max:100',
            'itens' => 'required|array|min:1',
            'itens.*.prato_id' => 'required|integer',
            'itens.*.qtd' => 'required|integer|min:1',
            'observacoes' => 'nullable|string|max:500'
        ]);

        $mesa = Mesa::findOrFail($mesaId);
        
        // Se a mesa estiver livre, o cliente ao enviar um novo pedido a ocupa automaticamente novamente
        if ($mesa->status === 'livre') {
            $mesa->update(['status' => 'ocupada']);
        }


        
        return \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $mesa) {
            $subtotal = 0;
            $itensPronto = [];

            foreach ($validated['itens'] as $item) {
                $prato = \App\Models\Prato::findOrFail($item['prato_id']);
                if (!$prato->disponivel || !$prato->ativo) continue;

                $preco = (float) $prato->preco_ativo;
                $qtd = (int) $item['qtd'];
                $itemTotal = round($preco * $qtd, 2);
                $subtotal += $itemTotal;

                $itensPronto[] = [
                    'prato_id' => $prato->id,
                    'nome_prato' => $prato->nome,
                    'preco_unitario' => $preco,
                    'quantidade' => $qtd,
                    'subtotal' => $itemTotal,
                    'observacao' => $item['observacao'] ?? null,
                ];
            }

            if (empty($itensPronto)) {
                return response()->json(['success' => false, 'message' => 'Nenhum prato válido selecionado.']);
            }

            $pedido = \App\Models\Pedido::create([
                'mesa_id' => $mesa->id,
                'nome_cliente_avulso' => $validated['nome'] ?? 'Cliente (Mesa)',
                'tipo_consumo' => 'local',
                'status' => 'confirmado', // Na mesa já cai como confirmado pra cozinha
                'subtotal' => $subtotal,
                'total' => $subtotal, // sem taxas
                'pagamento_metodo' => 'dinheiro', // placeholder, vira "A COMBINAR"
                'pagamento_status' => 'pendente',
                'observacoes' => $validated['observacoes'] ?? null,
            ]);

            $pedido->itens()->createMany($itensPronto);
            $pedido->historico()->create(['status' => 'confirmado']);
            
            // Registra este pedido na sessão do cliente para que ele veja na comanda
            $meusPedidos = session('meus_pedidos_ids', []);
            $meusPedidos[] = $pedido->id;
            session(['meus_pedidos_ids' => $meusPedidos]);

            $mesa->update(['status' => 'ocupada']);

            return response()->json([
                'success' => true, 
                'redirect' => route('mesa.comanda', ['hash' => $mesa->token_hash]),
                'message' => 'Pedido enviado para a cozinha com sucesso!'
            ]);


        });
    }
}
