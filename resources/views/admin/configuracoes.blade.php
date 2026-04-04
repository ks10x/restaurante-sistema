@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 border-b-2 border-brand-500 pb-2 inline-block">Configurações do Restaurante</h1>
            <p class="mt-2 text-sm text-slate-600">Ajuste taxas, informações públicas e funcionamento.</p>
        </div>
    </div>

    <!-- Aparência do Painel (localStorage) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-1">Aparência do Painel</h2>
        <p class="text-sm text-slate-600">Preferência salva neste navegador (tema + alto contraste).</p>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tema (cor principal)</label>
                <select data-a11y-theme class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    <option value="blue">Azul</option>
                    <option value="green">Verde</option>
                    <option value="yellow">Amarelo</option>
                    <option value="orange">Laranja</option>
                    <option value="red">Vermelho</option>
                    <option value="purple">Roxo</option>
                    <option value="teal">Teal</option>
                    <option value="slate">Slate</option>
                </select>

                <div class="mt-3 flex flex-wrap gap-2">
                    <button type="button" data-a11y-theme-btn="blue" class="h-8 w-8 rounded-full border border-slate-200" style="background:#2563eb" title="Azul"></button>
                    <button type="button" data-a11y-theme-btn="green" class="h-8 w-8 rounded-full border border-slate-200" style="background:#059669" title="Verde"></button>
                    <button type="button" data-a11y-theme-btn="yellow" class="h-8 w-8 rounded-full border border-slate-200" style="background:#d97706" title="Amarelo"></button>
                    <button type="button" data-a11y-theme-btn="orange" class="h-8 w-8 rounded-full border border-slate-200" style="background:#ea580c" title="Laranja"></button>
                    <button type="button" data-a11y-theme-btn="red" class="h-8 w-8 rounded-full border border-slate-200" style="background:#dc2626" title="Vermelho"></button>
                    <button type="button" data-a11y-theme-btn="purple" class="h-8 w-8 rounded-full border border-slate-200" style="background:#9333ea" title="Roxo"></button>
                    <button type="button" data-a11y-theme-btn="teal" class="h-8 w-8 rounded-full border border-slate-200" style="background:#0d9488" title="Teal"></button>
                    <button type="button" data-a11y-theme-btn="slate" class="h-8 w-8 rounded-full border border-slate-200" style="background:#475569" title="Slate"></button>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <div>
                        <div class="text-sm font-bold text-slate-900">Alto contraste</div>
                        <div class="text-xs text-slate-600">Melhora legibilidade</div>
                    </div>
                    <button type="button" onclick="window.A11Y_PREFS?.toggle('contrast')" class="px-3 py-1.5 rounded-lg bg-brand-600 text-white font-bold hover:bg-brand-700 transition">
                        Alternar
                    </button>
                </div>

                <div class="flex items-center justify-between bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <div>
                        <div class="text-sm font-bold text-slate-900">Reduzir animações</div>
                        <div class="text-xs text-slate-600">Remove transições</div>
                    </div>
                    <button type="button" onclick="window.A11Y_PREFS?.toggle('reduce_motion')" class="px-3 py-1.5 rounded-lg bg-brand-600 text-white font-bold hover:bg-brand-700 transition">
                        Alternar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensagens de Alerta (Session) -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-4 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.configuracoes.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden divide-y divide-slate-100">
            
            <!-- Bloco 1: Status de Funcionamento -->
            <div class="p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Status de Funcionamento
                </h2>
                
                <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-lg border border-slate-200" x-data="{ aberto: {{ $config['restaurante_aberto'] == '1' ? 'true' : 'false' }} }">
                    <button type="button" @click="aberto = !aberto" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none" :class="aberto ? 'bg-emerald-500' : 'bg-slate-300'">
                        <input type="checkbox" name="restaurante_aberto" class="hidden" x-model="aberto">
                        <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="aberto ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                    <div>
                        <span class="text-sm font-bold text-slate-900" x-text="aberto ? 'Restaurante Aberto (Recebendo Pedidos)' : 'Restaurante Fechado (Pausado)'"></span>
                        <p class="text-xs text-slate-500">Ao fechar, clientes não poderão finalizar novos pedidos no site.</p>
                    </div>
                </div>
            </div>

            <!-- Bloco 2: Informações Públicas -->
            <div class="p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Informações Públicas
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nome do Restaurante</label>
                        <input type="text" name="restaurante_nome" value="{{ $config['restaurante_nome'] }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">WhatsApp / Telefone de Contato</label>
                        <input type="text" name="restaurante_telefone" value="{{ $config['restaurante_telefone'] }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500" placeholder="(11) 99999-9999">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Aviso / Mensagem no Topo do Cardápio (Opcional)</label>
                        <input type="text" name="mensagem_aviso" value="{{ $config['mensagem_aviso'] }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500" placeholder="Ex: Estamos abertos neste feriado!">
                    </div>
                </div>
            </div>

            <!-- Bloco 3: Regras Operacionais -->
            <div class="p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Valores e Tempo
                </h2>
                <div class="flex gap-6 max-w-2xl">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Taxa de Entrega Padrão (R$)</label>
                        <input type="number" step="0.01" name="taxa_entrega_padrao" value="{{ $config['taxa_entrega_padrao'] }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tempo Estimado (Minutos)</label>
                        <input type="number" name="tempo_estimado_minutos" value="{{ $config['tempo_estimado_minutos'] }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500" placeholder="Ex: 45">
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex justify-end">
                <button type="submit" class="px-8 py-2.5 bg-brand-600 text-white font-bold rounded-lg shadow-sm hover:bg-brand-700 transition">
                    Salvar Configurações
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
