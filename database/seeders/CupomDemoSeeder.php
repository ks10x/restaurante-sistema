<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CupomDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $cupons = [
            [
                'codigo' => 'BEMVINDO10',
                'descricao' => '10% OFF na primeira compra',
                'tipo' => 'percentual',
                'valor' => 10.00,
                'uso_maximo' => null,
                'uso_por_cliente' => 1,
                'pedido_minimo' => 25.00,
                'validade_inicio' => $now->copy()->subDays(7),
                'validade_fim' => $now->copy()->addDays(60),
                'ativo' => 1,
            ],
            [
                'codigo' => 'OFF15',
                'descricao' => 'R$ 15 de desconto',
                'tipo' => 'fixo',
                'valor' => 15.00,
                'uso_maximo' => 200,
                'uso_por_cliente' => 1,
                'pedido_minimo' => 60.00,
                'validade_inicio' => $now->copy()->subDays(3),
                'validade_fim' => $now->copy()->addDays(30),
                'ativo' => 1,
            ],
            [
                'codigo' => 'FRETEGRATIS',
                'descricao' => 'Frete grátis (simulado)',
                'tipo' => 'frete_gratis',
                'valor' => 0.00,
                'uso_maximo' => 100,
                'uso_por_cliente' => 1,
                'pedido_minimo' => 40.00,
                'validade_inicio' => $now->copy()->subDays(1),
                'validade_fim' => $now->copy()->addDays(15),
                'ativo' => 1,
            ],
        ];

        foreach ($cupons as $cupom) {
            $codigo = $cupom['codigo'];

            $totalUso = (int) (DB::table('cupons')->where('codigo', $codigo)->value('total_uso') ?? 0);

            DB::table('cupons')->updateOrInsert(
                ['codigo' => $codigo],
                array_merge($cupom, [
                    'total_uso' => $totalUso,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}

