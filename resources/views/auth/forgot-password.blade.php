<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Esqueci Minha Senha — BellaCucina</title>
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
        
        <a href="{{ route('login') }}" class="absolute top-8 left-8 inline-flex items-center text-slate-400 hover:text-blue-900 transition-all group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="sr-only">Voltar ao Login</span>
        </a>

        <div class="text-center mb-6 mt-4">
            <h1 class="text-3xl navy-text mb-2 font-bold" style="font-family: 'Playfair Display', serif;">BellaCucina</h1>
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mt-6 mb-4">
                <svg class="w-8 h-8 navy-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Esqueceu sua senha?</h2>
            <p class="text-slate-500 text-sm leading-relaxed">Sem problema! Informe seu e-mail abaixo e enviaremos um link para você criar uma nova senha.</p>
        </div>

        @if(session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl mb-4 text-sm text-center">
            <div class="flex items-center justify-center gap-2 mb-1">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <span class="font-bold">Link enviado!</span>
            </div>
            <p>Verifique sua caixa de entrada e spam. Enviamos o link de redefinição para o e-mail informado.</p>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Seu endereço de e-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="exemplo@email.com" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
            </div>

            <button type="submit" class="w-full navy-bg text-white font-bold py-4 rounded-2xl hover:bg-blue-800 transition-all shadow-lg mt-2">
                Enviar Link de Redefinição
            </button>
        </form>

        <p class="text-center mt-8 text-slate-500 text-sm">
            Lembrou a senha? <a href="{{ route('login') }}" class="navy-text font-bold hover:underline">Voltar ao Login</a>
        </p>
    </div>
</body>
</html>
