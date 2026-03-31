<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('role')) {
            $oldRole = $user->getOriginal('role');
            $newRole = $user->role;
            
            // Segurança: Log severo se alguém ganhar permissão de admin
            if ($newRole == clone(\App\Models\User::ROLE_ADMIN)) {
                Log::alert("Atenção: O usuário {$user->email} recebeu direitos de Administração.", [
                    'changed_by' => auth()->id() ?? 'system'
                ]);
            }
        }
    }
}
