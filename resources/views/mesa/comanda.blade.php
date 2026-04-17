@extends('layouts.store')

@section('title', 'Comanda Mesa ' . $mesa->numero)

@section('content')
<div class="container py-8">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="bg-indigo-900 text-white p-6 text-center">
            <h1 class="text-3xl font-bold font-serif mb-2">Comanda da Mesa {{ $mesa->numero }}</h1>
            <p class="text-indigo-200 text-sm">Resumo dos seus pedidos até o momento.</p>
        </div>

        <div class="p-6">
            @if($mesa->pedidos->isEmpty())
                <div class="text-center text-slate-500 py-8">
                    <i class="fas fa-receipt text-4xl mb-4 text-slate-300"></i>
                    <p>Sua comanda ainda está vazia.</p>
                    <a href="{{ route('cardapio.index') }}" class="btn-primary mt-4 inline-block">Fazer um Pedido</a>
                </div>
            @else
                @php $totalGeral = 0; @endphp
                
                <div class="space-y-6">
                    @foreach($mesa->pedidos as $pedido)
                        @php $totalGeral += $pedido->total; @endphp
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Pedido #{{ $pedido->codigo }}
                                </span>
                                <span class="badge bg-{{ $pedido->statusCor }} text-xs">
                                    {{ $pedido->statusLabel }}
                                </span>
                            </div>

                            <ul class="divide-y divide-slate-200">
                                @foreach($pedido->itens as $item)
                                    <li class="py-2 flex justify-between text-sm">
                                        <div>
                                            <span class="font-bold border border-slate-300 px-1.5 py-0.5 rounded text-xs mr-2">{{ $item->quantidade }}x</span>
                                            <span class="text-slate-800">{{ $item->insumo->nome }}</span>
                                        </div>
                                        <div class="text-slate-600 font-medium">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3 pt-3 border-t border-slate-200 flex justify-between items-center font-bold text-slate-900">
                                <span>Total do Pedido</span>
                                <span>R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t-2 border-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-bold text-slate-700">Total da Mesa</span>
                        <span class="text-2xl font-bold text-green-600">R$ {{ number_format($totalGeral, 2, ',', '.') }}</span>
                    </div>

                    @if($mesa->status === 'aguardando_pagamento')
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-4 text-center">
                            <i class="fas fa-concierge-bell text-2xl mb-2 text-yellow-600"></i>
                            <p class="font-bold">Garçom a caminho!</p>
                            <p class="text-sm">Por favor, aguarde em sua mesa. O garçom está trazendo a maquininha para o pagamento.</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('mesa.fechar') }}">
                            @csrf
                            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl text-lg flex items-center justify-center gap-2 transition-transform transform active:scale-95">
                                <i class="fas fa-money-bill-wave"></i> Pedir a Conta
                            </button>
                            <p class="text-center text-xs text-slate-500 mt-3">O pagamento é feito diretamente na mesa com o garçom.</p>
                        </form>
                    @endif
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('cardapio.index') }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm">
                        <i class="fas fa-plus-circle"></i> Fazer novo pedido nesta mesa
                    </a>
                </div>

            @endif
        </div>
    </div>
</div>
@endsection
