<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LgpdService;
use App\Models\User;

class LgpdController extends Controller
{
    private $lgpd;

    public function __construct(LgpdService $lgpd)
    {
        $this->lgpd = $lgpd;
    }

    public function index()
    {
        $user = auth()->user();
        $consentimentos = $user->consentimentoLgpd;
        return view('cliente.lgpd.index', compact('consentimentos', 'user'));
    }

    public function updateConsent(Request $request)
    {
        $user = auth()->user();
        $this->lgpd->handleConsent($user, $request->only(['marketing', 'terceiros', 'analiticos']));
        return back()->with('success', 'Preferências de privacidade atualizadas.');
    }

    public function export()
    {
        $data = $this->lgpd->exportUserData(auth()->user());
        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="meus_dados_lgpd.json"'
        ]);
    }

    public function anonymize(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);
        
        $user = auth()->user();
        auth()->logout();
        
        $this->lgpd->anonymizeUser($user);
        
        return redirect('/')->with('success', 'Sua conta e dados foram anonimizados com sucesso.');
    }
}
