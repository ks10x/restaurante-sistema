<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PratoSeeder extends Seeder
{
    public function run(): void
    {
        $pratos = [
            [
                'categoria_id' => 1, // ID da categoria 'Entradas'
                'nome' => 'Bruschetta Italiana',
                'descricao' => 'Pão italiano tostado com tomates, manjericão e azeite.',
                'preco' => 25.90,
                'imagem' => 'bruschetta.jpg',
                'disponivel' => 1,
                'destaque' => 1,
            ],
            [
                'categoria_id' => 3, // ID da categoria 'Massas'
                'nome' => 'Tagliatelle ao Pesto',
                'descricao' => 'Massa artesanal com molho pesto de manjericão fresco.',
                'preco' => 48.00,
                'imagem' => 'tagliatelle.jpg',
                'disponivel' => 1,
                'destaque' => 0,
            ],
        ];

        foreach ($pratos as $prato) {
            DB::table('pratos')->insert(array_merge($prato, [
                'slug' => Str::slug($prato['nome']),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}