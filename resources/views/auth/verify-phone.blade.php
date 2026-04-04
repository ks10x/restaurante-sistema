<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Verifique seu telefone para continuar. (Em ambiente de desenvolvimento, o código é registrado no arquivo de log do Laravel.)
    </div>

    @if ($errors->has('phone'))
        <div class="mb-4 text-sm text-red-600">
            {{ $errors->first('phone') }}
        </div>
    @endif

    @if (session('status') === 'phone-code-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Um novo código foi enviado.
        </div>
    @endif

    @if (session('status') === 'phone-verified')
        <div class="mb-4 font-medium text-sm text-green-600">
            Telefone verificado com sucesso.
        </div>
    @endif

    <div class="mt-4 space-y-4">
        <form method="POST" action="{{ route('phone.verification.send') }}">
            @csrf
            <x-primary-button type="submit">
                Enviar código
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('phone.verification.verify') }}">
            @csrf

            <div>
                <x-input-label for="code" value="Código" />
                <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" autocomplete="one-time-code" required />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <x-primary-button type="submit">
                    Verificar
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Sair
            </button>
        </form>
    </div>
</x-guest-layout>
