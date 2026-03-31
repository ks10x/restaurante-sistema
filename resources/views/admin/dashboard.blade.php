@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">Visão Geral</h2>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="overflow-hidden rounded-xl bg-white px-4 py-5 shadow-sm border border-slate-100 sm:p-6">
        <dt class="truncate text-sm font-medium text-slate-500">Faturamento Hoje</dt>
        <dd class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">R$ {{ number_format($kpis['faturamento_hoje'], 2, ',', '.') }}</dd>
    </div>
    <div class="overflow-hidden rounded-xl bg-white px-4 py-5 shadow-sm border border-slate-100 sm:p-6">
        <dt class="truncate text-sm font-medium text-slate-500">Pedidos Hoje</dt>
        <dd class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $kpis['pedidos_hoje'] }}</dd>
    </div>
    <div class="overflow-hidden rounded-xl bg-white px-4 py-5 shadow-sm border border-slate-100 sm:p-6">
        <dt class="truncate text-sm font-medium text-slate-500">Ticket Médio</dt>
        <dd class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">R$ {{ number_format($kpis['ticket_medio'], 2, ',', '.') }}</dd>
    </div>
    <div class="overflow-hidden rounded-xl bg-white px-4 py-5 shadow-sm border border-slate-100 sm:p-6">
        <dt class="truncate text-sm font-medium text-slate-500">Estoque Crítico</dt>
        <dd class="mt-2 text-3xl font-semibold tracking-tight {{ $kpis['estoque_critico'] > 0 ? 'text-red-600' : 'text-slate-900' }}">
            {{ $kpis['estoque_critico'] }}<span class="text-sm font-medium text-slate-500 ml-1">itens</span>
        </dd>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="text-base font-semibold leading-6 text-slate-900 mb-4">Vendas (Últimos 7 dias)</h3>
        <canvas id="vendasChart" height="300"></canvas>
    </div>

    <!-- Top Pratos -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="text-base font-semibold leading-6 text-slate-900 mb-4">Top 5 Pratos (Mês)</h3>
        <ul role="list" class="divide-y divide-slate-100">
            @forelse($topPratos as $prato)
            <li class="flex justify-between gap-x-6 py-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-slate-900">{{ $prato->nome }}</p>
                    <p class="mt-1 truncate text-xs leading-5 text-slate-500">R$ {{ number_format($prato->receita, 2, ',', '.') }} gerados</p>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-sm leading-6 text-slate-900 font-medium">{{ $prato->total }} un</p>
                </div>
            </li>
            @empty
            <li class="py-4 text-sm text-slate-500 text-center">Nenhum dado disponível.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('vendasChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($vendas7dias->pluck('data')) !!},
                datasets: [{
                    label: 'Faturamento (R$)',
                    data: {!! json_encode($vendas7dias->pluck('total')) !!},
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>
@endpush