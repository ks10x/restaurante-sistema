@extends('layouts.admin')

@section('title', 'Gestão de Equipe')

@section('content')
<div class="space-y-8" x-data="funcionarioManager()">
    
    <!-- Header da Seção -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Equipe e Funcionários</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">Controle os níveis de acesso e gerencie as credenciais da sua equipe.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <button @click="openModal('create')" class="bg-slate-900 hover:bg-black text-white font-black px-6 py-3 rounded-2xl shadow-lg shadow-slate-200 transition-all active:scale-95 flex items-center gap-2 text-sm">
                <i class="fas fa-plus-circle"></i>
                Novo Funcionário
            </button>
        </div>
    </div>

    <!-- Tabela de Funcionários -->
    <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-users-cog text-brand-500"></i>
                Membros da Equipe
            </h2>
            <span class="bg-slate-200 text-slate-700 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                {{ $funcionarios->total() }} @choice('Membro|Membros', $funcionarios->total())
            </span>
        </div>

        <div class="overflow-x-auto text-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Nome</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Acesso (E-mail)</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Nível</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Código (PIN)</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($funcionarios as $func)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-black border-2 border-white shadow-sm overflow-hidden">
                                    @if($func->avatar)
                                        <img src="{{ Storage::url($func->avatar) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($func->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 leading-none">
                                        {{ $func->name }}
                                        @if($func->id === auth()->id())
                                            <span class="ml-1 text-[10px] text-brand-500 font-black uppercase">Você</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-tighter">Membro desde {{ $func->created_at->format('M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-600">
                            {{ $func->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $roleData = match((int)$func->role) {
                                    0 => ['bg-indigo-100 text-indigo-700 border-indigo-200', 'Administrador'],
                                    1 => ['bg-amber-100 text-amber-700 border-amber-200', 'Cozinha'],
                                    3 => ['bg-emerald-100 text-emerald-700 border-emerald-200', 'Entregador'],
                                    4 => ['bg-blue-100 text-blue-700 border-blue-200', 'Garçom'],
                                    default => ['bg-slate-100 text-slate-700 border-slate-200', 'Staff']
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 inline-flex text-[10px] font-black uppercase rounded-full border {{ $roleData[0] }}">
                                {{ $roleData[1] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl bg-slate-50 text-slate-800 border border-slate-200 text-xs font-mono font-black tracking-widest shadow-inner">
                                {{ $func->funcionario->codigo_identificacao ?? '----' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-1">
                            <button @click="openInfo({{ Js::from($func) }})" class="p-2 text-slate-300 hover:text-brand-500 transition-all" title="Ver Informações">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button @click="openModal('edit', {{ Js::from($func) }})" class="p-2 text-slate-300 hover:text-brand-500 transition-all" title="Editar Credenciais">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($func->id !== auth()->id())
                            <form action="{{ route('admin.funcionarios.destroy', $func) }}" method="POST" class="inline-block" onsubmit="return adminConfirmSubmit(event, { title: 'Demitir Funcionário', message: 'Deseja realmente excluir permanentemente este acesso?', confirmText: 'Remover Membro' });">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-all" title="Remover Funcionário">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium italic">
                            Nenhum funcionário cadastrado no sistema.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($funcionarios->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $funcionarios->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form (Modernizado) -->
    <div x-show="isModalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
         class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-8 py-7">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 tracking-tight" x-text="mode === 'edit' ? 'Editar Credenciais' : 'Cadastrar Membro'"></h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Gestão de Equipe e PINs</p>
                            </div>
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-brand-500 text-lg">
                                <i :class="mode === 'edit' ? 'fas fa-id-card' : 'fas fa-user-plus'"></i>
                            </div>
                        </div>
                        
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Nome Completo</label>
                                <input type="text" name="name" x-model="formData.name" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all placeholder:text-slate-300" placeholder="Ex: João da Silva">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">E-mail de Acesso</label>
                                <input type="email" name="email" x-model="formData.email" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all placeholder:text-slate-300" placeholder="joao@restaurante.com">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Cargo / Nível de Acesso</label>
                                <div class="relative">
                                    <select name="role" x-model="formData.role" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all appearance-none cursor-pointer">
                                        <option value="4">Garçom (Painel de Mesas)</option>
                                        <option value="1">Cozinha (Gestão de Fila)</option>
                                        <option value="3">Entregador (App Entrega)</option>
                                        <option value="0">Administrador (Total)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-300">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 space-y-4 border-t border-slate-100">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">
                                        Senha de Acesso
                                        <span x-show="mode === 'edit'" class="text-[9px] text-amber-500 font-black italic">(Deixe em branco para não alterar)</span>
                                    </label>
                                    <div class="relative">
                                        <input id="modal_pass" type="password" name="password" :required="mode === 'create'" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all placeholder:text-slate-300" placeholder="Mínimo 8 caracteres">
                                        <button type="button" data-toggle-password="modal_pass" class="absolute inset-y-0 right-4 flex items-center text-slate-300 hover:text-slate-500">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Confirmar Senha</label>
                                    <div class="relative">
                                        <input id="modal_pass_conf" type="password" name="password_confirmation" :required="mode === 'create'" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-slate-900 font-bold focus:border-brand-500 outline-none transition-all placeholder:text-slate-300">
                                        <button type="button" data-toggle-password="modal_pass_conf" class="absolute inset-y-0 right-4 flex items-center text-slate-300 hover:text-slate-500">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50/80 px-8 py-6 flex items-center justify-between border-t border-slate-100">
                        <button type="button" @click="closeModal()" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Voltar</button>
                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-black px-8 py-3.5 rounded-2xl shadow-lg shadow-brand-100 transition-all active:scale-95 text-sm">
                            <span x-text="mode === 'edit' ? 'Salvar Alterações' : 'Cadastrar Membro'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Info (Modernizado) -->
    <div x-show="isInfoOpen" x-transition class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeInfo()"></div>
            
            <div class="relative bg-white rounded-[32px] w-full max-w-sm overflow-hidden shadow-2xl border border-slate-200">
                <div class="h-24 bg-brand-500 relative">
                    <button @click="closeInfo()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-black/20 text-white flex items-center justify-center hover:bg-black/40 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                <div class="px-8 pb-8 -mt-12 relative text-center">
                    <div class="w-24 h-24 rounded-3xl bg-white shadow-xl mx-auto flex items-center justify-center border-4 border-white overflow-hidden mb-4">
                        <template x-if="selectedInfo && selectedInfo.avatar">
                            <img :src="`/storage/${selectedInfo.avatar}`" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!selectedInfo || !selectedInfo.avatar">
                            <span class="text-3xl font-black text-slate-200" x-text="selectedInfo?.name?.charAt(0)"></span>
                        </template>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 tracking-tight" x-text="selectedInfo?.name"></h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="selectedInfo?.email"></p>
                    
                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nível de Sistema</div>
                            <div class="text-xs font-bold text-slate-700" x-text="selectedInfo?.role"></div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Código PIN</div>
                            <div class="text-xs font-black text-brand-600 tracking-widest font-mono" x-text="selectedInfo?.funcionario?.codigo_identificacao ?? 'N/A'"></div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-center gap-2 text-slate-400">
                        <i class="fas fa-calendar-alt text-[10px]"></i>
                        <span class="text-[10px] font-bold uppercase tracking-tight">Membro ativo no sistema</span>
                    </div>

                    <button @click="closeInfo()" class="mt-6 w-full py-4 bg-slate-900 hover:bg-black text-white font-black rounded-2xl text-xs uppercase tracking-widest transition-all">
                        Fechar Perfil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('funcionarioManager', () => ({
        isModalOpen: false,
        isInfoOpen: false,
        mode: 'create',
        formAction: "{{ route('admin.funcionarios.store') }}",
        selectedInfo: null,
        formData: { name: '', email: '', role: '4' },

        openModal(mode, data = null) {
            this.mode = mode;
            if (mode === 'edit' && data) {
                this.formAction = `/admin/funcionarios/${data.id}`;
                this.formData = {
                    name: data.name,
                    email: data.email,
                    role: data.role.toString()
                };
            } else {
                this.formAction = "{{ route('admin.funcionarios.store') }}";
                this.formData = { name: '', email: '', role: '4' };
            }
            this.isModalOpen = true;
        },

        openInfo(data) {
            this.selectedInfo = data;
            this.isInfoOpen = true;
        },

        closeInfo() { this.isInfoOpen = false; this.selectedInfo = null; },
        closeModal() { this.isModalOpen = false; }
    }));
});
</script>
<script>
    document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-toggle-password');
            const input = document.getElementById(id);
            if (!input) return;
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endsection
