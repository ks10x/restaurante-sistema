<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->intended($this->defaultRedirectFor($user));
        }

        return view('auth.verify-email');
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
