<?php

namespace Database\Seeders;

use App\Models\Insumo;
use Illuminate\Database\Seeder;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        $insumosBase = [
            ['nome' => 'Tomate', 'unidade' => 'kg', 'quantidade_atual' => 2.500, 'quantidade_minima' => 1.000, 'preco_unitario' => 8.90, 'categoria' => 'Hortifruti'],
            ['nome' => 'Manjericão', 'unidade' => 'g', 'quantidade_atual' => 180.000, 'quantidade_minima' => 80.000, 'preco_unitario' => 0.06, 'categoria' => 'Hortifruti'],
            ['nome' => 'Azeite de Oliva', 'unidade' => 'l', 'quantidade_atual' => 3.000, 'quantidade_minima' => 1.000, 'preco_unitario' => 38.90, 'categoria' => 'Mercearia'],
            ['nome' => 'Alho', 'unidade' => 'kg', 'quantidade_atual' => 0.800, 'quantidade_minima' => 0.300, 'preco_unitario' => 22.50, 'categoria' => 'Hortifruti'],
            ['nome' => 'Cebola', 'unidade' => 'kg', 'quantidade_atual' => 1.200, 'quantidade_minima' => 0.500, 'preco_unitario' => 6.90, 'categoria' => 'Hortifruti'],
            ['nome' => 'Sal', 'unidade' => 'kg', 'quantidade_atual' => 2.000, 'quantidade_minima' => 0.500, 'preco_unitario' => 3.20, 'categoria' => 'Mercearia'],
            ['nome' => 'Pimenta-do-reino', 'unidade' => 'g', 'quantidade_atual' => 120.000, 'quantidade_minima' => 40.000, 'preco_unitario' => 0.12, 'categoria' => 'Mercearia'],
            ['nome' => 'Farinha de Trigo', 'unidade' => 'kg', 'quantidade_atual' => 5.000, 'quantidade_minima' => 2.000, 'preco_unitario' => 6.50, 'categoria' => 'Padaria'],
            ['nome' => 'Fermento Biológico', 'unidade' => 'g', 'quantidade_atual' => 300.000, 'quantidade_minima' => 80.000, 'preco_unitario' => 0.08, 'categoria' => 'Padaria'],
            ['nome' => 'Pão Italiano', 'unidade' => 'un', 'quantidade_atual' => 18.000, 'quantidade_minima' => 6.000, 'preco_unitario' => 3.50, 'categoria' => 'Padaria'],
            ['nome' => 'Macarrão', 'unidade' => 'kg', 'quantidade_atual' => 6.000, 'quantidade_minima' => 2.000, 'preco_unitario' => 14.90, 'categoria' => 'Mercearia'],
            ['nome' => 'Molho de Tomate', 'unidade' => 'kg', 'quantidade_atual' => 4.000, 'quantidade_minima' => 1.500, 'preco_unitario' => 11.90, 'categoria' => 'Mercearia'],
            ['nome' => 'Queijo Mussarela', 'unidade' => 'kg', 'quantidade_atual' => 0.120, 'quantidade_minima' => 0.600, 'preco_unitario' => 42.90, 'categoria' => 'Laticínios'],
            ['nome' => 'Queijo Parmesão', 'unidade' => 'kg', 'quantidade_atual' => 0.700, 'quantidade_minima' => 0.250, 'preco_unitario' => 69.90, 'categoria' => 'Laticínios'],
            ['nome' => 'Manteiga', 'unidade' => 'kg', 'quantidade_atual' => 1.200, 'quantidade_minima' => 0.400, 'preco_unitario' => 39.90, 'categoria' => 'Laticínios'],
            ['nome' => 'Creme de Leite', 'unidade' => 'l', 'quantidade_atual' => 2.000, 'quantidade_minima' => 0.600, 'preco_unitario' => 18.90, 'categoria' => 'Laticínios'],
            ['nome' => 'Leite', 'unidade' => 'l', 'quantidade_atual' => 12.000, 'quantidade_minima' => 4.000, 'preco_unitario' => 5.20, 'categoria' => 'Laticínios'],
            ['nome' => 'Ovo', 'unidade' => 'un', 'quantidade_atual' => 60.000, 'quantidade_minima' => 24.000, 'preco_unitario' => 0.90, 'categoria' => 'Frios'],
            ['nome' => 'Bacon', 'unidade' => 'kg', 'quantidade_atual' => 0.800, 'quantidade_minima' => 0.300, 'preco_unitario' => 49.90, 'categoria' => 'Carnes'],
            ['nome' => 'Frango', 'unidade' => 'kg', 'quantidade_atual' => 2.500, 'quantidade_minima' => 1.000, 'preco_unitario' => 19.90, 'categoria' => 'Carnes'],
            ['nome' => 'Carne Bovina', 'unidade' => 'kg', 'quantidade_atual' => 1.100, 'quantidade_minima' => 1.200, 'preco_unitario' => 55.90, 'categoria' => 'Carnes'],
            ['nome' => 'Peixe', 'unidade' => 'kg', 'quantidade_atual' => 0.900, 'quantidade_minima' => 0.500, 'preco_unitario' => 59.90, 'categoria' => 'Carnes'],
            ['nome' => 'Arroz', 'unidade' => 'kg', 'quantidade_atual' => 8.000, 'quantidade_minima' => 3.000, 'preco_unitario' => 7.90, 'categoria' => 'Mercearia'],
            ['nome' => 'Batata', 'unidade' => 'kg', 'quantidade_atual' => 4.000, 'quantidade_minima' => 2.000, 'preco_unitario' => 6.70, 'categoria' => 'Hortifruti'],
            ['nome' => 'Alface', 'unidade' => 'un', 'quantidade_atual' => 14.000, 'quantidade_minima' => 6.000, 'preco_unitario' => 3.20, 'categoria' => 'Hortifruti'],
            ['nome' => 'Limão', 'unidade' => 'kg', 'quantidade_atual' => 1.000, 'quantidade_minima' => 0.600, 'preco_unitario' => 7.80, 'categoria' => 'Hortifruti'],
            ['nome' => 'Chocolate', 'unidade' => 'kg', 'quantidade_atual' => 1.000, 'quantidade_minima' => 0.400, 'preco_unitario' => 38.00, 'categoria' => 'Confeitaria'],
            ['nome' => 'Açúcar', 'unidade' => 'kg', 'quantidade_atual' => 6.000, 'quantidade_minima' => 2.000, 'preco_unitario' => 4.20, 'categoria' => 'Confeitaria'],
            ['nome' => 'Refrigerante Lata', 'unidade' => 'un', 'quantidade_atual' => 24.000, 'quantidade_minima' => 12.000, 'preco_unitario' => 4.50, 'categoria' => 'Bebidas'],
            ['nome' => 'Água', 'unidade' => 'un', 'quantidade_atual' => 30.000, 'quantidade_minima' => 12.000, 'preco_unitario' => 3.00, 'categoria' => 'Bebidas'],
        ];

        foreach ($insumosBase as $insumo) {
            Insumo::updateOrCreate(
                ['nome' => $insumo['nome']],
                array_merge($insumo, [
                    'descricao' => $insumo['descricao'] ?? null,
                    'fornecedor' => $insumo['fornecedor'] ?? null,
                    'codigo_barras' => $insumo['codigo_barras'] ?? null,
                    'ativo' => $insumo['ativo'] ?? true,
                ])
            );
        }
    }
}

