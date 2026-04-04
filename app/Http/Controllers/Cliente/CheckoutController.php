<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Prato;
use App\Services\PagarmeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $enderecos = $user->enderecos;
        $config = [
            'taxa_entrega'       => 5.00,
            'pedido_minimo'      => (float) config_val('pedido_minimo', 30),
            'desconto_pix'       => 5,
            'pagarme_public_key' => config('services.pagarme.public_key', ''),
        ];

        return view('cliente.checkout', compact('enderecos', 'config'));
    }

    public function salvarEndereco(Request $request)
    {
        try {
            $validated = $request->validate([
                'cep'         => 'required|string|max:10',
                'logradouro'  => 'required|string|max:180',
                'numero'      => 'required|string|max:20',
                'complemento' => 'nullable|string|max:80',
                'bairro'      => 'required|string|max:80',
                'cidade'      => 'required|string|max:80',
                'estado'      => 'required|string|size:2',
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            $endereco = $user->enderecos()->create([
                'apelido'     => 'Casa',
                'cep'         => $validated['cep'],
                'logradouro'  => $validated['logradouro'],
                'numero'      => $validated['numero'],
                'complemento' => $validated['complemento'] ?? null,
                'bairro'      => $validated['bairro'],
                'cidade'      => $validated['cidade'],
                'estado'      => strtoupper($validated['estado']),
                'principal'   => $user->enderecos()->count() === 0,
            ]);

            return response()->json([
                'success'  => true,
                'endereco' => $endereco->fresh(),
                'message'  => 'Endereco salvo com sucesso.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?: 'Dados invalidos para o endereco.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Falha ao salvar endereco no checkout', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Nao foi possivel salvar o endereco agora. Tente novamente em instantes.',
            ], 500);
        }
    }

    public function processarPagamento(Request $request, PagarmeService $pagarme)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $validated = $request->validate([
                'itens'            => ['required', 'array', 'min:1'],
                'itens.*.prato_id' => ['required', 'integer'],
                'itens.*.qtd'      => ['required', 'integer', 'min:1'],
                'itens.*.preco'    => ['required', 'numeric', 'min:0'],
                'tipo_entrega'     => ['required', 'in:entrega,retirada'],
                'endereco_id'      => [
                    'nullable',
                    Rule::requiredIf(fn () => $request->input('tipo_entrega') === 'entrega'),
                    Rule::exists('enderecos', 'id')->where(fn ($q) => $q->where('user_id', $user->id)->whereNull('deleted_at')),
                ],
                'pagamento_metodo' => ['required', 'in:pix,cartao_credito'],
                'observacoes'      => ['nullable', 'string', 'max:500'],
                'card_token'       => ['required_if:pagamento_metodo,cartao_credito', 'string'],
                'card_last_four'   => ['nullable', 'string', 'max:4'],
                'card_brand'       => ['nullable', 'string', 'max:40'],
                'parcelas'         => ['nullable', 'integer', 'min:1', 'max:12'],
            ]);

            return DB::transaction(function () use ($validated, $user, $pagarme) {
                $itens = collect($validated['itens']);
                $subtotal = 0.0;

                // Validate items and compute subtotal from DB prices (security)
                $itensPronto = $itens->map(function ($item) use (&$subtotal) {
                    $prato = Prato::findOrFail($item['prato_id']);
                    abort_if(! $prato->disponivel || ! $prato->ativo, 422, "Prato {$prato->nome} indisponível.");

                    $preco = (float) $prato->preco_ativo;
                    $quantidade = (int) $item['qtd'];
                    $itemTotal = round($preco * $quantidade, 2);
                    $subtotal += $itemTotal;

                    return [
                        'prato_id' => $prato->id,
                        'nome_prato' => $prato->nome,
                        'preco_unitario' => $preco,
                        'quantidade' => $quantidade,
                        'subtotal' => $itemTotal,
                        'opcoes' => $item['opcoes'] ?? null,
                        'observacao' => $item['observacao'] ?? null,
                    ];
                });

                $tipoEntrega = $validated['tipo_entrega'];
                $enderecoId = $tipoEntrega === 'entrega' ? $validated['endereco_id'] : null;

                $taxaEntrega = $tipoEntrega === 'entrega' ? 5.00 : 0.00;
                $descontoPix = $validated['pagamento_metodo'] === 'pix' ? round($subtotal * 0.05, 2) : 0.00;
                $total = max(0, round($subtotal + $taxaEntrega - $descontoPix, 2));

                $pedido = Pedido::create([
                    'user_id'          => $user->id,
                    'endereco_id'      => $enderecoId,
                    'tipo_entrega'     => $tipoEntrega,
                    'status'           => 'aguardando_pagamento',
                    'subtotal'         => $subtotal,
                    'taxa_entrega'     => $taxaEntrega,
                    'desconto'         => $descontoPix,
                    'total'            => $total,
                    'pagamento_metodo' => $validated['pagamento_metodo'],
                    'pagamento_status' => 'pendente',
                    'observacoes'      => $validated['observacoes'] ?? null,
                    'tempo_estimado'   => (int) config_val('tempo_estimado_entrega', 45),
                ]);

                $pedido->itens()->createMany($itensPronto->toArray());

                $pedido->historico()->create([
                    'status'  => 'aguardando_pagamento',
                    'user_id' => $user->id,
                ]);

                $pedido->load(['itens.prato', 'endereco']);

                if ($validated['pagamento_metodo'] === 'cartao_credito') {
                    $paymentData = $pagarme->criarPedidoCartao($pedido, $user, [
                        'card_token'     => $validated['card_token'],
                        'installments'   => $validated['parcelas'] ?? 1,
                        'card_last_four' => $validated['card_last_four'] ?? null,
                        'card_brand'     => $validated['card_brand'] ?? null,
                    ]);

                    return response()->json([
                        'success'  => true,
                        'codigo'   => $pedido->codigo,
                        'redirect' => route('cliente.pedido.acompanhar', $pedido->codigo),
                        'message'  => $paymentData['message'] ?? 'Pagamento com cartao aprovado.',
                        'payment'  => $paymentData,
                    ]);
                }

                $pixData = $pagarme->criarPedidoPix($pedido, $user);

            return response()->json([
                'success'  => true,
                'codigo'   => $pedido->codigo,
                'redirect' => route('cliente.pedido.pagamento', $pedido->codigo),
                'pix'      => $pixData,
            ]);
        });
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?: 'Dados invalidos para finalizar o pagamento.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Falha ao processar pagamento no checkout', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Nao foi possivel processar o pagamento agora. Tente novamente em instantes.',
            ], 500);
        }
    }
}
