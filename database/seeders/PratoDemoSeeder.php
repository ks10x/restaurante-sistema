<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Insumo;
use App\Models\Prato;
use App\Services\PratoEstoqueService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PratoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = Categoria::query()
            ->whereNull('deleted_at')
            ->get(['id', 'slug'])
            ->keyBy('slug');

        $insumos = Insumo::query()
            ->get(['id', 'nome'])
            ->keyBy('nome');

        $pratos = [
            [
                'categoria' => 'entradas',
                'nome' => 'Bruschetta Italiana',
                'descricao' => 'Pão italiano tostado com tomates, manjericão e azeite.',
                'preco' => 25.90,
                'destaque' => true,
                'insumos' => [
                    'Pão Italiano' => 2.000,
                    'Tomate' => 0.200,
                    'Manjericão' => 10.000,
                    'Azeite de Oliva' => 0.020,
                    'Alho' => 0.010,
                    'Sal' => 0.005,
                ],
            ],
            [
                'categoria' => 'massas',
                'nome' => 'Tagliatelle ao Pesto',
                'descricao' => 'Massa com pesto de manjericão e parmesão.',
                'preco' => 48.00,
                'insumos' => [
                    'Macarrão' => 0.180,
                    'Manjericão' => 25.000,
                    'Azeite de Oliva' => 0.030,
                    'Queijo Parmesão' => 0.030,
                    'Alho' => 0.008,
                    'Sal' => 0.005,
                ],
            ],
            [
                'categoria' => 'pratos-principais',
                'nome' => 'Bife Grelhado com Batatas',
                'descricao' => 'Bife grelhado no ponto com batatas rústicas.',
                'preco' => 59.90,
                'insumos' => [
                    'Carne Bovina' => 0.220,
                    'Batata' => 0.250,
                    'Azeite de Oliva' => 0.020,
                    'Sal' => 0.006,
                    'Pimenta-do-reino' => 2.000,
                ],
            ],
            [
                'categoria' => 'grelhados',
                'nome' => 'Frango Grelhado com Arroz',
                'descricao' => 'Peito de frango grelhado com arroz soltinho.',
                'preco' => 44.90,
                'insumos' => [
                    'Frango' => 0.220,
                    'Arroz' => 0.120,
                    'Sal' => 0.005,
                    'Azeite de Oliva' => 0.010,
                ],
            ],
            [
                'categoria' => 'massas',
                'nome' => 'Spaghetti ao Molho de Tomate',
                'descricao' => 'Clássico spaghetti com molho de tomate e parmesão.',
                'preco' => 39.90,
                'insumos' => [
                    'Macarrão' => 0.180,
                    'Molho de Tomate' => 0.220,
                    'Queijo Parmesão' => 0.020,
                    'Azeite de Oliva' => 0.010,
                    'Sal' => 0.005,
                ],
            ],
            [
                'categoria' => 'pratos-principais',
                'nome' => 'Pizza Margherita',
                'descricao' => 'Molho de tomate, mussarela e manjericão.',
                'preco' => 52.00,
                'destaque' => true,
                'insumos' => [
                    'Farinha de Trigo' => 0.250,
                    'Fermento Biológico' => 6.000,
                    'Molho de Tomate' => 0.180,
                    'Queijo Mussarela' => 0.220,
                    'Manjericão' => 8.000,
                    'Azeite de Oliva' => 0.010,
                    'Sal' => 0.006,
                ],
            ],
            [
                'categoria' => 'pratos-principais',
                'nome' => 'Pizza Quatro Queijos',
                'descricao' => 'Mussarela, parmesão e mix de queijos.',
                'preco' => 58.00,
                'insumos' => [
                    'Farinha de Trigo' => 0.250,
                    'Fermento Biológico' => 6.000,
                    'Molho de Tomate' => 0.160,
                    'Queijo Mussarela' => 0.260,
                    'Queijo Parmesão' => 0.040,
                    'Sal' => 0.006,
                ],
            ],
            [
                'categoria' => 'entradas',
                'nome' => 'Salada Caesar',
                'descricao' => 'Alface, frango grelhado, parmesão e molho especial.',
                'preco' => 32.90,
                'insumos' => [
                    'Alface' => 1.000,
                    'Frango' => 0.120,
                    'Queijo Parmesão' => 0.015,
                    'Azeite de Oliva' => 0.010,
                    'Sal' => 0.004,
                ],
            ],
            [
                'categoria' => 'sobremesas',
                'nome' => 'Brownie de Chocolate',
                'descricao' => 'Brownie úmido com chocolate e manteiga.',
                'preco' => 18.90,
                'insumos' => [
                    'Chocolate' => 0.040,
                    'Manteiga' => 0.020,
                    'Açúcar' => 0.030,
                    'Ovo' => 2.000,
                    'Farinha de Trigo' => 0.020,
                ],
            ],
            [
                'categoria' => 'bebidas',
                'nome' => 'Refrigerante Lata',
                'descricao' => 'Refrigerante gelado (lata).',
                'preco' => 6.50,
                'insumos' => [
                    'Refrigerante Lata' => 1.000,
                ],
            ],
            [
                'categoria' => 'bebidas',
                'nome' => 'Água Mineral',
                'descricao' => 'Água sem gás.',
                'preco' => 4.50,
                'insumos' => [
                    'Água' => 1.000,
                ],
            ],
            [
                'categoria' => 'combos',
                'nome' => 'Combo Pizza + Refrigerante',
                'descricao' => 'Uma pizza Margherita + 1 refrigerante (lata).',
                'preco' => 56.90,
                'insumos' => [
                    'Farinha de Trigo' => 0.250,
                    'Fermento Biológico' => 6.000,
                    'Molho de Tomate' => 0.180,
                    'Queijo Mussarela' => 0.220,
                    'Manjericão' => 8.000,
                    'Refrigerante Lata' => 1.000,
                ],
            ],
        ];

        $service = app(PratoEstoqueService::class);

        $ordem = 1;
        foreach ($pratos as $data) {
            $categoriaSlug = $data['categoria'];
            $categoriaId = $categorias[$categoriaSlug]->id ?? null;
            if (! $categoriaId) {
                continue;
            }

            $slug = Str::slug($data['nome']) ?: ('prato-' . $ordem);

            $prato = Prato::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                [
                    'categoria_id' => $categoriaId,
                    'nome' => $data['nome'],
                    'descricao' => $data['descricao'],
                    'preco' => $data['preco'],
                    'preco_promocional' => $data['preco_promocional'] ?? null,
                    'imagem' => $data['imagem'] ?? null,
                    'calorias' => $data['calorias'] ?? null,
                    'tempo_preparo' => $data['tempo_preparo'] ?? null,
                    'porcao' => $data['porcao'] ?? null,
                    'ativo' => true,
                    'disponivel' => $data['disponivel'] ?? true,
                    'destaque' => (bool) ($data['destaque'] ?? false),
                    'ordem' => $ordem,
                    'deleted_at' => null,
                ]
            );

            $sync = [];
            foreach (($data['insumos'] ?? []) as $insumoNome => $quantidade) {
                $insumo = $insumos[$insumoNome] ?? null;
                if (! $insumo) {
                    continue;
                }
                $sync[$insumo->id] = ['quantidade' => $quantidade];
            }

            if (count($sync) > 0) {
                $prato->insumos()->sync($sync);
            }

            $service->syncAtivoForPrato($prato->fresh());
            $ordem++;
        }
    }
}

