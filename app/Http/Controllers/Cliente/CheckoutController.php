<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\{Pedido, Prato, Endereco, User};
use App\Services\PagarmeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $enderecos = $user->enderecos;
        $config = [
            'taxa_entrega'  => 5.00,
            'pedido_minimo' => (float) config_val('pedido_minimo', 30),
            'desconto_pix'  => 5, // 5% discount for PIX
        ];
        return view('cliente.checkout', compact('enderecos', 'config'));
    }

    /**
     * Store a new address inline from checkout
     */
    public function salvarEndereco(Request $request)
    {
        $request->validate([
            'cep'         => 'required|string|max:10',
            'logradouro'  => 'required|string|max:180',
            'numero'      => 'required|string|max:20',
            'complemento' => 'nullable|string|max:80',
            'bairro'      => 'required|string|max:80',
            'cidade'      => 'required|string|max:80',
            'estado'      => 'required|string|max:2',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $endereco = $user->enderecos()->create([
            'cep'         => $request->cep,
            'logradouro'  => $request->logradouro,
            'numero'      => $request->numero,
            'complemento' => $request->complemento,
            'bairro'      => $request->bairro,
            'cidade'      => $request->cidade,
            'uf'          => $request->estado,
            'principal'   => $user->enderecos()->count() === 0,
        ]);

        return response()->json([
            'success'  => true,
            'endereco' => $endereco,
        ]);
    }

    /**
     * Process checkout: create Pedido + call Pagar.me for PIX
     */
    public function processarPagamento(Request $request, PagarmeService $pagarme)
    {
        $request->validate([
            'itens'            => 'required|array|min:1',
            'itens.*.prato_id' => 'required|integer',
            'itens.*.qtd'      => 'required|integer|min:1',
            'itens.*.preco'    => 'required|numeric|min:0',
            'endereco_id'      => 'required|exists:enderecos,id',
            'tipo_entrega'     => 'required|in:entrega,retirada',
            'pagamento_metodo' => 'required|in:pix',
            'observacoes'      => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        return DB::transaction(function () use ($request, $user, $pagarme) {
            $itens = collect($request->itens);
            $subtotal = 0;

            // Validate items and compute subtotal from DB prices (security)
                $itensPronto = $itens->map(function ($item) use (&$subtotal) {
                $prato = Prato::findOrFail($item['prato_id']);
                abort_if(!$prato->disponivel || !$prato->ativo, 422, "Prato {$prato->nome} indisponível.");

                $preco = $prato->preco_ativo;
                $itemTotal = $preco * $item['qtd'];
                $subtotal += $itemTotal;

                return [
                    'prato_id'       => $prato->id,
                    'nome_prato'     => $prato->nome,
                    'preco_unitario' => $preco,
                    'quantidade'     => $item['qtd'],
                    'subtotal'       => $itemTotal,
                    'opcoes'         => $item['opcoes'] ?? null,
                    'observacao'     => $item['observacao'] ?? null,
                ];
            });

            $taxaEntrega = ($request->tipo_entrega === 'entrega')
                ? 5.00
                : 0;

            // PIX discount (5%)
            $descontoPix = round($subtotal * 0.05, 2);
            $total = max(0, $subtotal + $taxaEntrega - $descontoPix);

            $pedido = Pedido::create([
                'user_id'          => $user->id,
                'endereco_id'      => $request->endereco_id,
                'tipo_entrega'     => $request->tipo_entrega,
                'status'           => 'aguardando_pagamento',
                'subtotal'         => $subtotal,
                'taxa_entrega'     => $taxaEntrega,
                'desconto'         => $descontoPix,
                'total'            => $total,
                'pagamento_metodo' => 'pix',
                'pagamento_status' => 'pendente',
                'observacoes'      => $request->observacoes,
                'tempo_estimado'   => (int) config_val('tempo_estimado_entrega', 45),
            ]);

            $pedido->itens()->createMany($itensPronto->toArray());

            // Status history
            $pedido->historico()->create([
                'status'  => 'aguardando_pagamento',
                'user_id' => $user->id,
            ]);

            // Call Pagar.me to generate PIX
            $pedido->load('itens.prato');
            $pixData = $pagarme->criarPedidoPix($pedido, $user);

            return response()->json([
                'success'  => true,
                'codigo'   => $pedido->codigo,
                'redirect' => route('cliente.pedido.pagamento', $pedido->codigo),
                'pix'      => $pixData,
            ]);
        });
    }
}
