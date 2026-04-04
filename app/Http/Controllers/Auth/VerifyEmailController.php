<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        $redirectTo = $this->defaultRedirectFor($user) . '?verified=1';

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($redirectTo);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($redirectTo);
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
