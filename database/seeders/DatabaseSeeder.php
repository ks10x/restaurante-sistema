<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ClienteSeeder::class,

            ConfiguracaoSeeder::class,
            HorarioFuncionamentoSeeder::class,
            CategoriaDemoSeeder::class,

            InsumoSeeder::class,
            PratoDemoSeeder::class,
            EstoqueMovimentacaoSeeder::class,

            CupomDemoSeeder::class,
            EnderecoSeeder::class,
            PedidoDemoSeeder::class,
            NotificacaoDemoSeeder::class,
        ]);
    }
}
