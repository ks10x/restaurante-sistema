<?php

namespace Database\Seeders;

use App\Models\Insumo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstoqueMovimentacaoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('estoque_movimentacoes')->exists()) {
            return;
        }

        $userId = User::query()->orderBy('id')->value('id');
        if (! $userId) {
            return;
        }

        $now = now();

        $rows = [];
        foreach (Insumo::query()->get(['id', 'quantidade_atual', 'preco_unitario']) as $insumo) {
            $atual = (float) $insumo->quantidade_atual;

            // Entrada fictícia (ontem)
            $qEntrada = max(0.001, round($atual * 0.4, 3));
            $anteriorEntrada = max(0, $atual - $qEntrada);
            $rows[] = [
                'insumo_id' => $insumo->id,
                'user_id' => $userId,
                'tipo' => 'entrada',
                'quantidade' => $qEntrada,
                'quantidade_anterior' => $anteriorEntrada,
                'quantidade_posterior' => $anteriorEntrada + $qEntrada,
                'preco_unitario' => $insumo->preco_unitario,
                'motivo' => 'Compra (seed)',
                'referencia_id' => null,
                'referencia_tipo' => 'compra',
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ];

            // Saída fictícia (hoje)
            $qSaida = max(0.001, round($atual * 0.15, 3));
            $rows[] = [
                'insumo_id' => $insumo->id,
                'user_id' => $userId,
                'tipo' => 'saida',
                'quantidade' => $qSaida,
                'quantidade_anterior' => $atual,
                'quantidade_posterior' => max(0, $atual - $qSaida),
                'preco_unitario' => $insumo->preco_unitario,
                'motivo' => 'Consumo (seed)',
                'referencia_id' => null,
                'referencia_tipo' => 'ajuste',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (count($rows) > 0) {
            DB::table('estoque_movimentacoes')->insert($rows);
        }
    }
}

