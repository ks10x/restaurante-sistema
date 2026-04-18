@extends('layouts.garcom')
@section('title', 'Mesa ' . $mesa->numero)
@section('content')

    <div class="p-5 pb-8 max-w-4xl mx-auto">
        
        <!-- Botão Voltar e Status Top -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('garcom.index') }}" class="inline-flex items-center gap-2 text-custom-secondary font-bold hover:underline p-2 -ml-2 rounded-lg hover:bg-custom-secondary-soft transition-colors">
                <i class="fas fa-arrow-left"></i> Voltar ao Salão
            </a>
            
            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full border 
                @if($mesa->status === 'livre') bg-slate-100 border-slate-200 text-slate-500
                @elseif($mesa->status === 'ocupada') bg-red-50 border-red-200 text-red-600
                @elseif($mesa->status === 'aguardando_pagamento') bg-amber-50 border-amber-200 text-amber-600
                @else bg-slate-100 border-slate-200 text-slate-600
                @endif
            ">
                @if($mesa->status === 'livre') Livre
                @elseif($mesa->status === 'ocupada') Ocupada
                @elseif($mesa->status === 'aguardando_pagamento') Aguardando Pagamento
                @else {{ ucfirst($mesa->status) }}
                @endif
            </span>
        </div>

        @if($pedidos->isEmpty())
            <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-slate-200 flex flex-col items-center justify-center min-h-[400px]">
                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-5 border-[6px] border-white shadow-sm">
                    <i class="fas fa-receipt text-5xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 mb-2 tracking-tight">Comanda Vazia</h2>
                <p class="text-slate-500 text-base font-medium max-w-xs mx-auto">Nenhum pedido ativo para esta mesa no momento. Você pode voltar ao salão.</p>
                
                @if($mesa->status !== 'livre')
                    <form action="{{ route('garcom.mesa.fechar', $mesa) }}" method="POST" class="mt-8 w-full max-w-xs">
                        @csrf
                        <button class="w-full bg-white border-2 border-slate-200 text-slate-600 font-bold py-4 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95">
                            Forçar Limpeza da Mesa
                        </button>
                    </form>
                @endif
            </div>
        @else
            <!-- Resumo Financeiro Top -->
            <div class="bg-garcom-sidebar rounded-3xl p-8 text-white shadow-xl mb-8 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-black/10 rounded-full blur-xl"></div>
                
                <div class="relative z-10">
                    <p class="text-white/80 text-sm font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar text-white/60 text-lg"></i>
                        Total da Conta
                    </p>
                    <div class="text-5xl font-black tracking-tight mb-5 drop-shadow-md">R$ {{ number_format($totalGeral, 2, ',', '.') }}</div>
                    
                    @if($mesa->status === 'aguardando_pagamento')
                        <div class="bg-amber-400 text-amber-900 px-4 py-2.5 rounded-xl text-sm font-black flex items-center gap-2 w-max shadow-lg shadow-amber-400/20 animate-pulse border border-amber-300">
                            <i class="fas fa-hand-holding-dollar text-lg"></i> O CLIENTE PEDIU A CONTA
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lista de Pedidos -->
            <h3 class="font-bold text-slate-900 mb-4 px-2 text-lg">Pedidos Relacionados</h3>
            
            <div class="space-y-5">
                @foreach($pedidos as $pedido)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 relative overflow-hidden transition-all hover:border-custom-secondary">
                        
                        <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-4">
                            <span class="text-xs font-black text-slate-500 bg-slate-100 px-3.5 py-1.5 rounded-full tracking-wider border border-slate-200">#{{ $pedido->codigo }}</span>
                            
                            @if($pedido->status === 'confirmado' || $pedido->status === 'em_producao')
                                <span class="text-xs font-bold text-amber-600 bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-full"><i class="fas fa-fire mr-1"></i> Na Cozinha</span>
                            @elseif($pedido->status === 'saindo_entrega' || $pedido->status === 'entregue')
                                <span class="text-xs font-bold text-green-600 bg-green-50 border border-green-100 px-3 py-1.5 rounded-full"><i class="fas fa-check-double mr-1"></i> Entregue</span>
                            @endif
                        </div>

                        <ul class="space-y-4 mb-2">
                            @foreach($pedido->itens as $item)
                                <li class="flex justify-between items-start gap-4">
                                    <div class="flex gap-4 flex-1">
                                        <div class="bg-custom-secondary-soft text-custom-secondary w-9 h-9 rounded-[10px] flex items-center justify-center text-sm font-black border border-custom-secondary/20 shadow-sm shrink-0">
                                            {{ $item->quantidade }}x
                                        </div>
                                        <div class="pt-0.5">
                                            <p class="font-bold text-slate-800 text-base leading-tight">{{ $item->nome_prato }}</p>
                                            @if($item->observacao)
                                                <p class="text-[11px] text-red-500 font-bold mt-1.5 bg-red-50 border border-red-100 px-2 py-1 rounded inline-block">
                                                    <i class="fas fa-comment-dots mr-1"></i> {{ $item->observacao }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="font-black text-slate-700 mt-1">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            
            @if(isset($historicoHoje) && $historicoHoje->isNotEmpty())
            <div class="mt-10 mb-2">
                <h3 class="font-bold text-slate-900 mb-4 px-2 text-lg border-b border-slate-200 pb-2">Já Pagos Hoje (Histórico)</h3>
                <div class="space-y-4 opacity-80 backdrop-grayscale-[0.5]">
                    @foreach($historicoHoje as $pedidoHist)
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 relative overflow-hidden">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-black text-slate-400 bg-white px-3 py-1 rounded-full border border-slate-200">#{{ $pedidoHist->codigo }}</span>
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-200 px-3 py-1 rounded-full uppercase tracking-wider"><i class="fas fa-check-double mr-1"></i> Finalizado e Pago</span>
                            </div>
                            <ul class="space-y-2 mb-3">
                                @foreach($pedidoHist->itens as $item)
                                    <li class="flex justify-between items-start gap-4">
                                        <div class="flex gap-3">
                                            <div class="text-slate-500 font-bold text-sm">{{ $item->quantidade }}x</div>
                                            <p class="font-bold text-slate-600 text-sm">{{ $item->nome_prato }}</p>
                                        </div>
                                        <div class="font-bold text-slate-500 text-sm">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="pt-3 border-t border-slate-200 flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-400"><i class="far fa-clock"></i> {{ $pedidoHist->updated_at->format('H:i') }}</span>
                                <span class="font-black text-slate-700 text-sm">R$ {{ number_format($pedidoHist->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="h-8"></div>
            
            <!-- Barra de Ações End -->
            <div class="bg-white rounded-3xl border border-slate-200 p-5 mt-6 shadow-sm">
                <form id="fecharContaForm" action="{{ route('garcom.mesa.fechar', $mesa) }}" method="POST">
                    @csrf
                    <button type="button" onclick="document.getElementById('confirmModal').classList.remove('hidden')" class="w-full bg-green-500 hover:bg-green-600 active:bg-green-700 text-white text-xl font-black py-5 rounded-2xl shadow-xl shadow-green-500/30 transition-all flex items-center justify-center gap-3">
                        <i class="fas fa-check-circle text-2xl"></i> COBRANÇA REALIZADA
                    </button>
                    <p class="text-center text-[11px] uppercase font-bold text-slate-400 mt-4 tracking-widest leading-relaxed">
                        Ao clicar, você confirma que o cliente <br>
                        já efetuou o pagamento presencial
                    </p>
                </form>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <form id="limparMesaForm" action="{{ route('garcom.mesa.limpar', $mesa) }}" method="POST">
                        @csrf
                        <button type="button" onclick="document.getElementById('resetModal').classList.remove('hidden')" class="w-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2 border border-red-100">
                            <i class="fas fa-trash-alt"></i> ZERAR COMANDA / LIMPAR MESA
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de Confirmação de Pagamento -->
    <div id="confirmModal" class="fixed inset-0 z-[80] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="document.getElementById('confirmModal').classList.add('hidden')"></div>
        <div class="relative flex min-h-screen lg:min-h-full items-center justify-center p-4">
            <div class="w-full max-w-sm overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl transition-transform animate-[popup_0.2s_ease-out]">
                <div class="px-6 py-8 text-center bg-white">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100/50 border border-green-200 text-4xl font-bold text-green-500 mb-6 drop-shadow-sm">
                        <i class="fas fa-money-bill-wave animate-pulse"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Confirmar Pagamento</h3>
                    <p class="mt-3 text-[15px] text-slate-500 font-medium leading-relaxed">Você confirma o pagamento da <strong class="text-slate-800 font-black">Mesa {{ $mesa->numero }}</strong> via maquininha ou dinheiro?</p>
                </div>
                <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50 px-6 py-6">
                    <button onclick="document.getElementById('fecharContaForm').submit()" type="button" class="w-full rounded-2xl bg-green-500 hover:bg-green-600 active:bg-green-700 active:scale-95 px-5 py-4 font-black text-white shadow-lg shadow-green-500/30 transition-all text-[15px] flex justify-center items-center gap-2">
                        <i class="fas fa-check text-lg"></i> CONFIRMAR E LIMPAR MESA
                    </button>
                    <button onclick="document.getElementById('confirmModal').classList.add('hidden')" type="button" class="w-full rounded-2xl bg-white border-2 border-slate-200 px-5 py-3 font-bold text-slate-600 shadow-sm transition-all hover:bg-slate-50 active:scale-95 text-[15px]">
                        VOLTAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Zerar Mesa (Negar) -->
    <div id="resetModal" class="fixed inset-0 z-[80] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="document.getElementById('resetModal').classList.add('hidden')"></div>
        <div class="relative flex min-h-screen lg:min-h-full items-center justify-center p-4">
            <div class="w-full max-w-sm overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl transition-transform animate-[popup_0.2s_ease-out]">
                <div class="px-6 py-8 text-center bg-white">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-100/50 border border-red-200 text-4xl font-bold text-red-500 mb-6 drop-shadow-sm">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Limpar Mesa?</h3>
                    <p class="mt-3 text-[15px] text-slate-500 font-medium leading-relaxed">
                        Isso irá <strong class="text-red-600">CANCELAR</strong> todos os pedidos pendentes e expulsar o cliente da sessão atual. 
                        Use apenas se o cliente desistiu ou o pedido for inválido.
                    </p>
                </div>
                <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50 px-6 py-6">
                    <button onclick="document.getElementById('limparMesaForm').submit()" type="button" class="w-full rounded-2xl bg-red-500 hover:bg-red-600 active:bg-red-700 active:scale-95 px-5 py-4 font-black text-white shadow-lg shadow-red-500/30 transition-all text-[15px] flex justify-center items-center gap-2">
                        <i class="fas fa-trash-alt text-lg"></i> SIM, LIMPAR TUDO
                    </button>
                    <button onclick="document.getElementById('resetModal').classList.add('hidden')" type="button" class="w-full rounded-2xl bg-white border-2 border-slate-200 px-5 py-3 font-bold text-slate-600 shadow-sm transition-all hover:bg-slate-50 active:scale-95 text-[15px]">
                        CANCELAR
                    </button>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('styles')
<style>
    @keyframes popup {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endpush
