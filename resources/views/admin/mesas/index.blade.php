@extends('layouts.admin')

@section('title', 'Gestão de Mesas e QR Codes')

@section('content')
<div class="space-y-8">
    <!-- Header da Seção -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Gestão de Mesas</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">Cadastre suas mesas e gere QR Codes para pedidos instantâneos.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Coluna da Esquerda: Listagem de Mesas -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-chair text-brand-500"></i>
                        Mesas Cadastradas
                    </h2>
                    <span class="bg-slate-200 text-slate-700 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                        {{ $mesas->count() }} @choice('Mesa|Mesas', $mesas->count())
                    </span>
                </div>

                <div class="overflow-x-auto">
                    @if($mesas->isEmpty())
                        <div class="py-20 text-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chair text-3xl text-slate-300"></i>
                            </div>
                            <h3 class="text-slate-900 font-bold">Nenhuma mesa ainda</h3>
                            <p class="text-slate-500 text-sm">Use o formulário ao lado para começar.</p>
                        </div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Mesa</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Capacidade</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">QR Code</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($mesas as $mesa)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600 font-black">
                                                {{ $mesa->numero }}
                                            </div>
                                            <span class="font-bold text-slate-800 uppercase text-sm tracking-tight">Mesa {{ $mesa->numero }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 text-slate-600 font-medium">
                                            <i class="fas fa-users text-xs opacity-50"></i>
                                            {{ $mesa->capacidade }} @choice('pessoa|pessoas', $mesa->capacidade)
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = match($mesa->status) {
                                                'livre' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'ocupada' => 'bg-red-100 text-red-700 border-red-200',
                                                'chamando' => 'bg-amber-100 text-amber-700 border-amber-200 animate-pulse',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200'
                                            };
                                            $statusLabel = match($mesa->status) {
                                                'livre' => 'Livre',
                                                'ocupada' => 'Ocupada',
                                                'chamando' => 'Chamando...',
                                                default => 'Aguardando'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.mesas.qr', $mesa) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-50 hover:border-brand-500 transition-all shadow-sm">
                                            <i class="fas fa-qrcode text-brand-500"></i>
                                            Imprimir QR
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form action="{{ route('admin.mesas.destroy', $mesa) }}" method="POST" onsubmit="return adminConfirmSubmit(event, { title: 'Remover Mesa', message: 'Deseja realmente excluir esta mesa? O QR Code atual deixará de funcionar.', confirmText: 'Remover' });">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Formulário de Cadastro -->
        <div class="lg:col-span-4 sticky top-24">
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-brand-500"></i>
                        Nova Mesa
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.mesas.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Identificação / Número</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-slate-300 group-focus-within:text-brand-500 transition-colors"></i>
                                </div>
                                <input type="text" name="numero" placeholder="Ex: 01, VIP, Varanda" class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-900 font-bold placeholder:text-slate-300 placeholder:font-medium focus:border-brand-500 focus:bg-white outline-none transition-all" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Capacidade de Pessoas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-users text-slate-300 group-focus-within:text-brand-500 transition-colors"></i>
                                </div>
                                <input type="number" name="capacidade" value="4" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-900 font-bold focus:border-brand-500 focus:bg-white outline-none transition-all" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-black py-4 rounded-2xl shadow-lg shadow-slate-200 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                            <i class="fas fa-check-circle"></i>
                            Cadastrar Mesa
                        </button>
                    </form>

                    <div class="mt-8 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            <p class="text-[11px] font-bold text-blue-700 leading-relaxed uppercase tracking-tighter">
                                Ao cadastrar uma mesa, o sistema gera automaticamente um hash de segurança único vinculado ao QR Code.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
