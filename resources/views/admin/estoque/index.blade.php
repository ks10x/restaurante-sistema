@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="estoqueManager()">

    <!-- Header & Ações -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 border-b-2 border-brand-500 pb-2 inline-block">Gestão de Estoque</h1>
            <p class="mt-2 text-sm text-slate-600">Monitore níveis de insumos e registre entradas ou saídas.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button @click="openInsumoModal()" class="inline-flex items-center justify-center bg-brand-600 hover:bg-brand-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition-colors text-sm">
                + Novo Ingrediente
            </button>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center">
            <div class="bg-brand-50 text-brand-600 rounded-lg p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total de Insumos</p>
                <p class="text-2xl font-bold text-slate-900">{{ $resumo['total'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center">
            <div class="bg-amber-50 text-amber-600 rounded-lg p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Atenção / Crítico</p>
                <p class="text-2xl font-bold text-slate-900">{{ $resumo['critico'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center">
            <div class="bg-red-50 text-red-600 rounded-lg p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Zerados</p>
                <p class="text-2xl font-bold text-slate-900">{{ $resumo['zerado'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center">
            <div class="bg-emerald-50 text-emerald-600 rounded-lg p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8zm0 0V5m0 10v4m7-7h-4M9 12H5"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Custo em Estoque</p>
                <p class="text-2xl font-bold text-slate-900">R$ {{ number_format($resumo['custo_total'] ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center">
            <div class="bg-orange-50 text-orange-600 rounded-lg p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6 0A10 10 0 11 2 14a10 10 0 0120 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pratos Comprometidos</p>
                <p class="text-2xl font-bold text-slate-900">{{ $resumo['pratos_comprometidos'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Tabela de Insumos -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Insumo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nível / Categoria</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Disponível</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Preço Un.</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($insumos as $insumo)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $insumo->nome }}</div>
                            <div class="text-xs text-slate-500">{{ $insumo->descricao ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            @php
                                $badgeData = match($insumo->nivel ?? 'indefinido') {
                                    'ok'      => ['bg-emerald-100 text-emerald-800 border-emerald-200', '✔ Normal'],
                                    'atencao' => ['bg-amber-100 text-amber-800 border-amber-200',       '⚠ Atenção'],
                                    'critico' => ['bg-orange-100 text-orange-800 border-orange-200',    '⚠ Baixo'],
                                    'zerado'  => ['bg-red-100 text-red-800 border-red-200',             '✕ Zerado'],
                                    default   => ['bg-slate-100 text-slate-800 border-slate-200',       '— Indefinido'],
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 inline-flex text-xs font-semibold rounded-full border {{ $badgeData[0] }} mb-1">
                                {{ $badgeData[1] }}
                            </span>
                            <div class="text-xs text-slate-500">{{ $insumo->categoria ?? 'Geral' }}</div>
                            @if($insumo->pratos->isNotEmpty())
                            <div class="text-[11px] text-slate-400 mt-1">
                                Vinculado a: {{ $insumo->pratos->pluck('nome')->take(2)->join(', ') }}{{ $insumo->pratos->count() > 2 ? ' +' . ($insumo->pratos->count() - 2) : '' }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">
                                {{ rtrim(rtrim((string)$insumo->quantidade_atual, '0'), '.') }} <span class="text-xs font-normal text-slate-500">{{ $insumo->unidade }}</span>
                            </div>
                            <div class="text-xs text-slate-400">Restam {{ $insumo->percentual_estoque ?? 0 }}%</div>
                            @php $largura = min(100, max(0, $insumo->percentual_estoque ?? 0)); @endphp
                            <div class="w-24 h-1.5 bg-slate-200 rounded-full mt-1 overflow-hidden">
                                <div
                                    class="h-full {{ ($insumo->nivel ?? '') == 'ok' ? 'bg-emerald-500' : (($insumo->nivel ?? '') == 'atencao' ? 'bg-amber-500' : (($insumo->nivel ?? '') == 'critico' ? 'bg-orange-500' : 'bg-red-500')) }}"
                                    style="width: {{ $largura }}%;"
                                ></div>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-600">
                            R$ {{ number_format($insumo->preco_unitario, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="openMovimentacao({{ Js::from($insumo) }})" class="text-brand-600 hover:text-brand-900 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-md font-semibold transition-colors">
                                Lançar
                            </button>
                            <button @click="openInsumoModal({{ Js::from($insumo) }})" class="ml-2 text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-md font-semibold transition-colors">
                                Editar
                            </button>
                            <button @click="deleteInsumo({{ $insumo->id }})" class="ml-2 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md font-semibold transition-colors">
                                Excluir
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            Nenhum insumo cadastrado no estoque.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $insumos->links() }}
        </div>
    </div>

    <div x-show="isInsumoModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isInsumoModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="closeInsumoModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="isInsumoModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border-t-4 border-brand-500">
                <form @submit.prevent="submitInsumo">
                    <div class="px-6 py-5 bg-white">
                        <h3 class="text-lg leading-6 font-bold text-slate-900 mb-4" x-text="editingInsumo ? 'Editar Ingrediente' : 'Novo Ingrediente'"></h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nome*</label>
                                <input type="text" x-model="insumoForm.nome" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Categoria</label>
                                <input type="text" x-model="insumoForm.categoria" list="categorias-insumo" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                                <datalist id="categorias-insumo">
                                    @foreach(($categorias ?? []) as $categoria)
                                    <option value="{{ $categoria }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Unidade*</label>
                                <select x-model="insumoForm.unidade" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                    <option value="l">l</option>
                                    <option value="ml">ml</option>
                                    <option value="un">un</option>
                                    <option value="cx">cx</option>
                                    <option value="pct">pct</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Quantidade atual*</label>
                                <input type="number" step="0.001" min="0" x-model="insumoForm.quantidade_atual" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Quantidade mínima*</label>
                                <input type="number" step="0.001" min="0" x-model="insumoForm.quantidade_minima" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Preço unitário*</label>
                                <input type="number" step="0.0001" min="0" x-model="insumoForm.preco_unitario" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Fornecedor</label>
                                <input type="text" x-model="insumoForm.fornecedor" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Código de barras</label>
                                <input type="text" x-model="insumoForm.codigo_barras" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Descrição</label>
                                <textarea rows="3" x-model="insumoForm.descricao" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex justify-between rounded-b-2xl border-t border-slate-100">
                        <button type="button" @click="closeInsumoModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg shadow-sm hover:bg-slate-50 transition-colors">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-brand-600 text-white font-bold rounded-lg shadow-sm hover:bg-brand-700 transition-colors disabled:opacity-50" :disabled="isLoading">
                            <span x-text="isLoading ? 'Salvando...' : 'Salvar ingrediente'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Movimentação AlpineJS -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="isModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border-t-4 border-brand-500">
                <form @submit.prevent="submitMovimentacao">
                    <div class="px-6 py-4 bg-white">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-bold text-slate-900">Movimentar Estoque</h3>
                            <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="mb-4 bg-slate-50 p-3 rounded-lg border border-slate-200 flex justify-between items-center">
                            <div>
                                <div class="text-sm font-bold text-slate-900" x-text="selectedInsumo?.nome"></div>
                                <div class="text-xs text-slate-500">Atual: <span x-text="selectedInsumo?.quantidade_atual"></span> <span x-text="selectedInsumo?.unidade"></span></div>
                            </div>
                            <div class="inline-block px-2 overflow-hidden w-8 text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Tipo de Movimentação*</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="formData.tipo" value="entrada" class="peer sr-only">
                                        <div class="text-center px-2 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-500 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-slate-50 transition-colors">Entrada</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="formData.tipo" value="saida" class="peer sr-only">
                                        <div class="text-center px-2 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-500 peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 hover:bg-slate-50 transition-colors">Saída</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="formData.tipo" value="perda" class="peer sr-only">
                                        <div class="text-center px-2 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-500 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-slate-50 transition-colors">Perda</div>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Quantidade* <span class="text-xs font-normal" x-text="selectedInsumo?.unidade ? `(em ${selectedInsumo.unidade})` : ''"></span></label>
                                <input type="number" step="0.001" x-model="formData.quantidade" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 focus:border-brand-500 focus:ring-brand-500 outline-none transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Observações (Opcional)</label>
                                <input type="text" x-model="formData.observacoes" placeholder="Ex: Quebra, Lote vencido, Compra urgente" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 focus:border-brand-500 focus:ring-brand-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 px-6 py-4 flex justify-between rounded-b-2xl border-t border-slate-100">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg shadow-sm hover:bg-slate-50 transition-colors">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-brand-600 text-white font-bold rounded-lg shadow-sm hover:bg-brand-700 transition-colors disabled:opacity-50" :disabled="isLoading">
                            <span x-text="isLoading ? 'Salvando...' : 'Confirmar'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('estoqueManager', () => ({
        isModalOpen: false,
        isInsumoModalOpen: false,
        selectedInsumo: null,
        editingInsumo: null,
        isLoading: false,
        formData: {
            tipo: 'entrada',
            quantidade: '',
            observacoes: ''
        },
        insumoForm: {
            nome: '',
            descricao: '',
            categoria: '',
            unidade: 'un',
            quantidade_atual: '',
            quantidade_minima: '',
            preco_unitario: '',
            fornecedor: '',
            codigo_barras: '',
            ativo: true
        },

        openInsumoModal(insumo = null) {
            this.editingInsumo = insumo?.id ?? null;
            this.insumoForm = {
                nome: insumo?.nome ?? '',
                descricao: insumo?.descricao ?? '',
                categoria: insumo?.categoria ?? '',
                unidade: insumo?.unidade ?? 'un',
                quantidade_atual: insumo?.quantidade_atual ?? '',
                quantidade_minima: insumo?.quantidade_minima ?? '',
                preco_unitario: insumo?.preco_unitario ?? '',
                fornecedor: insumo?.fornecedor ?? '',
                codigo_barras: insumo?.codigo_barras ?? '',
                ativo: insumo?.ativo ?? true,
            };
            this.isInsumoModalOpen = true;
        },

        closeInsumoModal() {
            this.isInsumoModalOpen = false;
            this.editingInsumo = null;
        },

        openMovimentacao(insumo) {
            this.selectedInsumo = insumo;
            this.formData = { tipo: 'entrada', quantidade: '', observacoes: '' };
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
        },

        async submitInsumo() {
            this.isLoading = true;

            const payload = new FormData();
            payload.append('_token', '{{ csrf_token() }}');
            if (this.editingInsumo) payload.append('_method', 'PUT');

            Object.entries(this.insumoForm).forEach(([key, value]) => {
                payload.append(key, typeof value === 'boolean' ? (value ? 1 : 0) : value);
            });

            const url = this.editingInsumo ? `/admin/estoque/${this.editingInsumo}` : `/admin/estoque`;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: payload
                });

                if (response.ok) {
                    window.location.reload();
                    return;
                }

                const err = await response.json();
                const validationErrors = err.errors
                    ? Object.values(err.errors).flat().join('\n')
                    : null;
                alert(validationErrors || err.message || 'Erro ao salvar ingrediente.');
            } catch (e) {
                alert('Erro de comunicação ao salvar o ingrediente.');
            } finally {
                this.isLoading = false;
            }
        },

        async submitMovimentacao() {
            if(!this.formData.quantidade || this.formData.quantidade <= 0) {
                alert('Quantidade inválida');
                return;
            }

            this.isLoading = true;
            try {
                let response = await fetch(`/admin/estoque/${this.selectedInsumo.id}/movimentar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.formData)
                });

                if(response.ok) {
                    window.location.reload();
                } else {
                    let err = await response.json();
                    const validationErrors = err.errors
                        ? Object.values(err.errors).flat().join('\n')
                        : null;
                    alert(validationErrors || err.message || 'Erro ao registrar movimentação.');
                }
            } catch(e) {
                alert('Erro fatal de comunicação.');
            } finally {
                this.isLoading = false;
            }
        },

        async deleteInsumo(id) {
            const confirmed = await window.adminConfirm({
                title: 'Excluir ingrediente',
                message: 'Tem certeza que deseja excluir esse ingrediente? Essa ação não poderá ser desfeita.',
                confirmText: 'Excluir'
            });

            if (!confirmed) return;

            try {
                const response = await fetch(`/admin/estoque/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                    return;
                }

                const err = await response.json();
                alert(err.message || 'Não foi possível excluir o ingrediente.');
            } catch (e) {
                alert('Erro de comunicação ao excluir o ingrediente.');
            }
        }
    }));
});
</script>
@endsection
