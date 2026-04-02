@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="cardapioManager()">

    <!-- Header & Ações Globais -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 border-b-2 border-brand-500 pb-2 inline-block">Gestão do Cardápio</h1>
            <p class="mt-2 text-sm text-slate-600">Adicione e edite pratos e categorias do seu restaurante.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <button @click="openModal('prato')" class="inline-flex items-center justify-center bg-brand-600 hover:bg-brand-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition-colors text-sm">
                + Novo Prato
            </button>
        </div>
    </div>

    <!-- Filtros de Categoria HTML Tabs (Simplificado) -->
    <div class="flex gap-2 overflow-x-auto pb-2 border-b border-slate-200 hide-scrollbar">
        <button class="px-4 py-2 rounded-t-lg bg-white border-t border-l border-r border-slate-200 font-bold text-brand-600 text-sm whitespace-nowrap">
            Todos
        </button>
        @foreach($categorias as $cat)
            <button class="px-4 py-2 rounded-t-lg text-slate-500 hover:text-slate-800 text-sm font-medium whitespace-nowrap">
                {{ $cat->nome }}
            </button>
        @endforeach
    </div>

    <!-- Grid de Pratos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($pratos as $prato)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col transition-all hover:shadow-md group">
            <div class="relative h-48 w-full bg-slate-100 flex-shrink-0">
                @if($prato->imagem)
                    <img src="{{ asset('storage/' . $prato->imagem) }}" alt="{{ $prato->nome }}" class="absolute inset-0 w-full h-full object-cover">
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-slate-400">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                @endif
                
                <div class="absolute top-3 left-3 flex gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-white text-slate-800 shadow-sm opacity-90">
                        {{ $prato->categoria->nome ?? 'Sem Categoria' }}
                    </span>
                    <button @click="toggleDisponibilidade({{ $prato->id }}, {{ $prato->disponivel ? 'true' : 'false' }})" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none shadow-sm opacity-90" :class="getDisponivelState({{ $prato->id }}, {{ $prato->disponivel ? 'true' : 'false' }}) ? 'bg-emerald-500' : 'bg-slate-300'" role="switch" title="Alterar Disponibilidade">
                        <span aria-hidden="true" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="getDisponivelState({{ $prato->id }}, {{ $prato->disponivel ? 'true' : 'false' }}) ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>
            </div>
            
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="text-lg font-bold text-slate-900 mb-1 leading-tight">{{ $prato->nome }}</h3>
                <p class="text-sm text-slate-500 mb-4 line-clamp-2 flex-1">{{ $prato->descricao }}</p>
                
                <div class="flex items-end justify-between mt-auto">
                    <div>
                        @if($prato->preco_promocional > 0)
                            <div class="text-xs text-slate-400 line-through">R$ {{ number_format($prato->preco, 2, ',', '.') }}</div>
                            <div class="text-lg font-bold text-brand-600">R$ {{ number_format($prato->preco_promocional, 2, ',', '.') }}</div>
                        @else
                            <div class="text-lg font-bold text-brand-600">R$ {{ number_format($prato->preco, 2, ',', '.') }}</div>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <button @click="editPrato({{ Js::from($prato) }})" class="text-slate-400 hover:text-brand-600 p-2 rounded-full hover:bg-brand-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button @click="deletePrato({{ $prato->id }})" class="text-slate-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-500 bg-white border border-slate-200 rounded-xl shadow-sm">
            Nenhum prato cadastrado no restaurante. Adicione o primeiro no botão acima.
        </div>
        @endforelse
    </div>

    <!-- Paginação -->
    <div class="mt-6 flex justify-center">
        {{ $pratos->links() }}
    </div>

    <!-- Modal Form Prato -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="isModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border-t-4 border-brand-500">
                <form @submit.prevent="submitPrato" id="pratoForm">
                    <div class="px-6 py-4 bg-white">
                        <h3 class="text-lg leading-6 font-bold text-slate-900 mb-4" x-text="editingPrato ? 'Editar Prato' : 'Adicionar Prato'"></h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nome do Prato*</label>
                                <input type="text" x-model="formData.nome" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 focus:border-brand-500 focus:ring-brand-500 outline-none transition-all">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Categoria*</label>
                                    <select x-model="formData.categoria_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                                        <option value="">Selecione...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Preço (R$)*</label>
                                    <input type="number" step="0.01" x-model="formData.preco" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Descrição*</label>
                                <textarea x-model="formData.descricao" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Imagem</label>
                                <input type="file" @change="handleFileDrop" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" x-model="formData.disponivel" id="dispToggle" class="rounded border-slate-300 text-brand-600 shadow-sm focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50">
                                <label for="dispToggle" class="text-sm font-medium text-slate-700">Este prato deve aparecer visível no cardápio do cliente</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 px-6 py-4 flex justify-between rounded-b-2xl border-t border-slate-100">
                        <button type="button" @click="closeModal()" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-lg shadow-sm hover:bg-slate-100 transition-colors">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-brand-600 text-white font-bold rounded-lg shadow-sm hover:bg-brand-700 transition-colors" x-text="isLoading ? 'Enviando...' : 'Salvar Prato'" :disabled="isLoading"></button>
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
        formData: {
            nome: '',
            categoria_id: '',
            preco: '',
            descricao: '',
            disponivel: true
        },

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
                if(!response.ok) throw new Error('Falha');
            } catch(e) {
                this.localDisponibilidade[id] = !newState;
                alert('Erro na requisição. Recarregue a página.');
            }
        },

        openModal(type) {
            this.resetForm();
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
        },

        editPrato(prato) {
            this.editingPrato = prato.id;
            this.formData = {
                nome: prato.nome,
                categoria_id: prato.categoria_id,
                preco: prato.preco,
                descricao: prato.descricao,
                disponivel: !!prato.disponivel
            };
            this.isModalOpen = true;
        },

        handleFileDrop(event) {
            this.imageFile = event.target.files[0];
        },

        resetForm() {
            this.editingPrato = null;
            this.formData = { nome: '', categoria_id: '', preco: '', descricao: '', disponivel: true };
            this.imageFile = null;
            if(document.querySelector('input[type="file"]')) {
                document.querySelector('input[type="file"]').value = '';
            }
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
            
            if(this.imageFile) payload.append('imagem', this.imageFile);

            try {
                let response = await fetch(url, { method: 'POST', body: payload, headers: { 'Accept': 'application/json' } });
                
                if(response.ok) { window.location.reload(); }
                else {
                    let err = await response.json();
                    alert(err.message || 'Verifique se os dados estão corretos.');
                }
            } catch(e) {
                alert('Erro grave de comunicação no backend.');
            } finally {
                this.isLoading = false;
            }
        },

        async deletePrato(id) {
            if(!confirm('Exclusão CUIDADO: Este prato será excluido e poderá afetar histórico. Tem certeza?')) return;
            try {
                let response = await fetch(`/admin/cardapio/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
                if(response.ok) { window.location.reload(); }
                else { alert('Não foi possível excluir!'); }
            } catch(e) {}
        }
    }));
});
</script>
<style>
/* Utilities */
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
