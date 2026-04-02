@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="funcionarioManager()">

    <!-- Header & Ações -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 border-b-2 border-brand-500 pb-2 inline-block">Equipe e Funcionários</h1>
            <p class="mt-2 text-sm text-slate-600">Gerencie o acesso da sua equipe ao sistema.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <button @click="openModal('create')" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-md transition-all active:scale-95 text-sm ring-2 ring-blue-500 ring-offset-2">
                + Novo Funcionário
            </button>
        </div>
    </div>

    <!-- Tabela de Funcionários -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cargo / Nível</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Código (PIN)</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Data de Cadastro</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($funcionarios as $func)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $func->name }}</div>
                            @if($func->id === auth()->id())
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Você</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $func->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $roleData = match((int)$func->role) {
                                    0 => ['bg-indigo-100 text-indigo-800 border-indigo-200', 'Administrador'],
                                    1 => ['bg-amber-100 text-amber-800 border-amber-200', 'Cozinha'],
                                    3 => ['bg-emerald-100 text-emerald-800 border-emerald-200', 'Entregador'],
                                    default => ['bg-slate-100 text-slate-800 border-slate-200', 'Desconhecido']
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 inline-flex text-xs font-semibold rounded-full border {{ $roleData[0] }}">
                                {{ $roleData[1] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-800 border border-slate-200 text-xs font-mono font-bold">
                                {{ $func->funcionario->codigo_identificacao ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $func->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button @click="openModal('edit', {{ Js::from($func) }})" class="text-slate-400 hover:text-brand-600 transition-colors px-2" title="Editar Funcionário">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            @if($func->id !== auth()->id())
                            <form action="{{ route('admin.funcionarios.destroy', $func) }}" method="POST" class="inline-block" onsubmit="return adminConfirmSubmit(event, { title: 'Excluir funcionário', message: 'Tem certeza que deseja excluir permanentemente este funcionário?', confirmText: 'Excluir' });">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors px-2" title="Remover Funcionário">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Nenhum funcionário cadastrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $funcionarios->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="isModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-4 border-brand-500">
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-6 py-5 bg-white space-y-4">
                        <h3 class="text-lg leading-6 font-bold text-slate-900 mb-4" x-text="mode === 'edit' ? 'Editar Funcionário' : 'Novo Funcionário'"></h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nome Completo</label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Endereço de E-mail</label>
                            <input type="email" name="email" x-model="formData.email" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Cargo / Nível de Acesso</label>
                            <select name="role" x-model="formData.role" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                                <option value="0">Administrador (Total)</option>
                                <option value="1">Cozinha (Gestão de Fila)</option>
                                <option value="3">Entregador</option>
                            </select>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">
                                Senha de Acesso
                                <span x-show="mode === 'edit'" class="text-xs text-orange-600 font-normal ml-1">(Deixe em branco para manter a atual)</span>
                            </label>
                            <input type="password" name="password" :required="mode === 'create'" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500 mb-3" placeholder="Mínimo 8 caracteres">
                            
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" :required="mode === 'create'" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-brand-500">
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 px-6 py-4 flex justify-between rounded-b-2xl border-t border-slate-100">
                        <button type="button" @click="closeModal()" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-lg shadow-sm hover:bg-slate-100">Cancelar</button>
                        <button type="submit" class="px-6 py-2 bg-brand-600 text-white font-bold rounded-lg shadow-sm hover:bg-brand-700">
                            <span x-text="mode === 'edit' ? 'Salvar Edição' : 'Cadastrar'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('funcionarioManager', () => ({
        isModalOpen: false,
        mode: 'create', // create | edit
        formAction: "{{ route('admin.funcionarios.store') }}",
        formData: {
            name: '',
            email: '',
            role: '1'
        },

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
                this.formData = { name: '', email: '', role: '1' };
            }
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
        }
    }));
});
</script>
@endsection
