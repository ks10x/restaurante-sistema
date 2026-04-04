<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha — BellaCucina</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        <div class="text-center mb-6 mt-4">
            <h1 class="text-3xl navy-text mb-2 font-bold" style="font-family: 'Playfair Display', serif;">BellaCucina</h1>
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mt-6 mb-4">
                <svg class="w-8 h-8 navy-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Crie sua nova senha</h2>
            <p class="text-slate-500 text-sm leading-relaxed">Preencha os campos abaixo para redefinir sua senha de acesso.</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label class="block text-slate-500 text-xs font-bold uppercase mb-2">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
            </div>
            <div>
                <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Nova Senha</label>
                <div class="relative">
                    <input id="reset_password" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 pr-12 text-slate-900 navy-border outline-none transition-all">
                    <button type="button" data-toggle-password="reset_password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Confirmar Nova Senha</label>
                <div class="relative">
                    <input id="reset_password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 pr-12 text-slate-900 navy-border outline-none transition-all">
                    <button type="button" data-toggle-password="reset_password_confirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full navy-bg text-white font-bold py-4 rounded-2xl hover:bg-blue-800 transition-all shadow-lg mt-2">
                Redefinir Senha
            </button>
        </form>

        <p class="text-center mt-8 text-slate-500 text-sm">
            Após a redefinição, você será direcionado ao login.
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
