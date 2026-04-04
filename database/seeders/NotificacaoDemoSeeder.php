<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificacaoDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('notificacoes')->exists()) {
            return;
        }

        $usuarios = User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COZINHA])
            ->whereNull('deleted_at')
            ->get(['id', 'role']);

        foreach ($usuarios as $user) {
            DB::table('notificacoes')->insert([
                'user_id' => $user->id,
                'tipo' => 'seed_demo',
                'titulo' => 'Bem-vindo!',
                'mensagem' => 'Notificação de exemplo criada via seed.',
                'dados' => json_encode(['origem' => 'seeder']),
                'lida' => 0,
                'lida_em' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

