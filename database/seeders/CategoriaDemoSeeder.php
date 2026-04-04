<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Entradas', 'icone' => '🥗', 'cor' => '#f39c12', 'ordem' => 1, 'ativa' => 1],
            ['nome' => 'Pratos Principais', 'icone' => '🍽️', 'cor' => '#d35400', 'ordem' => 2, 'ativa' => 1],
            ['nome' => 'Massas', 'icone' => '🍝', 'cor' => '#c0392b', 'ordem' => 3, 'ativa' => 1],
            ['nome' => 'Grelhados', 'icone' => '🥩', 'cor' => '#7f8c8d', 'ordem' => 4, 'ativa' => 1],
            ['nome' => 'Sobremesas', 'icone' => '🍮', 'cor' => '#8e44ad', 'ordem' => 5, 'ativa' => 1],
            ['nome' => 'Bebidas', 'icone' => '🥤', 'cor' => '#3498db', 'ordem' => 6, 'ativa' => 1],
            ['nome' => 'Combos', 'icone' => '🍱', 'cor' => '#16a085', 'ordem' => 7, 'ativa' => 1],
        ];

        foreach ($categorias as $categoria) {
            $slug = Str::slug($categoria['nome']);

            Categoria::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                [
                    'nome' => $categoria['nome'],
                    'descricao' => $categoria['descricao'] ?? null,
                    'icone' => $categoria['icone'] ?? null,
                    'cor' => $categoria['cor'] ?? null,
                    'ordem' => $categoria['ordem'] ?? 0,
                    'ativa' => (bool) ($categoria['ativa'] ?? true),
                    'deleted_at' => null,
                ]
            );
        }
    }
}

