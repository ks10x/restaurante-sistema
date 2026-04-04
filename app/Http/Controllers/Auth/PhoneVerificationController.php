<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PhoneVerificationController extends Controller
{
    private function cacheKey(int $userId): string
    {
        return "phone_verify_code:{$userId}";
    }

    public function notice(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user && method_exists($user, 'hasVerifiedPhone') && $user->hasVerifiedPhone()) {
            return redirect()->intended($this->defaultRedirectFor($user));
        }

        return view('auth.verify-phone');
    }

    public function send(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        if (blank($user->phone)) {
            return back()->withErrors([
                'phone' => 'Cadastre um telefone antes de verificar.',
            ]);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put($this->cacheKey((int) $user->id), hash('sha256', $code), now()->addMinutes(10));

        // Driver padrão (dev): log. Em produção, integre SMS/WhatsApp aqui.
        Log::info('Phone verification code generated', [
            'user_id' => $user->id,
            'phone' => $user->phone,
            'code' => $code,
        ]);

        return back()->with('status', 'phone-code-sent');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'min:4', 'max:10'],
        ]);

        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $expectedHash = Cache::get($this->cacheKey((int) $user->id));
        $providedHash = hash('sha256', (string) $request->input('code'));

        if (!$expectedHash || !hash_equals((string) $expectedHash, $providedHash)) {
            throw ValidationException::withMessages([
                'code' => 'Código inválido ou expirado.',
            ]);
        }

        $user->markPhoneAsVerified();
        Cache::forget($this->cacheKey((int) $user->id));

        return redirect()->intended($this->defaultRedirectFor($user))->with('status', 'phone-verified');
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
