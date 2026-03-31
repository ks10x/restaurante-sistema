<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'cpf' => ['required', 'string', 'max:14'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'last_name'=> $request->last_name,
            'phone'    => $request->phone,
            // If the application uses casts for encryption, we map normal attributes. Or if they use exact column names:
            'phone_encrypted' => $request->phone,
            'cpf'      => $request->cpf,
            'cpf_encrypted' => $request->cpf,
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => User::ROLE_CLIENTE,
            'status'   => 'ativo',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('cardapio.index');
    }
}
