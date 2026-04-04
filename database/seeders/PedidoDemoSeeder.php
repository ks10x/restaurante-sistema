<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Prato;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PedidoDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('pedidos')->exists()) {
            return;
        }

        $clientes = User::query()
            ->where('role', User::ROLE_CLIENTE)
            ->whereNull('deleted_at')
            ->pluck('id')
            ->all();

        if (count($clientes) === 0) {
            return;
        }

        $pratos = Prato::query()
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get(['id', 'nome', 'preco', 'preco_promocional', 'ativo', 'disponivel']);

        if ($pratos->isEmpty()) {
            return;
        }

        $pratosAtivos = $pratos->where('ativo', true)->where('disponivel', true)->values();
        $pratosPool = $pratosAtivos->isNotEmpty() ? $pratosAtivos : $pratos->values();

        $statuses = [
            'aguardando_pagamento',
            'confirmado',
            'em_producao',
            'saindo_entrega',
            'entregue',
            'cancelado',
        ];

        $metodos = ['pix', 'dinheiro', 'cartao_credito', 'cartao_debito'];

        DB::transaction(function () use ($clientes, $pratosPool, $statuses, $metodos) {
            $n = 25;
            for ($i = 0; $i < $n; $i++) {
                $userId = $clientes[array_rand($clientes)];

                $enderecoId = DB::table('enderecos')
                    ->where('user_id', $userId)
                    ->whereNull('deleted_at')
                    ->orderByDesc('principal')
                    ->value('id');

                $tipoEntrega = $enderecoId ? (rand(1, 10) <= 8 ? 'entrega' : 'retirada') : 'retirada';
                $enderecoId = $tipoEntrega === 'entrega' ? $enderecoId : null;

                $status = $statuses[array_rand($statuses)];
                $pagamentoMetodo = $metodos[array_rand($metodos)];
                $pagamentoStatus = in_array($status, ['confirmado', 'em_producao', 'saindo_entrega', 'entregue'], true) ? 'aprovado' : 'pendente';
                if ($status === 'cancelado') {
                    $pagamentoStatus = 'recusado';
                }

                $qtdItens = rand(1, 4);
                $itens = [];
                $subtotal = 0.0;

                for ($k = 0; $k < $qtdItens; $k++) {
                    $prato = $pratosPool[rand(0, $pratosPool->count() - 1)];
                    $preco = (float) ($prato->preco_promocional ?? $prato->preco);
                    $quantidade = rand(1, 3);
                    $linhaSubtotal = round($preco * $quantidade, 2);
                    $subtotal += $linhaSubtotal;

                    $itens[] = [
                        'prato_id' => $prato->id,
                        'nome_prato' => $prato->nome,
                        'preco_unitario' => $preco,
                        'quantidade' => $quantidade,
                        'subtotal' => $linhaSubtotal,
                        'opcoes' => null,
                        'observacao' => rand(1, 10) === 1 ? 'Sem cebola, por favor.' : null,
                    ];
                }

                $taxaEntrega = $tipoEntrega === 'entrega' ? 8.00 : 0.00;
                $desconto = 0.00;
                $total = max(0, round($subtotal + $taxaEntrega - $desconto, 2));

                do {
                    $codigo = Str::upper(Str::random(8));
                } while (DB::table('pedidos')->where('codigo', $codigo)->exists());

                $pedido = Pedido::create([
                    'codigo' => $codigo,
                    'user_id' => $userId,
                    'endereco_id' => $enderecoId,
                    'tipo_entrega' => $tipoEntrega,
                    'status' => $status,
                    'subtotal' => $subtotal,
                    'taxa_entrega' => $taxaEntrega,
                    'desconto' => $desconto,
                    'total' => $total,
                    'pagamento_metodo' => $pagamentoMetodo,
                    'pagamento_status' => $pagamentoStatus,
                    'observacoes' => rand(1, 10) === 1 ? 'Caprichar no molho.' : null,
                    'tempo_estimado' => 45,
                    'confirmado_em' => in_array($status, ['confirmado', 'em_producao', 'saindo_entrega', 'entregue'], true) ? now()->subMinutes(rand(20, 120)) : null,
                    'producao_em' => in_array($status, ['em_producao', 'saindo_entrega', 'entregue'], true) ? now()->subMinutes(rand(15, 90)) : null,
                    'saiu_em' => in_array($status, ['saindo_entrega', 'entregue'], true) ? now()->subMinutes(rand(10, 60)) : null,
                    'entregue_em' => $status === 'entregue' ? now()->subMinutes(rand(1, 30)) : null,
                    'cancelado_em' => $status === 'cancelado' ? now()->subMinutes(rand(1, 120)) : null,
                    'cancelado_por' => null,
                    'motivo_cancelamento' => $status === 'cancelado' ? 'Pedido cancelado (seed)' : null,
                ]);

                foreach ($itens as $item) {
                    DB::table('pedido_itens')->insert(array_merge($item, [
                        'pedido_id' => $pedido->id,
                    ]));
                }

                DB::table('pedido_status_historico')->insert([
                    'pedido_id' => $pedido->id,
                    'status' => $status,
                    'user_id' => null,
                    'observacao' => 'Criado via seed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
