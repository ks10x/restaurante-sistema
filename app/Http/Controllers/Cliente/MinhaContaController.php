<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\ConsentimentoLgpd;
use App\Models\LoginAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MinhaContaController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $consentimentos = null;
        if (Schema::hasTable('consentimentos_lgpd')) {
            $consentimentos = ConsentimentoLgpd::query()->where('user_id', $user->id)->first();
        }

        $sessoesAtivas = collect();
        if (Schema::hasTable('seguranca_sessoes')) {
            $sessoesAtivas = DB::table('seguranca_sessoes')
                ->where('user_id', $user->id)
                ->orderByDesc('last_activity')
                ->limit(10)
                ->get();
        }

        $loginAttempts = collect();
        if (Schema::hasTable('login_attempts')) {
            $loginAttempts = LoginAttempt::query()
                ->where('email', $user->email)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        }

        return view('cliente.configuracoes', [
            'user' => $user,
            'consentimentos' => $consentimentos,
            'sessoesAtivas' => $sessoesAtivas,
            'loginAttempts' => $loginAttempts,
        ]);
    }

    public function revokeSessao(Request $request, string $id): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        abort_unless(Schema::hasTable('seguranca_sessoes'), 404);

        $sessao = DB::table('seguranca_sessoes')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        abort_unless($sessao, 404);

        DB::table('seguranca_sessoes')->where('id', $id)->delete();

        // Best-effort: se o app estiver usando session driver "database", tentar invalidar a sessão real também.
        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'id')) {
            DB::table('sessions')->where('id', $id)->delete();
        }

        if ($id === $request->session()->getId()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Sessão encerrada com sucesso.');
        }

        return redirect()->back()->with('success', 'Sessão encerrada com sucesso.');
    }

    public function revokeOutrasSessoes(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $currentId = $request->session()->getId();

        abort_unless(Schema::hasTable('seguranca_sessoes'), 404);

        $ids = DB::table('seguranca_sessoes')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentId)
            ->pluck('id')
            ->all();

        DB::table('seguranca_sessoes')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentId)
            ->delete();

        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'id') && ! empty($ids)) {
            DB::table('sessions')->whereIn('id', $ids)->delete();
        }

        return redirect()->back()->with('success', 'Outras sessões foram encerradas (quando possível).');
    }
}

