<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    public function index()
    {
        // 0 = Admin, 1 = Cozinha, 3 = Entregador. Rejeitamos 2 (Clientes)
        $funcionarios = User::with('funcionario')->whereIn('role', [0, 1, 3])->orderBy('id', 'desc')->paginate(20);
        return view('admin.funcionarios', compact('funcionarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', Rule::in([0, 1, 3])],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => (int) $request->role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        // Gera um código único de 6 dígitos
        do {
            $codigo = (string) mt_rand(100000, 999999);
        } while (Funcionario::where('codigo_identificacao', $codigo)->exists());

        Funcionario::create([
            'user_id' => $user->id,
            'codigo_identificacao' => $codigo,
        ]);

        return redirect()->back()->with('success', 'Funcionário cadastrado com sucesso!');
    }

    public function update(Request $request, User $user)
    {
        if($user->role == 2) {
            abort(403, 'Acesso negado para modificar clientes por esta rota.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in([0, 1, 3])],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => (int) $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Funcionário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        if ($user->role == 2) {
            abort(403, 'Acesso negado para remover clientes por esta rota.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Funcionário removido do sistema.');
    }
}
