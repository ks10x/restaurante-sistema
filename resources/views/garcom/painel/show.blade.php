<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mesa {{ $mesa->numero }} - Garçom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { fontFamily: { sans: ['"DM Sans"', 'sans-serif'] }, }
            }
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f8fafc; }
        .btn-active { transition: all 0.2s; }
        .btn-active:active { transform: scale(0.95); opacity: 0.8; }
    </style>
</head>
<body class="text-slate-800 antialiased pb-32">

    <!-- Header -->
    <header class="bg-indigo-900 text-white p-5 sticky top-0 z-50 shadow-md">
        <div class="flex items-center gap-4">
            <a href="{{ route('garcom.index') }}" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold">Mesa {{ $mesa->numero }}</h1>
                <p class="text-indigo-200 text-xs font-semibold uppercase tracking-wider">
                    @if($mesa->status === 'livre') Livre
                    @elseif($mesa->status === 'ocupada') Ocupada
                    @elseif($mesa->status === 'aguardando_pagamento') Aguardando Pagamento
                    @else {{ ucfirst($mesa->status) }}
                    @endif
                </p>
            </div>
        </div>
    </header>

    <div class="p-5">
        @if($pedidos->isEmpty())
            <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-slate-100 flex flex-col items-center justify-center min-h-[300px]">
                <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-receipt text-4xl"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">Comanda Vazia</h2>
                <p class="text-slate-500 text-sm">Nenhum pedido ativo para esta mesa no momento.</p>
                
                @if($mesa->status !== 'livre')
                    <form action="{{ route('garcom.mesa.fechar', $mesa) }}" method="POST" class="mt-8 w-full">
                        @csrf
                        <button class="w-full bg-white border-2 border-slate-200 text-slate-600 font-bold py-4 rounded-2xl btn-active">
                            Limpar / Liberar Mesa
                        </button>
                    </form>
                @endif
            </div>
        @else
            <!-- Resumo Financeiro Top -->
            <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-3xl p-6 text-white shadow-xl mb-6 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                
                <p class="text-indigo-200 text-sm font-bold uppercase tracking-wider mb-1">Total da Mesa</p>
                <div class="text-4xl font-black tracking-tight mb-4">R$ {{ number_format($totalGeral, 2, ',', '.') }}</div>
                
                @if($mesa->status === 'aguardando_pagamento')
                    <div class="bg-amber-400 text-amber-900 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 w-max shadow-md shadow-amber-400/20">
                        <i class="fas fa-money-bill-wave"></i> O cliente pediu a conta!
                    </div>
                @endif
            </div>

            <!-- Lista de Pedidos -->
            <h3 class="font-bold text-slate-900 mb-4 px-2 text-lg">Pedidos na Comanda</h3>
            
            <div class="space-y-4">
                @foreach($pedidos as $pedido)
                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 relative overflow-hidden">
                        
                        <div class="flex justify-between items-center mb-4 border-b border-slate-50 pb-4">
                            <span class="text-xs font-black text-slate-400 bg-slate-100 px-3 py-1 rounded-full tracking-wider">#{{ $pedido->codigo }}</span>
                            
                            @if($pedido->status === 'confirmado' || $pedido->status === 'em_producao')
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full"><i class="fas fa-fire mr-1"></i> Na Cozinha</span>
                            @elseif($pedido->status === 'saindo_entrega' || $pedido->status === 'entregue')
                                <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full"><i class="fas fa-check-double mr-1"></i> Entregue</span>
                            @endif
                        </div>

                        <ul class="space-y-3 mb-4">
                            @foreach($pedido->itens as $item)
                                <li class="flex justify-between items-start">
                                    <div class="flex gap-3">
                                        <div class="bg-slate-50 w-8 h-8 rounded-lg flex items-center justify-center text-xs font-black text-slate-700 mt-0.5 border border-slate-200 shrink-0">
                                            {{ $item->quantidade }}x
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 leading-tight">{{ $item->nome_prato }}</p>
                                            @if($item->observacao)
                                                <p class="text-xs text-red-500 font-medium mt-1 bg-red-50 px-2 py-1 rounded inline-block"><i class="fas fa-comment-dots"></i> {{ $item->observacao }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="font-bold text-slate-600 mt-1">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</div>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                @endforeach
            </div>
            
            <!-- Barra de Ações Fixa Embaixo -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 pb-safe-area shadow-[0_-10px_40px_rgba(0,0,0,0.05)] z-40">
                <form action="{{ route('garcom.mesa.fechar', $mesa) }}" method="POST" onsubmit="return confirm('Deseja realmente confirmar o pagamento e limpar esta mesa? (Isso dará baixa nos pedidos)')">
                    @csrf
                    <button class="w-full bg-green-500 hover:bg-green-600 active:bg-green-700 text-white text-lg font-black py-5 rounded-2xl shadow-xl shadow-green-500/30 btn-active flex items-center justify-center gap-3">
                        <i class="fas fa-check-circle text-2xl"></i> COBRANÇA REALIZADA
                    </button>
                    <p class="text-center text-[10px] uppercase font-bold text-slate-400 mt-3 tracking-widest leading-tight">Ao clicar, você confirma que o cliente já pagou<br>na maquininha/dinheiro.</p>
                </form>
            </div>
        @endif
    </div>

</body>
</html>
