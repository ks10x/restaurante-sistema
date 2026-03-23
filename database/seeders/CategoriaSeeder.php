<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Entradas', 'icone' => 'fas fa-utensils', 'cor' => '#f39c12', 'ordem' => 1, 'ativa' => 1],
            ['nome' => 'Massas', 'icone' => 'fas fa-pasta', 'cor' => '#d35400', 'ordem' => 2, 'ativa' => 1],
            ['nome' => 'Bebidas', 'icone' => 'fas fa-glass-whiskey', 'cor' => '#3498db', 'ordem' => 3, 'ativa' => 1],
        ];

        foreach ($categorias as $cat) {
            DB::table('categorias')->insert([
                'nome'       => $cat['nome'],
                'slug'       => Str::slug($cat['nome']), // Resolve o erro da imagem
                'icone'      => $cat['icone'],
                'cor'        => $cat['cor'],
                'ordem'      => $cat['ordem'],
                'ativa'      => $cat['ativa'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}