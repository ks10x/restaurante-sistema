@extends('layouts.admin')

@section('content')
@if(isset($openCliente) && $openCliente)
<script>
    window.__openCliente = {!! Js::from($openCliente) !!};
</script>
@endif
<div class="space-y-6" x-data="clienteManager()" x-init="init()">

    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 border-b-2 border-brand-500 pb-2 inline-block">Clientes</h1>
            <p class="mt-2 text-sm text-slate-600">Visualize clientes cadastrados e bloqueie/desbloqueie acesso.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Contato</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cadastro</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($clientes as $cli)
                    @php($isBlocked = ($cli->status ?? 'ativo') !== 'ativo')
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $cli->name }} {{ $cli->last_name }}</div>
                            <div class="text-xs text-slate-500">ID #{{ $cli->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <div>{{ $cli->email }}</div>
                            <div class="text-xs text-slate-400">{{ $cli->phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs font-semibold rounded-full border {{ $isBlocked ? 'bg-red-100 text-red-800 border-red-200' : 'bg-emerald-100 text-emerald-800 border-emerald-200' }}">
                                {{ $isBlocked ? 'Bloqueado' : 'Ativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $cli->created_at?->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button @click="openInfo({{ Js::from($cli) }})" class="text-slate-400 hover:text-brand-600 transition-colors px-2" title="Ver informações">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>

                            <form action="{{ route('admin.clientes.toggleBlock', $cli) }}" method="POST" class="inline-block" onsubmit="return adminConfirmSubmit(event, { title: '{{ $isBlocked ? 'Desbloquear cliente' : 'Bloquear cliente' }}', message: 'Tem certeza que deseja {{ $isBlocked ? 'desbloquear' : 'bloquear' }} este cliente?', confirmText: '{{ $isBlocked ? 'Desbloquear' : 'Bloquear' }}' });">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $isBlocked ? 'text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100' : 'text-red-700 hover:text-red-900 bg-red-50 hover:bg-red-100' }} px-3 py-1.5 rounded-md font-semibold transition-colors">
                                    {{ $isBlocked ? 'Desbloquear' : 'Bloquear' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            Nenhum cliente cadastrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $clientes->links() }}
        </div>
    </div>

    <!-- Modal Info -->
    <div x-show="isInfoOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isInfoOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="closeInfo()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="isInfoOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-4 border-brand-500">
                <div class="px-6 py-5 bg-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-slate-900">Informações do Cliente</h3>
                            <p class="text-xs text-slate-500 mt-1" x-text="`ID #${selected?.id ?? ''}`"></p>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-slate-600" @click="closeInfo()">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <div><span class="font-semibold">Nome:</span> <span x-text="`${selected?.name ?? ''} ${selected?.last_name ?? ''}`"></span></div>
                        <div><span class="font-semibold">E-mail:</span> <span x-text="selected?.email ?? ''"></span></div>
                        <div><span class="font-semibold">Telefone:</span> <span x-text="selected?.phone ?? '-'"></span></div>
                        <div><span class="font-semibold">CPF:</span> <span x-text="selected?.cpf ?? '-'"></span></div>
                        <div><span class="font-semibold">Status:</span> <span x-text="(selected?.status ?? 'ativo')"></span></div>
                        <div><span class="font-semibold">Criado em:</span> <span x-text="selected?.created_at ?? '-'"></span></div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex justify-end rounded-b-2xl border-t border-slate-100">
                    <button type="button" @click="closeInfo()" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-lg shadow-sm hover:bg-slate-100">Fechar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('clienteManager', () => ({
        isInfoOpen: false,
        selected: null,

        init() {
            if (window.__openCliente) {
                this.openInfo(window.__openCliente);
                window.__openCliente = null;
            }
        },

        openInfo(cli) {
            this.selected = cli;
            this.isInfoOpen = true;
        },
        closeInfo() {
            this.isInfoOpen = false;
            this.selected = null;
        }
    }));
});
</script>
@endsection
