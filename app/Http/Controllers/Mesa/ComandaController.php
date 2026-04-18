<?php

namespace App\Http\Controllers\Mesa;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
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

        return redirect()->route('cardapio.index')->with('success', 'Bem-vindo! Você está conectado na Mesa ' . $mesa->numero . '. Faça seu pedido livremente!');
    }

    /**
     * Área onde o cliente vê o que já pediu na mesa e solicita o fechamento
     */
    public function verComanda()
    {
        $mesaId = session('mesa_ativa_id');

        if (!$mesaId) {
            return redirect()->route('cardapio.index')->with('error', 'Nenhuma mesa associada a esta sessão. Por favor, escaneie o QR Code novamente.');
        }

        $mesa = Mesa::with(['pedidos' => function ($query) {
            // Conta os pedidos que não foram cancelados
            $query->where('status', '!=', 'cancelado')->with('itens.prato');
        }])->findOrFail($mesaId);

        // Se o status for "aguardando_pagamento", a interface deve avisar que o garçom está a caminho
        return view('mesa.comanda', compact('mesa'));
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
            
            $mesa->update(['status' => 'ocupada']);

            return response()->json([
                'success' => true, 
                'redirect' => route('mesa.comanda'),
                'message' => 'Pedido enviado para a cozinha com sucesso!'
            ]);
        });
    }
}
