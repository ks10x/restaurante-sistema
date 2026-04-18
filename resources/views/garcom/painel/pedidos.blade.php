@extends('layouts.garcom')
@section('title', 'Pedidos Pendentes')
@section('content')

    <!-- Visão Geral -->
    <div class="px-5 mt-6 mb-5 flex justify-between items-center max-w-4xl mx-auto">
        <h2 class="text-xl font-black text-slate-800">Mural de Pedidos</h2>
        <span class="text-xs font-bold bg-slate-200 text-slate-600 px-3 py-1.5 rounded-full flex items-center gap-2">
            <i class="fas fa-sync-alt animate-spin text-slate-400"></i> Auto (60s)
        </span>
    </div>

    <!-- Lista de Pedidos -->
    <div class="px-4 space-y-5 pb-20 max-w-4xl mx-auto">
        @forelse($pedidos as $pedido)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden transition-all hover:shadow-md hover:border-custom-secondary">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">#{{ $pedido->codigo }}</span>
                        
                        @if($pedido->mesa_id)
                            <a href="{{ route('garcom.mesa.show', $pedido->mesa_id) }}" class="text-[11px] font-bold text-white bg-custom-secondary px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1 hover:bg-custom-secondary-dark transition-colors">
                                <i class="fas fa-chair"></i> Mesa {{ $pedido->mesa->numero ?? '' }}
                            </a>
                        @endif
                    </div>
                    
                    @if($pedido->status === 'aguardando_pagamento')
                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 border border-amber-200 px-2 py-1.5 rounded-md uppercase tracking-wider flex items-center gap-1"><i class="fas fa-money-bill-wave"></i> Conta</span>
                    @elseif($pedido->status === 'confirmado' || $pedido->status === 'em_producao')
                        <span class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-100 px-2 py-1.5 rounded-md uppercase tracking-wider flex items-center gap-1"><i class="fas fa-fire"></i> Cozinha</span>
                    @elseif($pedido->status === 'saindo_entrega')
                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2 py-1.5 rounded-md uppercase tracking-wider flex items-center gap-1"><i class="fas fa-motorcycle"></i> Entregando</span>
                    @else
                        <span class="text-[10px] font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2 py-1.5 rounded-md uppercase tracking-wider">{{ str_replace('_', ' ', $pedido->status) }}</span>
                    @endif
                </div>

                <ul class="space-y-3 border-b border-slate-100 pb-4 mb-4">
                    @foreach($pedido->itens as $item)
                        <li class="flex items-start text-[15px]">
                            <span class="font-black text-white bg-custom-secondary rounded-lg w-7 h-7 flex items-center justify-center text-xs mr-3 shrink-0 shadow-sm">{{ $item->quantidade }}x</span>
                            <div>
                                <span class="font-bold text-slate-800">{{ $item->nome_prato }}</span>
                                @if($item->observacao)
                                    <p class="w-full text-xs font-bold text-red-500 mt-1 bg-red-50 border border-red-100 inline-block px-2 py-0.5 rounded gap-1"><i class="fas fa-comment-dots text-[10px]"></i> {{ $item->observacao }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total do Pedido</p>
                        <p class="font-black text-xl text-slate-800">R$ {{ number_format($pedido->total, 2, ',', '.') }}</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 flex items-center gap-1.5 shadow-inner">
                        <i class="far fa-clock"></i> {{ $pedido->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-slate-200 flex flex-col items-center justify-center min-h-[400px]">
                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-[28px] flex items-center justify-center mb-6 shadow-inner border border-slate-100 rotate-3">
                    <i class="fas fa-box-open text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Mural Limpo</h3>
                <p class="text-slate-500 text-base font-medium max-w-sm mx-auto">Nenhum pedido ativo no momento. Acompanhe a chegada de novos pedidos por aqui ou pelo Salão.</p>
                
                <a href="{{ route('garcom.index') }}" class="mt-8 px-6 py-3 bg-custom-secondary text-white font-bold rounded-xl shadow-lg shadow-custom-secondary/30 hover:bg-custom-secondary-dark transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-th-large"></i> Ver Mapa do Salão
                </a>
            </div>
        @endforelse
    </div>

@endsection

@push('scripts')
<script>
    // Refresh automático a cada 60 segundos
    setTimeout(() => {
        window.location.reload();
    }, 60000);
</script>
@endpush
