<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $cpfDigits = preg_replace('/\D/', '', (string) $this->input('cpf'));
        $cepDigits = preg_replace('/\D/', '', (string) $this->input('cep'));
        $phoneDigits = preg_replace('/\D/', '', (string) $this->input('phone'));
        $estado = strtoupper((string) $this->input('estado'));

        $this->merge([
            'cpf' => $cpfDigits ?: $this->input('cpf'),
            'cep' => $cepDigits ?: $this->input('cep'),
            'phone' => $this->input('phone'),
            'phone_digits' => $phoneDigits,
            'estado' => $estado ?: $this->input('estado'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique(User::class, 'cpf')->ignore($this->user()->id),
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! $this->isValidCpf((string) $value)) {
                        $fail('CPF inválido.');
                    }
                },
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:180',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'address_home' => ['nullable', 'string', 'max:255'],
            'address_work' => ['nullable', 'string', 'max:255'],

            // Endereço (enderecos)
            'cep' => ['nullable', 'string', 'size:8'],
            'logradouro' => ['required_with:cep', 'string', 'max:180'],
            'numero' => ['required_with:cep', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:80'],
            'bairro' => ['required_with:cep', 'string', 'max:80'],
            'cidade' => ['required_with:cep', 'string', 'max:80'],
            'estado' => ['required_with:cep', 'string', 'size:2'],
        ];
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
