<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@restaurante.com'],
            [
                'name'      => 'Administrador',
                'password'  => Hash::make('Admin@1234'),
                'role'      => User::ROLE_ADMIN,
                'status'    => 'ativo',
            ]
        );

        // Cozinha
        User::updateOrCreate(
            ['email' => 'cozinha@restaurante.com'],
            [
                'name'      => 'Chef Cozinha',
                'password'  => Hash::make('Cozinha@1234'),
                'role'      => User::ROLE_COZINHA,
                'status'    => 'ativo',
            ]
        );

        // Cliente de teste
        User::updateOrCreate(
            ['email' => 'cliente@teste.com'],
            [
                'name'      => 'Cliente Teste',
                'password'  => Hash::make('Cliente@1234'),
                'role'      => User::ROLE_CLIENTE,
                'status'    => 'ativo',
            ]
        );

        $this->command->info('Usuários criados/atualizados:');
        $this->command->table(
            ['Email', 'Role', 'Senha'],
            [
                ['admin@restaurante.com',   'Admin (0)',    'Admin@1234'],
                ['cozinha@restaurante.com', 'Cozinha (1)', 'Cozinha@1234'],
                ['cliente@teste.com',       'Cliente (2)', 'Cliente@1234'],
            ]
        );
    }
}
