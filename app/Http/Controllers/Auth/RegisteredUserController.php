<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Endereco;
use App\Models\RestaurantConfig;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $restaurantConfig = RestaurantConfig::storefront();

        return view('auth.register_wizard', compact('restaurantConfig'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $cpfDigits = preg_replace('/\D/', '', (string) $request->input('cpf'));
        $cepDigits = preg_replace('/\D/', '', (string) $request->input('cep'));
        $phoneDigits = preg_replace('/\D/', '', (string) $request->input('phone'));

        $request->merge([
            'cpf' => $cpfDigits,
            'cep' => $cepDigits,
            'phone_digits' => $phoneDigits,
        ]);

        $request->validate([
            // Etapa 1: dados pessoais
            'name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                'unique:users,cpf',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! $this->isValidCpf((string) $value)) {
                        $fail('CPF inválido.');
                    }
                },
            ],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_year' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:180', 'unique:' . User::class],

            // Etapa 2: endereço (ViaCEP)
            'cep' => ['required', 'string', 'size:8'],
            'logradouro' => ['required', 'string', 'max:180'],
            'bairro' => ['required', 'string', 'max:80'],
            'cidade' => ['required', 'string', 'max:80'],
            'estado' => ['required', 'string', 'size:2'],
            'numero' => ['required', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:80'],

            // Etapa 3: segurança
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $birthDay = (int) $request->input('birth_day');
        $birthMonth = (int) $request->input('birth_month');
        $birthYear = (int) $request->input('birth_year');

        if (! checkdate($birthMonth, $birthDay, $birthYear)) {
            throw ValidationException::withMessages([
                'birth_day' => 'Data de nascimento inválida.',
            ]);
        }

        $birthDate = sprintf('%04d-%02d-%02d', $birthYear, $birthMonth, $birthDay);

        /** @var User $user */
        $user = DB::transaction(function () use ($request, $birthDate) {
            $user = User::create([
                'name'     => $request->input('name'),
                'last_name'=> $request->input('last_name'),
                'phone'    => $request->input('phone'),
                'phone_encrypted' => $request->input('phone_digits') ?: $request->input('phone'),
                'cpf'      => $request->input('cpf'),
                'cpf_encrypted' => $request->input('cpf'),
                'birth_date' => $birthDate,
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
                'role'     => User::ROLE_CLIENTE,
                'status'   => 'ativo',
            ]);

            $user->enderecos()->create([
                'apelido' => 'Casa',
                'cep' => $request->input('cep'),
                'logradouro' => $request->input('logradouro'),
                'numero' => $request->input('numero'),
                'complemento' => $request->input('complemento'),
                'bairro' => $request->input('bairro'),
                'cidade' => $request->input('cidade'),
                'estado' => strtoupper((string) $request->input('estado')),
                'principal' => 1,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('cardapio.index');
    }

    private function isValidCpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        $digits = array_map('intval', str_split($cpf));

        $sum1 = 0;
        for ($i = 0, $w = 10; $i < 9; $i++, $w--) {
            $sum1 += $digits[$i] * $w;
        }
        $d1 = ($sum1 * 10) % 11;
        $d1 = $d1 === 10 ? 0 : $d1;

        $sum2 = 0;
        for ($i = 0, $w = 11; $i < 10; $i++, $w--) {
            $sum2 += $digits[$i] * $w;
        }
        $d2 = ($sum2 * 10) % 11;
        $d2 = $d2 === 10 ? 0 : $d2;

        return $d1 === $digits[9] && $d2 === $digits[10];
    }
}