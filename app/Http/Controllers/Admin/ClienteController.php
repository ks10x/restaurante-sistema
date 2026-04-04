<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $openId = $request->query('view');

        $clientes = User::query()
            ->where(function ($q) {
                $q->where('role', User::ROLE_CLIENTE)->orWhere('role', 'cliente');
            })
            ->orderByDesc('id')
            ->paginate(20);

        $openCliente = null;
        if ($openId) {
            $openCliente = User::query()
                ->whereKey($openId)
                ->where(function ($q) {
                    $q->where('role', User::ROLE_CLIENTE)->orWhere('role', 'cliente');
                })
                ->first();
        }

        return view('admin.clientes', compact('clientes', 'openCliente'));
    }

    public function toggleBlock(User $user): RedirectResponse
    {
        $isCliente = ((string) $user->role === (string) User::ROLE_CLIENTE) || ((string) $user->role === 'cliente');
        abort_if(! $isCliente, 404);

        $user->status = ($user->status ?? 'ativo') === 'ativo' ? 'suspenso' : 'ativo';
        $user->save();

        return redirect()->back()->with('success', ($user->status === 'ativo')
            ? 'Cliente desbloqueado com sucesso!'
            : 'Cliente bloqueado com sucesso!'
        );
    }
}
