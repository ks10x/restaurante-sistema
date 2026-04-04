<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro — BellaCucina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #F8FAFC; }
        .navy-text { color: #1E3A8A; }
        .navy-bg { background-color: #1E3A8A; }
        .navy-border:focus { border-color: #1E3A8A; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 px-6">
    <div class="w-full max-w-lg bg-white rounded-[32px] p-8 border border-slate-200 shadow-xl relative mt-10">
        
        <a href="{{ route('cardapio.index') }}" class="absolute top-8 left-8 inline-flex items-center text-slate-400 hover:text-blue-900 transition-all group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="sr-only">Voltar ao Cardápio</span>
        </a>

        <div class="text-center mb-8 mt-4">
            <h1 class="text-3xl font-serif navy-text mb-2 font-bold">BellaCucina</h1>
            <p class="text-slate-500 text-sm">Crie sua conta e aproveite o melhor sabor.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Ops! Verifique os dados abaixo:</h3>
                        <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
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
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Nome</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border @error('name') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
                </div>
                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Sobrenome</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full bg-slate-50 border @error('last_name') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Telefone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="(11) 90000-0000" class="w-full bg-slate-50 border @error('phone') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
                </div>
                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf') }}" required placeholder="000.000.000-00" class="w-full bg-slate-50 border @error('cpf') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-slate-500 text-xs font-bold uppercase mb-2">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 text-slate-900 navy-border outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Senha</label>
                    <div class="relative">
                        <input id="register_password" type="password" name="password" required class="w-full bg-slate-50 border @error('password') border-red-500 @else border-slate-200 @enderror rounded-2xl p-4 pr-12 text-slate-900 navy-border outline-none transition-all">
                        <button type="button" data-toggle-password="register_password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-slate-500 text-xs font-bold uppercase mb-2">Confirmar Senha</label>
                    <div class="relative">
                        <input id="register_password_confirmation" type="password" name="password_confirmation" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 pr-12 text-slate-900 navy-border outline-none transition-all">
                        <button type="button" data-toggle-password="register_password_confirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full navy-bg text-white font-bold py-4 rounded-2xl hover:bg-blue-800 transition-all shadow-lg mt-6">
                Finalizar Cadastro
            </button>
        </form>

        <p class="text-center mt-8 text-slate-500 text-sm">
            Já possui conta? <a href="{{ route('login') }}" class="navy-text font-bold hover:underline">Fazer Login</a>
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
