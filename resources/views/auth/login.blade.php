<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar — BellaCucina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #1A1512; font-family: 'DM Sans', sans-serif; }
        .amber-text { color: #D4A373; }
        .amber-bg { background-color: #D4A373; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md bg-[#2A2420] rounded-[32px] p-8 border border-[#D4A3731A] shadow-2xl">
        
        <a href="{{ route('cardapio.index') }}" class="inline-flex items-center text-zinc-500 hover:text-[#D4A373] transition-all mb-8 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="text-[10px] font-bold uppercase tracking-widest">Voltar ao Cardápio</span>
        </a>

        <div class="text-center mb-10">
            <h1 class="text-3xl font-serif amber-text mb-2" style="font-family: 'Playfair Display', serif;">BellaCucina</h1>
            <p class="text-zinc-500 text-sm">Identifique-se para continuar seu pedido.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-zinc-400 text-xs font-bold uppercase mb-2">E-mail</label>
                <input type="email" name="email" required class="w-full bg-[#1A1512] border border-zinc-800 rounded-2xl p-4 text-white outline-none focus:border-[#D4A373] transition-all">
            </div>
            <div>
                <label class="block text-zinc-400 text-xs font-bold uppercase mb-2">Senha</label>
                <input type="password" name="password" required class="w-full bg-[#1A1512] border border-zinc-800 rounded-2xl p-4 text-white outline-none focus:border-[#D4A373] transition-all">
            </div>
            <button type="submit" class="w-full amber-bg text-[#1A1512] font-bold py-4 rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-orange-950/20">
                Acessar Conta
            </button>
        </form>

        <p class="text-center mt-8 text-zinc-500 text-sm">
            Novo por aqui? <a href="{{ route('register') }}" class="amber-text font-bold hover:underline">Crie sua conta</a>
        </p>
    </div>
</body>
</html>