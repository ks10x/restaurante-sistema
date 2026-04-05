<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - {{ config('app.name', 'Restaurante') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>
    @include('layouts.partials.restaurant-theme')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: var(--surface-soft); font-family: 'DM Sans', sans-serif; }
        .brand-text { color: var(--color-secondary); }
        .brand-bg { background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-secondary-dark) 100%); }
        .brand-border:focus { border-color: var(--color-secondary); box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-secondary) 15%, transparent); }
    </style>
</head>
<body class="flex min-h-screen items-center justify-center px-6 py-10">
    <div class="relative mt-10 w-full max-w-lg rounded-[32px] border bg-white p-8 shadow-xl" style="border-color: var(--color-secondary-border);">
        <a href="{{ route('cardapio.index') }}" class="group absolute left-8 top-8 inline-flex items-center text-slate-400 transition-all hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4 transform transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="sr-only">Voltar ao Cardapio</span>
        </a>

        <div class="mt-4 mb-8 text-center">
            @if($restaurantConfig->logo_url)
                <img src="{{ $restaurantConfig->logo_url }}" alt="Logo do restaurante" class="mx-auto mb-4 max-h-16 max-w-[180px] object-contain">
            @endif
            <h1 class="mb-2 text-3xl font-bold brand-text" style="font-family: 'Playfair Display', serif;">{{ config('app.name', 'Restaurante') }}</h1>
            <p class="text-sm text-slate-500">Crie sua conta e aproveite o melhor sabor.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-r-xl border-l-4 border-red-500 bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Ops! Verifique os dados abaixo:</h3>
                        <ul class="mt-1 list-inside list-disc text-xs text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Nome</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="brand-border w-full rounded-2xl border @error('name') border-red-500 @else border-slate-200 @enderror bg-slate-50 p-4 text-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Sobrenome</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="brand-border w-full rounded-2xl border @error('last_name') border-red-500 @else border-slate-200 @enderror bg-slate-50 p-4 text-slate-900 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Telefone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="(11) 90000-0000" class="brand-border w-full rounded-2xl border @error('phone') border-red-500 @else border-slate-200 @enderror bg-slate-50 p-4 text-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf') }}" required placeholder="000.000.000-00" class="brand-border w-full rounded-2xl border @error('cpf') border-red-500 @else border-slate-200 @enderror bg-slate-50 p-4 text-slate-900 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase text-slate-500">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="brand-border w-full rounded-2xl border @error('email') border-red-500 @else border-slate-200 @enderror bg-slate-50 p-4 text-slate-900 outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Senha</label>
                    <div class="relative">
                        <input id="register_password" type="password" name="password" required class="brand-border w-full rounded-2xl border @error('password') border-red-500 @else border-slate-200 @enderror bg-slate-50 p-4 pr-12 text-slate-900 outline-none transition-all">
                        <button type="button" data-toggle-password="register_password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Confirmar Senha</label>
                    <div class="relative">
                        <input id="register_password_confirmation" type="password" name="password_confirmation" required class="brand-border w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 pr-12 text-slate-900 outline-none transition-all">
                        <button type="button" data-toggle-password="register_password_confirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="brand-bg mt-6 w-full rounded-2xl py-4 font-bold text-white shadow-lg transition-all">
                Finalizar Cadastro
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            Ja possui conta? <a href="{{ route('login') }}" class="font-bold brand-text hover:underline">Fazer Login</a>
        </p>
    </div>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-toggle-password');
                const input = document.getElementById(id);
                if (!input) return;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    </script>
</body>
</html>
