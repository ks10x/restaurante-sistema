<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Painel do Garçom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"DM Sans"', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f8fafc; overflow-x: hidden; }
        .mesa-card { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .mesa-card:active { transform: scale(0.97); }
    </style>
</head>
<body class="text-slate-800 antialiased pb-24">

    <!-- Header Mobile -->
    <header class="bg-indigo-900 text-white p-5 sticky top-0 z-50 shadow-lg flex justify-between items-center rounded-b-3xl">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight">Mesas</h1>
            <p class="text-indigo-200 text-sm font-medium">Controle de salão</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-indigo-800 px-3 py-1.5 rounded-full text-xs font-bold shadow-inner">
                {{ auth()->user()->name }}
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    @if(session('success'))
        <div class="m-4 bg-green-100 text-green-800 p-4 rounded-2xl flex items-center gap-3 font-bold text-sm shadow-sm animate-pulse">
            <i class="fas fa-check-circle text-green-600"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Visão Geral -->
    <div class="px-5 mt-6 mb-4 flex justify-between items-center">
        <h2 class="text-lg font-bold text-slate-800">Mapa do Salão</h2>
        <span class="text-xs font-bold bg-slate-200 text-slate-600 px-3 py-1 rounded-full">Automático (30s)</span>
    </div>

    <!-- Grid de Mesas -->
    <div class="px-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @foreach($mesas as $mesa)
            @php
                $corFundo = 'bg-white border-slate-200';
                $corTexto = 'text-slate-400';
                $icone = 'fa-chair';
                $statusText = 'Livre';
                $shadow = 'shadow-sm';

                if ($mesa->status === 'ocupada') {
                    $corFundo = 'bg-red-500 border-red-600';
                    $corTexto = 'text-white';
                    $icone = 'fa-utensils';
                    $statusText = 'Ocupada';
                    $shadow = 'shadow-lg shadow-red-500/30';
                } elseif ($mesa->status === 'aguardando_pagamento') {
                    $corFundo = 'bg-amber-400 border-amber-500';
                    $corTexto = 'text-amber-900';
                    $icone = 'fa-hand-holding-dollar animate-bounce';
                    $statusText = 'Conta pedida!';
                    $shadow = 'shadow-lg shadow-amber-400/50';
                } elseif ($mesa->status === 'chamando') {
                    $corFundo = 'bg-blue-500 border-blue-600';
                    $corTexto = 'text-white';
                    $icone = 'fa-bell animate-wiggle';
                    $statusText = 'Chamando';
                    $shadow = 'shadow-lg shadow-blue-500/30';
                }
            @endphp

            <a href="{{ route('garcom.mesa.show', $mesa) }}" class="mesa-card block border {{ $corFundo }} rounded-[24px] p-5 text-center {{ $shadow }} relative overflow-hidden group">
                
                @if($mesa->status !== 'livre')
                    <div class="absolute top-0 right-0 w-16 h-16 bg-white/20 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                @endif
                
                <h3 class="text-4xl font-extrabold mb-1 {{ $mesa->status === 'livre' ? 'text-slate-800' : $corTexto }}">{{ $mesa->numero }}</h3>
                <div class="text-[10px] font-bold uppercase tracking-wider mb-3 {{ $mesa->status === 'livre' ? 'text-slate-400' : $corTexto }} opacity-90">{{ $statusText }}</div>
                
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $mesa->status === 'livre' ? 'bg-slate-100 text-slate-300' : 'bg-white/20 '.$corTexto }}">
                    <i class="fas {{ $icone }} text-xl"></i>
                </div>
                
                <!-- Bolinha de pedidos ativos -->
                @if($mesa->pedidos->count() > 0)
                    <div class="absolute top-3 left-3 w-3 h-3 bg-white rounded-full flex items-center justify-center animate-pulse">
                        <div class="w-2 h-2 {{ $mesa->status === 'aguardando_pagamento' ? 'bg-amber-600' : 'bg-red-600' }} rounded-full"></div>
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    <!-- Navegação Inferior Móvel -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] pb-safe-area">
        <div class="flex justify-around items-center h-16 max-w-md mx-auto">
            <a href="{{ route('garcom.index') }}" class="flex flex-col items-center justify-center w-full h-full text-indigo-600">
                <i class="fas fa-th-large text-xl mb-1"></i>
                <span class="text-[10px] font-bold">Salão</span>
            </a>
            <!-- Botão central flutuante para update -->
            <div class="relative w-full flex justify-center -mt-6">
                <button onclick="location.reload()" class="w-14 h-14 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-xl shadow-indigo-600/40 border-4 border-[#f8fafc] text-xl active:scale-90 transition-transform">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <a href="{{ route('garcom.index') }}" class="flex flex-col items-center justify-center w-full h-full text-slate-400 hover:text-indigo-600">
                <i class="fas fa-clipboard-list text-xl mb-1"></i>
                <span class="text-[10px] font-bold">Pedidos</span>
            </a>
        </div>
    </div>

    <script>
        // Refresh automático a cada 30 segundos
        setTimeout(() => {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
