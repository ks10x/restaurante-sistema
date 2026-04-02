@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
    openModal: false,
    selectedPedido: null,
    viewDetails(pedido) {
        this.selectedPedido = pedido;
        this.openModal = true;
    }
}">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 border-b-2 border-brand-500 pb-2 inline-block">Gestão de Pedidos</h1>
            <p class="mt-2 text-sm text-slate-600">Acompanhe e atualize o status dos pedidos recebidos.</p>
        </div>
    </div>

    <!-- Tabela de Pedidos -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cód. Pedido</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Data / Hora</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($pedidos as $pedido)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-brand-900">
                            #{{ $pedido->codigo }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 font-medium">{{ $pedido->usuario->name ?? 'Cliente Removido' }}</div>
                            <div class="text-xs text-slate-500">{{ $pedido->tipo_entrega == 'entrega' ? 'Delivery' : 'Retirada' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $pedido->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConf = \App\Models\Pedido::STATUS_LABELS[$pedido->status] ?? ['label' => $pedido->status, 'cor' => 'gray'];
                                $badgeColor = match($statusConf['cor']) {
                                    'gray' => 'bg-slate-100 text-slate-800 border-slate-200',
                                    'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'amber' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'green' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'red' => 'bg-red-100 text-red-800 border-red-200',
                                    'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $badgeColor }}">
                                {{ $statusConf['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-bold text-right">
                            R$ {{ number_format($pedido->total, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="viewDetails({{ Js::from($pedido) }})" class="text-brand-600 hover:text-brand-900 mr-3">Detalhes</button>
                            
                            <!-- Dropdown de Status Dinâmico Minimalista -->
                            <div class="inline-block relative" x-data="{ openOptions: false }" @click.away="openOptions = false">
                                <button @click="openOptions = !openOptions" class="text-slate-500 hover:text-slate-700 bg-slate-100 px-2 py-1 rounded">
                                    Ações ▼
                                </button>
                                <div x-show="openOptions" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-slate-200 z-10">
                                    <form action="{{ route('admin.pedidos.updateStatus', $pedido) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="status" value="confirmado" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b">1. Confirmar</button>
                                        <button type="submit" name="status" value="em_producao" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b">2. Em Produção</button>
                                        <button type="submit" name="status" value="saindo_entrega" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b">3. Saiu para Entrega</button>
                                        <button type="submit" name="status" value="entregue" class="block w-full text-left px-4 py-2 text-sm text-emerald-700 hover:bg-emerald-50 font-bold border-b">✔ Entregue</button>
                                        <button type="submit" name="status" value="cancelado" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">✕ Cancelar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Nenhum pedido encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $pedidos->links() }}
        </div>
    </div>

    <!-- Modal Alpine.js para Detalhes do Pedido -->
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="openModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Menu Content -->
            <div x-show="openModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <!-- Cabeçalho -->
                <div class="bg-brand-900 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-bold text-white flex items-center gap-2">
                        Detalhes do Pedido <span x-text="'#'+selectedPedido?.codigo" class="text-brand-200"></span>
                    </h3>
                    <button @click="openModal = false" class="text-white hover:text-red-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <!-- Corpo do Detalhe -->
                <div class="px-6 py-4 space-y-4">
                    <!-- Infos do Cliente e Resumo -->
                    <div class="grid grid-cols-2 gap-4 text-sm text-slate-600 bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <div>
                            <p><strong class="text-slate-900">Cliente:</strong> <span x-text="selectedPedido?.usuario?.name"></span></p>
                            <p><strong class="text-slate-900">Tipo:</strong> <span x-text="selectedPedido?.tipo_entrega == 'entrega' ? 'Delivery' : 'Retirada no Local'"></span></p>
                            <p><strong class="text-slate-900">Criado em:</strong> <span x-text="new Date(selectedPedido?.created_at).toLocaleString('pt-BR')"></span></p>
                        </div>
                        <div>
                            <p><strong class="text-slate-900">Status Pgto:</strong> <span x-text="selectedPedido?.pagamento_status"></span></p>
                            <p><strong class="text-slate-900">Método:</strong> <span x-text="selectedPedido?.pagamento_metodo"></span></p>
                            <template x-if="selectedPedido?.troco_para">
                                <p><strong class="text-slate-900 relative text-orange-600">Troco p/:</strong> R$ <span x-text="selectedPedido?.troco_para"></span></p>
                            </template>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <template x-if="selectedPedido?.tipo_entrega == 'entrega'">
                        <div class="text-sm bg-white border border-slate-200 p-3 rounded-lg">
                            <strong class="text-slate-900 block mb-1">Endereço de Entrega:</strong>
                            <span class="text-slate-600">ID do Endereço de Relação: <span x-text="selectedPedido?.endereco_id || 'Não Informado'"></span> (Veja o endereço atualizado pelo usuário no banco)</span>
                        </div>
                    </template>

                    <!-- Lista de Itens (Loop Simples Frontend) -->
                    <div>
                        <strong class="text-slate-900 text-sm mb-2 block border-b pb-1">Itens Adquiridos:</strong>
                        <ul class="space-y-3">
                            <template x-for="item in selectedPedido?.itens" :key="item.id">
                                <li class="flex justify-between text-sm">
                                    <div>
                                        <span x-text="item.quantidade + 'x '" class="font-bold text-slate-700"></span> 
                                        <span x-text="item.prato_nome || item.prato?.nome" class="font-medium text-slate-900"></span>
                                        <template x-if="item.observacoes">
                                            <p class="text-xs text-orange-600 italic mt-0.5" x-text="'Obs: ' + item.observacoes"></p>
                                        </template>
                                    </div>
                                    <div class="text-slate-700 font-medium">
                                        R$ <span x-text="parseFloat(item.preco_unitario * item.quantidade).toFixed(2).replace('.', ',')"></span>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="border-t border-slate-200 mt-4 pt-4 text-sm text-slate-700 space-y-1">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span x-text="'R$ ' + parseFloat(selectedPedido?.subtotal).toFixed(2).replace('.', ',')"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Taxa de Entrega:</span>
                            <span x-text="'R$ ' + parseFloat(selectedPedido?.taxa_entrega).toFixed(2).replace('.', ',')"></span>
                        </div>
                        <template x-if="selectedPedido?.desconto > 0">
                            <div class="flex justify-between text-green-600">
                                <span>Desconto:</span>
                                <span x-text="'-R$ ' + parseFloat(selectedPedido?.desconto).toFixed(2).replace('.', ',')"></span>
                            </div>
                        </template>
                        <div class="flex justify-between text-base font-bold text-slate-900 mt-2">
                            <span>TOTAL:</span>
                            <span x-text="'R$ ' + parseFloat(selectedPedido?.total).toFixed(2).replace('.', ',')"></span>
                        </div>
                    </div>
                </div>

                <!-- Rodapé -->
                <div class="bg-slate-50 px-6 py-4 flex justify-end">
                    <button @click="openModal = false" class="px-6 py-2 bg-slate-200 text-slate-800 font-bold rounded-lg hover:bg-slate-300 transition-colors">
                        Fechar Relatório
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection