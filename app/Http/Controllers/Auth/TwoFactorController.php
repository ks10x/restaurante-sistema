<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TwoFactorService;

class TwoFactorController extends Controller
{
    private $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function index()
    {
        return view('auth.2fa_verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $user = auth()->user();

        // Checar App
        if ($this->twoFactorService->verifyKey($user->two_factor_secret, $request->code)) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended(route('admin.dashboard'));
        }

        // Checar Recovery Code
        if ($this->twoFactorService->isRecoveryCodeValid($user, $request->code)) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended(route('admin.dashboard'))->with('warning', 'Acesso via código de recuperação. Lembre-se de reconfigurar seu 2FA caso tenha perdido o dispositivo.');
        }

        return back()->withErrors(['code' => 'Código inválido.']);
    }

    // Gerar 2FA
    public function setup()
    {
        $user = auth()->user();
        if ($user->two_factor_secret) {
            return back()->with('error', '2FA já está ativo.');
        }

        $secret = $this->twoFactorService->generateSecretKey();
        $qrCode = $this->twoFactorService->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
        $recovery = $this->twoFactorService->generateRecoveryCodes();

        // Guarda provisoriamente para confirmar
        session(['2fa_setup_secret' => $secret, '2fa_setup_recovery' => collect($recovery)->toArray()]);

        return view('auth.2fa_setup', compact('qrCode', 'secret', 'recovery'));
    }

    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $secret = session('2fa_setup_secret');
        
        if (!$secret || !$this->twoFactorService->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Código inválido. Tente novamente.']);
        }

        $user = auth()->user();
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => encrypt(json_encode(session('2fa_setup_recovery'))),
            'two_factor_confirmed_at' => now(),
        ]);

        session()->forget(['2fa_setup_secret', '2fa_setup_recovery']);
        session(['2fa_verified' => true]);

        return redirect()->route('admin.dashboard')->with('success', 'Autenticação em duas etapas ativada com sucesso!');
    }
}
