<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Confirme o código do seu autenticador para continuar.
    </div>

    @if (session('warning'))
        <div class="mb-4 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md p-3">
            {{ session('warning') }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <div>
            <x-input-label for="code" value="Código (ou código de recuperação)" />
            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" autofocus required autocomplete="one-time-code" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-between">
            <x-primary-button>
                Confirmar
            </x-primary-button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Sair
        </button>
    </form>
</x-guest-layout>
