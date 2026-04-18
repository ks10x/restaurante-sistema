@extends('layouts.admin')

@section('title', 'Gestão de Cardápio')

@section('content')
<div class="space-y-8" x-data="cardapioManager()">

    <!-- Header & Ações Globais -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Gestão do Cardápio</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">Controle total sobre seus pratos, categorias e insumos.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <button @click="openModal('prato')" class="bg-slate-900 hover:bg-black text-white font-black px-6 py-3 rounded-2xl shadow-lg shadow-slate-200 transition-all active:scale-95 flex items-center gap-2 text-sm">
                <i class="fas fa-plus-circle"></i>
                Novo Prato
            </button>
        </div>
    </div>

    <!-- Filtros de Categoria (Tabs Premium) -->
    @php($categoriaAtual = request('categoria'))
    <div class="flex items-center gap-2 overflow-x-auto pb-4 hide-scrollbar">
        <a href="{{ route('admin.cardapio.index') }}" 
           class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all {{ blank($categoriaAtual) ? 'bg-brand-500 text-white shadow-md shadow-brand-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600' }}">
            Todos
        </a>
        @foreach($categorias as $cat)
            <a href="{{ route('admin.cardapio.index', ['categoria' => $cat->id]) }}" 
               class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all {{ (string) $categoriaAtual === (string) $cat->id ? 'bg-brand-500 text-white shadow-md shadow-brand-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600' }}">
                {{ $cat->nome }}
            </a>
        @endforeach
    </div>

    <!-- Grid de Pratos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($pratos as $prato)
        <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden flex flex-col transition-all hover:shadow-xl hover:-translate-y-1 group">
            <div class="relative h-48 w-full bg-slate-50 flex-shrink-0">
                @if($prato->imagem)
                    <img src="{{ asset('storage/' . $prato->imagem) }}" alt="{{ $prato->nome }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-slate-200">
                        <i class="fas fa-utensils text-4xl"></i>
                    </div>
                @endif
                
                <div class="absolute top-3 left-3 flex gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase bg-white/90 text-slate-800 backdrop-blur-sm border border-white shadow-sm">
                        {{ $prato->categoria->nome ?? 'S/ Cat' }}
                    </span>
                    <button @click="toggleDisponibilidade({{ $prato->id }}, {{ $prato->disponivel ? 'true' : 'false' }})" 
                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none shadow-sm" 
                            :class="getDisponivelState({{ $prato->id }}, {{ $prato->disponivel ? 'true' : 'false' }}) ? 'bg-emerald-500' : 'bg-slate-300'" role="switch">
                        <span aria-hidden="true" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="getDisponivelState({{ $prato->id }}, {{ $prato->disponivel ? 'true' : 'false' }}) ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-base font-black text-slate-900 leading-tight mb-2 uppercase tracking-tight">{{ $prato->nome }}</h3>
                    <p class="text-xs text-slate-500 line-clamp-2 font-medium leading-relaxed">{{ $prato->descricao }}</p>
                </div>

                @if($prato->insumos->isNotEmpty())
                <div class="mb-5 flex flex-wrap gap-1.5">
                    @foreach($prato->insumos->take(3) as $insumo)
                    <span class="inline-flex items-center rounded-lg bg-slate-50 border border-slate-100 px-2 py-1 text-[9px] font-black text-slate-400 uppercase tracking-tighter">
                        {{ $insumo->nome }}
                    </span>
                    @endforeach
                    @if($prato->insumos->count() > 3)
                    <span class="inline-flex iitems-center rounded-lg bg-slate-50 border border-slate-100 px-2 py-1 text-[9px] font-black text-slate-300 uppercase">+{{ $prato->insumos->count() - 3 }}</span>
                    @endif
                </div>
                @endif
                
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-50">
                    <div class="flex flex-col">
                        @if($prato->preco_promocional > 0)
                            <span class="text-[10px] text-slate-300 line-through font-bold">R$ {{ number_format($prato->preco, 2, ',', '.') }}</span>
                            <span class="text-lg font-black text-brand-600 tracking-tighter">R$ {{ number_format($prato->preco_promocional, 2, ',', '.') }}</span>
                        @else
                            <span class="text-lg font-black text-brand-600 tracking-tighter">R$ {{ number_format($prato->preco, 2, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="flex gap-1">
                        <button @click="editPrato({{ Js::from($prato) }})" class="w-8 h-8 flex items-center justify-center text-slate-300 hover:text-brand-500 hover:bg-brand-50 rounded-xl transition-all">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <button @click="deletePrato({{ $prato->id }})" class="w-8 h-8 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-utensils text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-slate-900 font-bold uppercase tracking-widest text-sm">Cardápio Vazio</h3>
            <p class="text-slate-500 text-xs font-medium mt-1">Comece adicionando seu primeiro prato.</p>
        </div>
        @endforelse
    </div>

    <!-- Paginação -->
    @if($pratos->hasPages())
    <div class="mt-10">
        {{ $pratos->appends(request()->query())->links() }}
    </div>
    @endif

    <!-- Modal Form Prato (Modernizado) -->
    <div x-show="isModalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-12">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal()"></div>
            
            <div class="inline-block align-bottom bg-white rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-xl sm:w-full border border-slate-200">
                <form @submit.prevent="submitPrato" id="pratoForm">
                    <div class="px-8 py-7 bg-white">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 tracking-tight" x-text="editingPrato ? 'Editar Prato' : 'Novo Prato'"></h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Cardápio • Preços • Insumos</p>
                            </div>
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-brand-500">
                                <i class="fas fa-utensils"></i>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome do Prato / Produto</label>
                                <input type="text" x-model="formData.nome" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all placeholder:text-slate-300" placeholder="Ex: Risoto de Camarão">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Categoria</label>
                                    <select x-model="formData.categoria_id" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all appearance-none cursor-pointer">
                                        <option value="">Escolher...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }} text-bold">{{ $cat->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Preço Venda (R$)</label>
                                    <input type="number" step="0.01" x-model="formData.preco" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Descrição Comercial</label>
                                <textarea x-model="formData.descricao" rows="2" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all placeholder:text-slate-300" placeholder="Conte o que torna este prato especial..."></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Imagem do Prato</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-100 rounded-2xl border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <input type="file" @change="handleFileDrop" accept="image/*" class="flex-1 text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-black cursor-pointer">
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-50">
                                <div class="flex items-center justify-between mb-4">
                                    <label class="text-xs font-black uppercase text-slate-900">Ingredientes (Ficha Técnica)</label>
                                    <button type="button" @click="addInsumoRow()" class="px-3 py-1.5 bg-brand-50 text-brand-700 text-[10px] font-black uppercase rounded-lg border border-brand-100 hover:bg-brand-100 transition-colors">
                                        + Adicionar
                                    </button>
                                </div>

                                <div class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                    <template x-if="formData.insumos.length === 0">
                                        <div class="p-5 border-2 border-dashed border-slate-100 rounded-2xl text-center">
                                            <p class="text-[10px] text-slate-300 font-bold uppercase">Sem insumos vinculados</p>
                                        </div>
                                    </template>
                                    
                                    <template x-for="(insumo, index) in formData.insumos" :key="`insumo-${index}`">
                                        <div class="flex items-center gap-2 group">
                                            <div class="flex-1 grid grid-cols-2 gap-2">
                                                <select x-model="insumo.id" class="bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 text-xs font-bold text-slate-700">
                                                    <option value="">Insumo</option>
                                                    @foreach($insumos as $insumo)
                                                        <option value="{{ $insumo->id }}">{{ $insumo->nome }} ({{ $insumo->unidade }})</option>
                                                    @endforeach
                                                </select>
                                                <input type="number" step="0.001" x-model="insumo.quantidade" placeholder="Qtd" class="bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 text-xs font-bold text-slate-700">
                                            </div>
                                            <button type="button" @click="removeInsumoRow(index)" class="w-8 h-8 flex items-center justify-center text-slate-300 hover:text-red-500 transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                                <input type="checkbox" x-model="formData.disponivel" id="dispToggle" class="w-5 h-5 rounded-lg border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                <label for="dispToggle" class="text-xs font-bold text-emerald-800 uppercase tracking-tight cursor-pointer">Visível no cardápio digital do cliente</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50/80 px-8 py-6 flex items-center justify-between border-t border-slate-100">
                        <button type="button" @click="closeModal()" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancelar</button>
                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-black px-8 py-3.5 rounded-2xl shadow-lg shadow-brand-100 transition-all active:scale-95 text-sm" :disabled="isLoading">
                            <span x-text="isLoading ? 'Enviando...' : (editingPrato ? 'Salvar Alterações' : 'Cadastrar Prato')"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cardapioManager', () => ({
        isModalOpen: false,
        editingPrato: null,
        localDisponibilidade: {},
        isLoading: false,
        imageFile: null,
        formData: { nome: '', categoria_id: '', preco: '', descricao: '', disponivel: true, insumos: [] },

        getDisponivelState(id, serverState) {
            return this.localDisponibilidade[id] !== undefined ? this.localDisponibilidade[id] : serverState;
        },

        async toggleDisponibilidade(id, currentState) {
            const newState = !(this.getDisponivelState(id, currentState));
            this.localDisponibilidade[id] = newState;
            try {
                let response = await fetch(`/admin/cardapio/${id}/toggle`, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if(!response.ok) throw new Error();
            } catch(e) {
                this.localDisponibilidade[id] = !newState;
                alert('Falha ao atualizar disponibilidade.');
            }
        },

        openModal(type) { this.resetForm(); this.isModalOpen = true; },
        closeModal() { this.isModalOpen = false; },

        editPrato(prato) {
            this.editingPrato = prato.id;
            this.formData = {
                nome: prato.nome,
                categoria_id: prato.categoria_id,
                preco: prato.preco,
                descricao: prato.descricao,
                disponivel: !!prato.disponivel,
                insumos: (prato.insumos || []).map((insumo) => ({
                    id: String(insumo.id),
                    quantidade: insumo.pivot?.quantidade ?? ''
                }))
            };
            this.isModalOpen = true;
        },

        addInsumoRow() { this.formData.insumos.push({ id: '', quantidade: '' }); },
        removeInsumoRow(index) { this.formData.insumos.splice(index, 1); },
        handleFileDrop(event) { this.imageFile = event.target.files[0]; },
        
        resetForm() {
            this.editingPrato = null;
            this.formData = { nome: '', categoria_id: '', preco: '', descricao: '', disponivel: true, insumos: [] };
            this.imageFile = null;
            if(document.querySelector('input[type="file"]')) document.querySelector('input[type="file"]').value = '';
        },

        async submitPrato() {
            this.isLoading = true;
            let url = this.editingPrato ? `/admin/cardapio/${this.editingPrato}` : `/admin/cardapio`;
            let payload = new FormData();
            payload.append('_token', '{{ csrf_token() }}');
            if(this.editingPrato) payload.append('_method', 'PUT');
            payload.append('nome', this.formData.nome);
            payload.append('categoria_id', this.formData.categoria_id);
            payload.append('preco', this.formData.preco);
            payload.append('descricao', this.formData.descricao);
            payload.append('disponivel', this.formData.disponivel ? 1 : 0);
            this.formData.insumos.filter(i => i.id && i.quantidade).forEach((insumo, idx) => {
                payload.append(`insumos[${idx}][id]`, insumo.id);
                payload.append(`insumos[${idx}][quantidade]`, insumo.quantidade);
            });
            if(this.imageFile) payload.append('imagem', this.imageFile);

            try {
                let response = await fetch(url, { method: 'POST', body: payload, headers: { 'Accept': 'application/json' } });
                if(response.ok) { window.location.reload(); }
                else {
                    let err = await response.json();
                    alert(err.message || 'Erro ao processar requisição.');
                }
            } catch(e) {
                alert('Erro de conexão.');
            } finally { this.isLoading = false; }
        },

        async deletePrato(id) {
            const confirmed = await window.adminConfirm({
                title: 'Excluir prato',
                message: 'Tem certeza que deseja remover este item? Esta ação é irreversível.',
                confirmText: 'Sim, Excluir'
            });
            if(!confirmed) return;
            try {
                let resp = await fetch(`/admin/cardapio/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
                if(resp.ok) window.location.reload();
            } catch(e) {}
        }
    }));
});
</script>
<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endsection
