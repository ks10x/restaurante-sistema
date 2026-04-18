@extends('layouts.garcom')
@section('title', 'Controle de Salão')
@section('content')

    @if(session('success'))
        <div class="mx-5 mt-6 bg-green-100 text-green-800 p-4 rounded-2xl flex items-center gap-3 font-bold text-sm shadow-sm animate-pulse">
            <i class="fas fa-check-circle text-green-600"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Visão Geral -->
    <div class="px-5 mt-6 mb-4 flex justify-between items-center">
        <h2 class="text-lg font-bold text-slate-800">Mapa do Salão</h2>
        <span class="text-xs font-bold bg-slate-200 text-slate-600 px-3 py-1 rounded-full flex items-center gap-2">
            <i class="fas fa-sync-alt animate-spin text-slate-400"></i> Auto (30s)
        </span>
    </div>

    <!-- Grid de Mesas -->
    <div class="px-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 pb-20">
        @foreach($mesas as $mesa)
            @php
                $corFundo = 'bg-white border-slate-200';
                $corTexto = 'text-slate-400';
                $icone = 'fa-chair';
                $statusText = 'Livre';
                $shadow = 'shadow-sm hover:shadow-md';

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

            <a href="{{ route('garcom.mesa.show', $mesa) }}" class="mesa-card block border {{ $corFundo }} rounded-[24px] p-5 text-center {{ $shadow }} relative overflow-hidden group transition-all duration-200 active:scale-95">
                
                @if($mesa->status !== 'livre')
                    <div class="absolute top-0 right-0 w-16 h-16 bg-white/20 rounded-bl-[100px] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                @endif
                
                <h3 class="text-4xl font-extrabold mb-1 {{ $mesa->status === 'livre' ? 'text-slate-800' : $corTexto }}">{{ $mesa->numero }}</h3>
                <div class="text-[10px] font-bold uppercase tracking-wider mb-3 {{ $mesa->status === 'livre' ? 'text-slate-400' : $corTexto }} opacity-90">{{ $statusText }}</div>
                
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center {{ $mesa->status === 'livre' ? 'bg-slate-100 text-slate-300' : 'bg-white/20 '.$corTexto }}">
                    <i class="fas {{ $icone }} text-xl duration-300 group-hover:scale-110"></i>
                </div>
                
                <!-- Bolinha de pedidos ativos -->
                @if($mesa->pedidos->count() > 0)
                    <div class="absolute top-3 left-3 w-3 h-3 bg-white rounded-full flex items-center justify-center animate-pulse shadow-sm">
                        <div class="w-2 h-2 {{ $mesa->status === 'aguardando_pagamento' ? 'bg-amber-600' : 'bg-red-600' }} rounded-full"></div>
                    </div>
                @endif
            </a>
        @endforeach
    </div>

@endsection

@push('scripts')
<script>
    // Refresh automático a cada 30 segundos
    setTimeout(() => {
        window.location.reload();
    }, 30000);
</script>
@endpush
