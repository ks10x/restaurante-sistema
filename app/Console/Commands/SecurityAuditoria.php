<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoginAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SecurityAuditoria extends Command
{
    protected $signature = 'security:auditoria';
    protected $description = 'Roda auditoria diária procurando padrões anômalos no banco e gera log';

    public function handle()
    {
        $hoje = Carbon::today();
        
        $tentativasFalhas = LoginAttempt::where('success', false)->whereDate('created_at', $hoje)->count();
        $ipsBanidos = \Illuminate\Support\Facades\DB::table('ips_banidos')->whereDate('created_at', $hoje)->count();
        
        if ($tentativasFalhas > 100) {
            Log::alert("ALERTA DE SEGURANÇA: {$tentativasFalhas} tentativas de login falhas apenas hoje.");
        }

        if ($ipsBanidos > 10) {
            Log::warning("Aviso: {$ipsBanidos} IPs foram banidos hoje pelo sistema de detecção de anomalias.");
        }

        $this->info("Auditoria finalizada. Falhas: {$tentativasFalhas} | IPs banidos: {$ipsBanidos}");
    }
}
