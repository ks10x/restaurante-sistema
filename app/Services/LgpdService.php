<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\ConsentimentoLgpd;

class LgpdService
{
    /**
     * Anonimizar dados do usuário após expiração ou pedido de exclusão LGPD
     */
    public function anonymizeUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->update([
                'name' => 'Usuário Excluído',
                'email' => 'anonimo_' . $user->id . '@deletado.com',
                'phone_encrypted' => null,
                'cpf_encrypted' => null,
                'avatar' => null,
                'status' => 'inativo',
                // remove 2fa etc
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ]);

            // Se quisermos excluir de fato, $user->delete() aplicaria o soft delete, mas 
            // no caso da LGPD os PIIs precisam ser anonimizados para manter métricas de BD consistentes.
            
            // Registra um log explícito 
            app(AuditService::class)->log('LGPD_ANONYMIZED', 'users', $user->id);
            
            // Delete force
            $user->delete();
        });
    }

    /**
     * Exporta os dados num formato JSON para entregar ao cliente
     */
    public function exportUserData(User $user): array
    {
        return [
            'perfil' => [
                'nome' => $user->name,
                'email' => $user->email,
                'cadastrado_em' => $user->created_at->toIso8601String(),
                'status' => $user->status,
            ],
            'pedidos' => $user->pedidos()->select('codigo', 'total', 'created_at', 'status')->get()->toArray(),            
            'consentimentos' => $user->consentimentoLgpd ?? [],
        ];
    }

    public function handleConsent(User $user, array $choices): void
    {
        ConsentimentoLgpd::updateOrCreate(
            ['user_id' => $user->id],
            [
                'marketing' => $choices['marketing'] ?? false,
                'compartilhamento_terceiros' => $choices['terceiros'] ?? false,
                'cookies_analiticos' => $choices['analiticos'] ?? false,
                'ip_address' => request()->ip(),
                'accepted_at' => now(),
            ]
        );
    }
}
