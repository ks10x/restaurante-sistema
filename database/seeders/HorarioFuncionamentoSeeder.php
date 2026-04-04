<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioFuncionamentoSeeder extends Seeder
{
    public function run(): void
    {
        $horarios = [
            ['dia_semana' => 0, 'abertura' => '11:00', 'fechamento' => '23:30', 'ativo' => 1], // Domingo
            ['dia_semana' => 1, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Segunda
            ['dia_semana' => 2, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Terça
            ['dia_semana' => 3, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Quarta
            ['dia_semana' => 4, 'abertura' => '11:00', 'fechamento' => '23:00', 'ativo' => 1], // Quinta
            ['dia_semana' => 5, 'abertura' => '11:00', 'fechamento' => '23:30', 'ativo' => 1], // Sexta
            ['dia_semana' => 6, 'abertura' => '11:00', 'fechamento' => '23:30', 'ativo' => 1], // Sábado
        ];

        foreach ($horarios as $horario) {
            DB::table('horarios_funcionamento')->updateOrInsert(
                ['dia_semana' => $horario['dia_semana']],
                $horario
            );
        }
    }
}

