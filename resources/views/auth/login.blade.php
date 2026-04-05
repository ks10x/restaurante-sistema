<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar - {{ config('app.name', 'Restaurante') }}</title>
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
    <div class="relative mt-10 w-full max-w-md rounded-[32px] border bg-white p-8 shadow-xl" style="border-color: var(--color-secondary-border);">
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
            <p class="text-sm text-slate-500">Identifique-se para continuar seu pedido.</p>
        </div>

        @if($errors->any())
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-2 block text-xs font-bold uppercase text-slate-500">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="brand-border w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-slate-900 outline-none transition-all">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase text-slate-500">Senha</label>
                <div class="relative">
                    <input id="login_password" type="password" name="password" required class="brand-border w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 pr-12 text-slate-900 outline-none transition-all">
                    <button type="button" data-toggle-password="login_password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-500">
                    <input type="checkbox" name="remember" class="rounded border-slate-300" style="color: var(--color-secondary);">
                    Lembrar-me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm font-semibold brand-text hover:underline">
                    Esqueci minha senha
                </a>
            </div>

            <button type="submit" class="brand-bg mt-4 w-full rounded-2xl py-4 font-bold text-white shadow-lg transition-all">
                Acessar Conta
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            Novo por aqui? <a href="{{ route('register') }}" class="font-bold brand-text hover:underline">Crie sua conta</a>
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
