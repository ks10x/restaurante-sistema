<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracaoSeeder extends Seeder
{
    public function run(): void
    {
        $configuracoes = [
            ['chave' => 'restaurante_nome', 'valor' => 'Restaurante Bella Cucina', 'tipo' => 'texto', 'descricao' => 'Nome do restaurante'],
            ['chave' => 'restaurante_telefone', 'valor' => '(11) 99999-9999', 'tipo' => 'texto', 'descricao' => 'Telefone de contato'],
            ['chave' => 'taxa_entrega_padrao', 'valor' => '8.00', 'tipo' => 'numero', 'descricao' => 'Taxa de entrega padrão em R$'],
            ['chave' => 'raio_entrega_km', 'valor' => '10', 'tipo' => 'numero', 'descricao' => 'Raio máximo de entrega em km'],
            ['chave' => 'tempo_estimado_entrega', 'valor' => '45', 'tipo' => 'numero', 'descricao' => 'Tempo estimado de entrega em minutos'],
            ['chave' => 'pedido_minimo', 'valor' => '30.00', 'tipo' => 'numero', 'descricao' => 'Valor mínimo de pedido'],
            ['chave' => 'aceita_retirada', 'valor' => '1', 'tipo' => 'booleano', 'descricao' => 'Aceita retirada no local'],
            ['chave' => 'aceita_agendamento', 'valor' => '0', 'tipo' => 'booleano', 'descricao' => 'Aceita pedidos agendados'],
            ['chave' => 'pix_chave', 'valor' => '', 'tipo' => 'texto', 'descricao' => 'Chave PIX do restaurante'],
            ['chave' => 'instagram', 'valor' => '', 'tipo' => 'texto', 'descricao' => 'Link do Instagram'],
            ['chave' => 'whatsapp', 'valor' => '', 'tipo' => 'texto', 'descricao' => 'Número WhatsApp para contato'],
        ];

        foreach ($configuracoes as $config) {
            DB::table('configuracoes')->updateOrInsert(
                ['chave' => $config['chave']],
                [
                    'valor' => $config['valor'],
                    'tipo' => $config['tipo'],
                    'descricao' => $config['descricao'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}

