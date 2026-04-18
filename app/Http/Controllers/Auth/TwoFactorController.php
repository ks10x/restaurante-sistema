<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TwoFactorService;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    private $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Tela de verificação 2FA (exibida após login quando 2FA está ativo)
     */
    public function index()
    {
        $user = auth()->user();

        // Se não tem 2FA ativo, redirecionar para o painel
        if (!$user || empty($user->two_factor_secret) || empty($user->two_factor_confirmed_at)) {
            return redirect($this->defaultRedirectFor($user));
        }

        // Se já verificou nesta sessão, redirecionar
        if (session()->has('2fa_verified')) {
            return redirect()->intended($this->defaultRedirectFor($user));
        }

        return view('auth.2fa_verify');
    }

    /**
     * Verificar código 2FA no login
     */
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $user = auth()->user();

        if (!$user || empty($user->two_factor_secret)) {
            return redirect($this->defaultRedirectFor($user));
        }

        // Checar código do App
        if ($this->twoFactorService->verifyKey($user->two_factor_secret, $request->code)) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended($this->defaultRedirectFor($user));
        }

        // Checar Recovery Code
        if ($this->twoFactorService->isRecoveryCodeValid($user, $request->code)) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended($this->defaultRedirectFor($user))
                ->with('warning', 'Acesso via código de recuperação. Considere reconfigurar seu 2FA caso tenha perdido o dispositivo.');
        }

        return back()->withErrors(['code' => 'Código inválido. Verifique e tente novamente.']);
    }

    /**
     * Tela de configuração do 2FA (gera QR Code + chave manual)
     */
    public function setup()
    {
        $user = auth()->user();

        // Se já tem 2FA ativo, redirecionar para configurações
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            return redirect()->route('cliente.configuracoes')
                ->with('info', '2FA já está ativo na sua conta.');
        }

        $secret = $this->twoFactorService->generateSecretKey();
        $otpauthUrl = $this->twoFactorService->getOtpAuthUrl(config('app.name', 'Restaurante'), $user->email, $secret);
        $recovery = $this->twoFactorService->generateRecoveryCodes();

        // Gerar QR Code como SVG
        $qrCodeSvg = $this->generateQrCodeSvg($otpauthUrl);

        // Guardar na sessão para confirmar
        session([
            '2fa_setup_secret' => $secret,
            '2fa_setup_recovery' => collect($recovery)->toArray(),
        ]);

        return view('auth.2fa_setup', compact('otpauthUrl', 'secret', 'recovery', 'qrCodeSvg'));
    }

    /**
     * Confirmar ativação do 2FA (usuário digita o código gerado pelo app)
     */
    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $secret = session('2fa_setup_secret');

        if (!$secret || !$this->twoFactorService->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Código inválido. Abra o Google Authenticator e tente novamente.']);
        }

        $user = auth()->user();
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => encrypt(json_encode(session('2fa_setup_recovery'))),
            'two_factor_confirmed_at' => now(),
        ]);

        session()->forget(['2fa_setup_secret', '2fa_setup_recovery']);
        session(['2fa_verified' => true]);

        // Redirecionar baseado no role
        $redirectRoute = match ((int) $user->role) {
            User::ROLE_ADMIN => 'cliente.configuracoes',
            User::ROLE_COZINHA => 'cliente.configuracoes',
            default => 'cliente.configuracoes',
        };

        return redirect()->route($redirectRoute)
            ->with('success', '✅ Autenticação em duas etapas ativada com sucesso!');
    }

    /**
     * Desativar 2FA (exige senha para confirmação)
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Senha incorreta.']);
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        session()->forget('2fa_verified');

        return redirect()->route('cliente.configuracoes')
            ->with('success', '🔓 Autenticação em duas etapas desativada.');
    }

    /**
     * Gerar QR Code como SVG inline
     */
    private function generateQrCodeSvg(string $otpauthUrl): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200, 0),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($otpauthUrl);
    }

    /**
     * Redirecionar baseado no role do usuário
     */
    private function defaultRedirectFor($user): string
    {
        return match ((int) ($user->role ?? User::ROLE_CLIENTE)) {
            User::ROLE_ADMIN => route('admin.dashboard'),
            User::ROLE_COZINHA => route('cozinha.fila'),
            User::ROLE_ENTREGADOR => route('entregador.index'),
            default => route('cardapio.index'),
        };
    }
}
