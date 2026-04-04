<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($this->defaultRedirectFor($user));
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            // Fallback (dev): gera a URL e registra no log para não travar o fluxo.
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );

            Log::warning('Email verification notification failed; logging URL fallback.', [
                'user_id' => $user->getKey(),
                'email' => $user->email,
                'error' => $e->getMessage(),
                'verification_url' => $verificationUrl,
            ]);

            return back()->with([
                'status' => 'verification-link-sent',
                'verification_url' => $verificationUrl,
            ]);
        }

        return back()->with('status', 'verification-link-sent');
    }

    private function defaultRedirectFor($user): string
    {
        return match ((int) ($user->role ?? \App\Models\User::ROLE_CLIENTE)) {
            \App\Models\User::ROLE_ADMIN => route('admin.dashboard'),
            \App\Models\User::ROLE_COZINHA => route('cozinha.fila'),
            \App\Models\User::ROLE_ENTREGADOR => route('entregador.index'),
            default => route('cardapio.index'),
        };
    }
}
