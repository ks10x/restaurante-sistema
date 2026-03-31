<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConsentimentoLgpd;
use App\Services\LgpdService;
use App\Models\User;
use Carbon\Carbon;

class LgpdLimpar extends Command
{
    protected $signature = 'lgpd:limpar-expirados';
    protected $description = 'Anonimiza dados de usuários que revogaram consentimento há mais de 30 dias';

    public function handle(LgpdService $lgpd)
    {
        $this->info('Iniciando limpeza LGPD...');

        $expirados = ConsentimentoLgpd::where('revoked_at', '<=', Carbon::now()->subDays(30))->get();

        foreach ($expirados as $consentimento) {
            $user = $consentimento->user;
            if ($user) {
                $lgpd->anonymizeUser($user);
                $this->line("Usuário {$user->id} anonimizado.");
            }
        }

        $this->info('Limpeza concluída.');
    }
}
