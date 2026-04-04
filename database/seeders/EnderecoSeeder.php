<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnderecoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('pt_BR');

        $clientes = User::query()
            ->where('role', User::ROLE_CLIENTE)
            ->whereNull('deleted_at')
            ->get(['id']);

        foreach ($clientes as $cliente) {
            $jaTem = DB::table('enderecos')
                ->where('user_id', $cliente->id)
                ->whereNull('deleted_at')
                ->exists();

            if ($jaTem) {
                continue;
            }

            $qtd = rand(1, 2);
            for ($i = 0; $i < $qtd; $i++) {
                DB::table('enderecos')->insert([
                    'user_id' => $cliente->id,
                    'apelido' => $i === 0 ? 'Casa' : 'Trabalho',
                    'cep' => $faker->postcode(),
                    'logradouro' => $faker->streetName(),
                    'numero' => (string) $faker->buildingNumber(),
                    'complemento' => rand(0, 1) ? $faker->secondaryAddress() : null,
                    'bairro' => $faker->citySuffix(),
                    'cidade' => $faker->city(),
                    'estado' => $faker->stateAbbr(),
                    'latitude' => null,
                    'longitude' => null,
                    'principal' => $i === 0 ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]);
            }
        }
    }
}

