<x-guest-layout>
    <div class="space-y-4">
        <div class="text-sm text-gray-600">
            Para ativar o 2FA, abra o link no seu celular (ou cadastre manualmente a chave) no app autenticador (Google Authenticator, Microsoft Authenticator, etc.) e confirme com o código gerado.
        </div>

        @if (session('error'))
            <div class="text-sm text-red-700 bg-red-50 border border-red-200 rounded-md p-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-600 break-all">
                Link (otpauth): <a class="underline" href="{{ $otpauthUrl }}">{{ $otpauthUrl }}</a>
            </div>

            <div class="mt-3 text-xs text-gray-500 break-all">
                Chave manual: <span class="font-mono">{{ $secret }}</span>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="text-sm font-semibold text-yellow-900">Códigos de recuperação</div>
            <div class="text-xs text-yellow-800 mt-1">
                Guarde estes códigos em um local seguro. Você poderá usar um deles se perder acesso ao autenticador.
            </div>
            <ul class="mt-3 grid grid-cols-2 gap-2 text-xs font-mono text-yellow-900">
                @foreach($recovery as $code)
                    <li class="bg-white/60 border border-yellow-200 rounded px-2 py-1">{{ $code }}</li>
                @endforeach
            </ul>
        </div>

        <form method="POST" action="{{ route('2fa.confirm') }}">
            @csrf

            <div>
                <x-input-label for="code" value="Código do autenticador" />
                <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" autofocus required autocomplete="one-time-code" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-primary-button>
                    Ativar 2FA
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
