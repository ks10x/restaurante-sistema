<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Double Rate Limit: Checar o IP (Global) e o Email
        $ipThrottleKey = 'login_ip_' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipThrottleKey, 10)) {
            // Se errou 10 vezes no IP, banir IP temporariamente
            DB::table('ips_banidos')->insertOrIgnore([
                'ip_address' => $request->ip(),
                'reason' => 'Brute force detectado via Limit Rate',
                'expires_at' => now()->addHours(2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($ipThrottleKey)]),
            ]);
        }

        try {
            $request->authenticate();
        } catch (\Exception $e) {
            RateLimiter::hit($ipThrottleKey);
            LoginAttempt::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'success' => false
            ]);
            throw $e;
        }

        RateLimiter::clear($ipThrottleKey);
        LoginAttempt::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'success' => true
        ]);

        $request->session()->regenerate();
        $user = Auth::user();

        // Security Sessões Tracking
        DB::table('seguranca_sessoes')->insert([
            'id' => session()->getId(),
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser_fingerprint' => md5($request->ip() . $request->userAgent()),
            'last_activity' => time(),
        ]);

        $user->update(['last_login_at' => now()]);

        return redirect()->intended(match((int)$user->role) {
            User::ROLE_ADMIN => route('admin.dashboard'),
            User::ROLE_COZINHA => route('cozinha.fila'),
            User::ROLE_ENTREGADOR => route('entregador.index'),
            default => route('cardapio'),
        });
    }

    public function destroy(Request $request): RedirectResponse
    {
        DB::table('seguranca_sessoes')->where('id', session()->getId())->delete();
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
