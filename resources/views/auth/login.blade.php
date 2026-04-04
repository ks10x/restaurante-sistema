<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar — BellaCucina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #F8FAFC; font-family: 'DM Sans', sans-serif; }
        .navy-text { color: #1E3A8A; }
        .navy-bg { background-color: #1E3A8A; }
        .navy-border:focus { border-color: #1E3A8A; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 px-6">
    <div class="w-full max-w-md bg-white rounded-[32px] p-8 border border-slate-200 shadow-xl relative mt-10">
        
        <a href="{{ route('cardapio.index') }}" class="absolute top-8 left-8 inline-flex items-center text-slate-400 hover:text-blue-900 transition-all group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="sr-only">Voltar ao Cardápio</span>
        </a>

        <div class="text-center mb-8 mt-4">
            <h1 class="text-3xl navy-text mb-2 font-bold" style="font-family: 'Playfair Display', serif;">BellaCucina</h1>
            <p class="text-slate-500 text-sm">Identifique-se para continuar seu pedido.</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-slate-500 text-xs font-bold uppercase mb-2">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
            </div>
            <div>
                <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Senha</label>
                <div class="relative">
                    <input id="login_password" type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 pr-12 text-slate-900 navy-border outline-none transition-all">
                    <button type="button" data-toggle-password="login_password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-500 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-900 focus:ring-blue-900">
                    Lembrar-me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm navy-text font-semibold hover:underline">
                    Esqueci minha senha
                </a>
            </div>

            <button type="submit" class="w-full navy-bg text-white font-bold py-4 rounded-2xl hover:bg-blue-800 transition-all shadow-lg mt-4">
                Acessar Conta
            </button>
        </form>

        <p class="text-center mt-8 text-slate-500 text-sm">
            Novo por aqui? <a href="{{ route('register') }}" class="navy-text font-bold hover:underline">Crie sua conta</a>
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
