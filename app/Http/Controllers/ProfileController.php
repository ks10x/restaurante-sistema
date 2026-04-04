<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'endereco' => $user->enderecoAtivo(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('photo')->store('avatars', 'public');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        if (isset($data['cpf'])) {
            $data['cpf_encrypted'] = $data['cpf'];
        }
        if (isset($data['phone'])) {
            $data['phone_encrypted'] = $data['phone'];
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $addressKeys = ['cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'estado'];
        $addressData = collect($data)->only($addressKeys)->toArray();

        if (collect($addressData)->filter(fn ($v) => filled($v))->isNotEmpty()) {
            $cepDigits = preg_replace('/\\D/', '', (string) ($addressData['cep'] ?? ''));
            $addressData['cep'] = $cepDigits;
            $addressData['estado'] = strtoupper((string) ($addressData['estado'] ?? ''));

            $endereco = $user->enderecos()
                ->whereNull('deleted_at')
                ->orderByDesc('principal')
                ->orderByDesc('id')
                ->first();

            if ($endereco) {
                $endereco->update($addressData);
            } else {
                $user->enderecos()->create(array_merge($addressData, [
                    'apelido' => 'Casa',
                    'principal' => 1,
                ]));
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
