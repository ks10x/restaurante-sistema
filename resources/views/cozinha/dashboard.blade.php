@include('cozinha.partials.layout-start', ['title' => 'Dashboard da Cozinha - Bella Cucina'])

@php
  $statusMeta = [
      'confirmado' => ['label' => 'Aguardando Producao', 'class' => 'blue', 'icon' => 'fa-hourglass-start', 'color' => '#1d4ed8'],
      'em_producao' => ['label' => 'Em Producao', 'class' => 'amber', 'icon' => 'fa-fire-burner', 'color' => '#d97706'],
      'saindo_entrega' => ['label' => 'Pronto / Saindo', 'class' => 'green', 'icon' => 'fa-motorcycle', 'color' => '#059669'],
  ];
@endphp

<style>
.page{max-width:1500px;margin:0 auto}.hero{display:grid;grid-template-columns:1.4fr 1fr;gap:18px;margin-bottom:20px}.hero-card,.stats-grid .stat,.board-col,.side-card,.history-card,.modal-card,.toast{background:#fff;border:1px solid rgba(15,23,42,.08);box-shadow:0 20px 45px rgba(15,23,42,.08)}.hero-card{border-radius:28px;padding:26px;position:relative;overflow:hidden;background:radial-gradient(circle at top right, color-mix(in srgb, var(--color-secondary) 16%, white 84%), transparent 36%),linear-gradient(135deg,#ffffff 0%,#f8fbff 100%)}.hero-card::after{content:"";position:absolute;right:-50px;top:-50px;width:180px;height:180px;border-radius:50%;background:color-mix(in srgb, var(--color-secondary) 8%, white 92%)}.eyebrow{font-family:'IBM Plex Mono',monospace;font-size:.78rem;letter-spacing:.12em;text-transform:uppercase;color:var(--color-secondary-dark);margin-bottom:10px}.hero-title{font-size:2rem;line-height:1.05;max-width:12ch;margin-bottom:10px;font-family:'Space Grotesk',sans-serif}.hero-subtitle{font-size:.97rem;color:#64748b;max-width:62ch}.hero-meta{display:flex;gap:12px;flex-wrap:wrap;margin-top:18px}.hero-pill{padding:10px 14px;border-radius:999px;background:rgba(255,255,255,.88);border:1px solid var(--color-secondary-border);font-size:.85rem;color:#64748b}.clock-card{border-radius:28px;padding:22px;display:flex;flex-direction:column;justify-content:space-between;background:linear-gradient(135deg,var(--color-secondary-dark) 0%,var(--color-secondary) 100%)}.clock-label{font-family:'IBM Plex Mono',monospace;letter-spacing:.12em;text-transform:uppercase;font-size:.72rem;color:color-mix(in srgb, white 82%, var(--color-secondary) 18%)}.clock-time{font-family:'IBM Plex Mono',monospace;font-size:2.5rem;font-weight:600;color:#fff;margin:12px 0}.clock-date{color:rgba(255,255,255,.86);font-size:.95rem}.clock-status{display:flex;align-items:center;gap:8px;color:#dcfce7;font-size:.9rem}.status-dot{width:10px;height:10px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 6px rgba(34,197,94,.18)}.stats-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin-bottom:20px}.stat{border-radius:18px;padding:18px}.stat-label{font-size:.78rem;text-transform:uppercase;letter-spacing:.10em;color:#94a3b8;margin-bottom:8px}.stat-value{font-family:'IBM Plex Mono',monospace;font-size:1.45rem;font-weight:600}.stat-hint{margin-top:10px;font-size:.86rem;color:#64748b}.layout{display:grid;grid-template-columns:minmax(0,1fr) 350px;gap:18px}.board{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-bottom:20px}.board-col{border-radius:24px;overflow:hidden}.board-head{padding:16px 18px;border-bottom:1px solid rgba(15,23,42,.08);display:flex;align-items:center;justify-content:space-between}.board-title{display:flex;align-items:center;gap:10px;font-size:.95rem;font-weight:700}.board-dot{width:10px;height:10px;border-radius:50%}.board-count{font-family:'IBM Plex Mono',monospace;padding:6px 10px;border-radius:999px;background:var(--surface-accent);font-size:.82rem;color:var(--color-secondary-dark)}.board-body{padding:14px;display:flex;flex-direction:column;gap:14px;min-height:520px;background:linear-gradient(180deg,rgba(248,250,252,.55),#fff)}.pedido-card{border:2px solid rgba(15,23,42,.08);border-radius:20px;overflow:hidden;background:#fff}.pedido-card.status-confirmado{border-color:rgba(29,78,216,.34);box-shadow:0 0 0 3px rgba(29,78,216,.08)}.pedido-card.status-em_producao{border-color:rgba(217,119,6,.34);box-shadow:0 0 0 3px rgba(217,119,6,.08)}.pedido-card.status-saindo_entrega{border-color:rgba(5,150,105,.34);box-shadow:0 0 0 3px rgba(5,150,105,.08)}.pedido-card.critico{box-shadow:0 0 0 4px rgba(220,38,38,.12)}.pedido-progress{height:5px;background:#e2e8f0}.pedido-progress span{display:block;height:100%}.pedido-main{padding:16px}.pedido-top{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:12px}.pedido-code{font-family:'IBM Plex Mono',monospace;font-size:1rem;font-weight:600}.pedido-client{font-size:.88rem;color:#64748b;margin-top:4px}.pedido-badges{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}.badge{display:inline-flex;align-items:center;gap:6px;padding:7px 10px;border-radius:999px;font-size:.73rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}.badge.blue{background:rgba(29,78,216,.10);color:#1d4ed8}.badge.amber{background:rgba(217,119,6,.12);color:#d97706}.badge.green{background:rgba(5,150,105,.12);color:#059669}.badge.red{background:rgba(220,38,38,.10);color:#dc2626}.pedido-items{display:flex;flex-direction:column;gap:12px}.pedido-item{display:grid;grid-template-columns:68px 1fr;gap:12px;padding:12px;border-radius:16px;background:#f8fafc}.pedido-thumb{width:68px;height:68px;border-radius:14px;object-fit:cover;background:#dbeafe}.pedido-item-name{font-size:.95rem;font-weight:700;display:flex;gap:8px;align-items:center}.pedido-item-desc{font-size:.84rem;color:#64748b;line-height:1.45;margin-top:5px}.pedido-tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px}.tag{font-size:.72rem;padding:5px 8px;border-radius:999px;background:#fff;border:1px solid rgba(15,23,42,.08);color:#64748b}.pedido-extra{display:grid;gap:8px;margin-top:12px}.pedido-note{padding:10px 12px;border-radius:14px;font-size:.83rem}.pedido-note.address{background:var(--surface-accent);color:var(--color-secondary-dark)}.pedido-note.obs{background:#fef2f2;color:#b91c1c}.side{display:flex;flex-direction:column;gap:18px}.side-card{border-radius:24px;padding:18px}.side-title,.history-title{font-size:1rem;font-weight:700;margin-bottom:14px}.mini-list{display:flex;flex-direction:column;gap:12px}.mini-row{display:flex;align-items:center;gap:12px;padding:12px;border-radius:16px;background:#f8fafc}.mini-thumb{width:48px;height:48px;border-radius:12px;object-fit:cover;background:#dbeafe}.mini-row strong{display:block;font-size:.9rem}.mini-row span{display:block;font-size:.82rem;color:#64748b}.stock-meter{height:8px;border-radius:999px;background:#e2e8f0;margin-top:8px;overflow:hidden}.stock-meter span{display:block;height:100%;background:linear-gradient(90deg,#f59e0b,#dc2626)}.history{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.history-card{border-radius:24px;padding:18px}.history-grid{display:flex;flex-direction:column;gap:14px}.history-order{border:1px solid rgba(15,23,42,.08);border-radius:18px;padding:14px;background:linear-gradient(180deg,#fff,#f8fafc)}.history-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px}.history-code{font-family:'IBM Plex Mono',monospace;font-size:.95rem;font-weight:600}.history-time{font-size:.78rem;color:#64748b}.history-item{display:grid;grid-template-columns:54px 1fr;gap:10px;margin-top:10px}.history-item img{width:54px;height:54px;border-radius:12px;object-fit:cover;background:#dbeafe}.history-item-name{font-size:.88rem;font-weight:700}.history-item-desc{font-size:.8rem;color:#64748b;line-height:1.4;margin-top:4px}.history-empty,.board-empty{height:100%;min-height:170px;border:1px dashed rgba(148,163,184,.5);border-radius:18px;display:flex;align-items:center;justify-content:center;text-align:center;color:#64748b;padding:18px;background:rgba(248,250,252,.6)}
@media(max-width:1280px){.stats-grid{grid-template-columns:repeat(3,1fr)}.layout{grid-template-columns:1fr}.side{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:1024px){.hero,.board,.history{grid-template-columns:1fr}}@media(max-width:720px){.stats-grid,.side{grid-template-columns:1fr}.pedido-actions{flex-direction:column}}
</style>

<div class="page">
  <section class="hero">
    <div class="hero-card">
      <div class="eyebrow">Central Operacional da Cozinha</div>
      <h1 class="hero-title">Controle completo dos pedidos do dia em um unico painel.</h1>
      <p class="hero-subtitle">Acompanhe fila ativa, gargalos de preparo, entregas concluidas, cancelamentos e insumos criticos sem sair da tela da cozinha.</p>
      <div class="hero-meta">
        <div class="hero-pill"><strong>{{ $kpis['pedidos_hoje'] }}</strong> pedidos hoje</div>
        <div class="hero-pill"><strong>{{ $kpis['itens_hoje'] }}</strong> itens produzidos hoje</div>
        <div class="hero-pill"><strong>{{ $kpis['tempo_medio_inicio'] }} min</strong> para iniciar preparo</div>
        <div class="hero-pill"><strong>{{ $kpis['tempo_medio_entrega'] }} min</strong> do confirmado ate a entrega</div>
      </div>
    </div>
    <div class="clock-card">
      <div>
        <div class="clock-label">Bella Cucina Kitchen Live</div>
        <div class="clock-time" id="liveClock">00:00:00</div>
        <div class="clock-date" id="liveDate">{{ now()->translatedFormat('l, d \\d\\e F \\d\\e Y') }}</div>
      </div>
      <div class="clock-status"><span class="status-dot"></span>Painel sincronizado com pedidos, producao e entrega</div>
    </div>
  </section>

  <section class="stats-grid">
    <div class="stat"><div class="stat-label">Pedidos Ativos</div><div class="stat-value">{{ $kpis['ativos'] }}</div><div class="stat-hint">{{ $kpis['aguardando'] }} aguardando e {{ $kpis['em_producao'] }} em producao</div></div>
    <div class="stat"><div class="stat-label">Prontos / Saindo</div><div class="stat-value">{{ $kpis['saindo_entrega'] }}</div><div class="stat-hint">Pedidos aguardando despacho ou retirada</div></div>
    <div class="stat"><div class="stat-label">Entregues Hoje</div><div class="stat-value">{{ $kpis['entregues_hoje'] }}</div><div class="stat-hint">Fluxo concluido no dia atual</div></div>
    <div class="stat"><div class="stat-label">Cancelados Hoje</div><div class="stat-value">{{ $kpis['cancelados_hoje'] }}</div><div class="stat-hint">Monitoramento de falhas e indisponibilidades</div></div>
    <div class="stat"><div class="stat-label">Faturamento Hoje</div><div class="stat-value">R$ {{ number_format($kpis['faturamento_hoje'], 2, ',', '.') }}</div><div class="stat-hint">Pedidos aprovados pela operacao</div></div>
    <div class="stat"><div class="stat-label">Ticket Medio</div><div class="stat-value">R$ {{ number_format($kpis['ticket_medio_hoje'], 2, ',', '.') }}</div><div class="stat-hint">Media dos pedidos pagos hoje</div></div>
  </section>

  <section class="layout">
    <div>
      <section class="board">
        @foreach (['confirmado', 'em_producao', 'saindo_entrega'] as $status)
          @php $meta = $statusMeta[$status]; $pedidos = $pedidosPorStatus[$status]; @endphp
          <div class="board-col">
            <div class="board-head"><div class="board-title"><span class="board-dot" style="background: {{ $meta['color'] }}"></span>{{ $meta['label'] }}</div><div class="board-count">{{ $pedidos->count() }}</div></div>
            <div class="board-body">
              @forelse ($pedidos as $pedido)
                <article class="pedido-card status-{{ $status }} {{ $pedido->faixa_tempo === 'critico' ? 'critico' : '' }}">
                  <div class="pedido-progress"><span style="width: {{ $pedido->progresso }}%; background: {{ $meta['color'] }}"></span></div>
                  <div class="pedido-main">
                    <div class="pedido-top"><div><div class="pedido-code">#{{ $pedido->codigo }}</div><div class="pedido-client">{{ $pedido->usuario->name ?? 'Cliente' }} · {{ $pedido->tipo_entrega === 'entrega' ? 'Entrega' : 'Retirada' }}</div></div><span class="badge {{ $pedido->faixa_tempo === 'critico' ? 'red' : ($pedido->faixa_tempo === 'atencao' ? 'amber' : 'green') }}"><i class="fas fa-stopwatch"></i> {{ $pedido->minutos_decorridos }} min</span></div>
                    <div class="pedido-badges"><span class="badge {{ $meta['class'] }}"><i class="fas {{ $meta['icon'] }}"></i> {{ $meta['label'] }}</span><span class="badge blue"><i class="fas fa-bag-shopping"></i> {{ $pedido->total_itens }} itens</span><span class="badge green"><i class="fas fa-money-bill-wave"></i> R$ {{ number_format($pedido->total, 2, ',', '.') }}</span></div>
                    <div class="pedido-items">
                      @foreach ($pedido->itens as $item)
                        <div class="pedido-item"><img class="pedido-thumb" src="{{ $item->foto_url }}" alt="{{ $item->nome_exibicao }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';"><div><div class="pedido-item-name">{{ $item->quantidade }}x {{ $item->nome_exibicao }}</div>@if($item->descricao_curta)<div class="pedido-item-desc">{{ $item->descricao_curta }}</div>@endif<div class="pedido-tags">@foreach (($item->ingredientes_lista ?? []) as $ingrediente)<span class="tag">{{ $ingrediente }}</span>@endforeach @if(is_array($item->opcoes ?? null)) @foreach (collect($item->opcoes)->take(2) as $opcao)<span class="tag">{{ data_get($opcao, 'nome') }}: {{ data_get($opcao, 'valor') }}</span>@endforeach @endif</div>@if($item->observacao_limpa)<div class="pedido-item-desc" style="color:#b45309;margin-top:8px;">Observacao: {{ $item->observacao_limpa }}</div>@endif</div></div>
                      @endforeach
                    </div>
                    <div class="pedido-extra">@if($pedido->tipo_entrega === 'entrega' && $pedido->endereco_formatado)<div class="pedido-note address"><i class="fas fa-location-dot"></i> {{ $pedido->endereco_formatado }}</div>@else<div class="pedido-note address"><i class="fas fa-store"></i> Retirada no balcao</div>@endif @if($pedido->observacoes_limpa)<div class="pedido-note obs"><i class="fas fa-triangle-exclamation"></i> {{ $pedido->observacoes_limpa }}</div>@endif</div>
                  </div>
                </article>
              @empty
                <div class="board-empty">Nenhum pedido nesta etapa agora.</div>
              @endforelse
            </div>
          </div>
        @endforeach
      </section>

      <section class="history">
        <div class="history-card"><div class="history-title">Produzidos Hoje</div><div class="history-grid">@forelse ($pedidosProduzidosHoje as $pedido)<div class="history-order"><div class="history-head"><div class="history-code">#{{ $pedido->codigo }} · {{ $pedido->usuario->name ?? 'Cliente' }}</div><div class="history-time">{{ optional($pedido->producao_em)->format('H:i') }}</div></div>@foreach ($pedido->itens->take(2) as $item)<div class="history-item"><img src="{{ $item->foto_url }}" alt="{{ $item->nome_exibicao }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';"><div><div class="history-item-name">{{ $item->quantidade }}x {{ $item->nome_exibicao }}</div><div class="history-item-desc">{{ $item->descricao_curta }}</div></div></div>@endforeach</div>@empty<div class="history-empty">Nenhum pedido entrou em producao hoje.</div>@endforelse</div></div>
        <div class="history-card"><div class="history-title">Entregues Hoje</div><div class="history-grid">@forelse ($pedidosEntreguesHoje as $pedido)<div class="history-order"><div class="history-head"><div class="history-code">#{{ $pedido->codigo }} · {{ $pedido->usuario->name ?? 'Cliente' }}</div><div class="history-time">{{ optional($pedido->entregue_em)->format('H:i') }}</div></div>@foreach ($pedido->itens->take(2) as $item)<div class="history-item"><img src="{{ $item->foto_url }}" alt="{{ $item->nome_exibicao }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';"><div><div class="history-item-name">{{ $item->quantidade }}x {{ $item->nome_exibicao }}</div><div class="history-item-desc">{{ $item->descricao_curta }}</div></div></div>@endforeach</div>@empty<div class="history-empty">Ainda nao ha entregas registradas hoje.</div>@endforelse</div></div>
        <div class="history-card"><div class="history-title">Cancelados Hoje</div><div class="history-grid">@forelse ($pedidosCanceladosHoje as $pedido)<div class="history-order"><div class="history-head"><div class="history-code">#{{ $pedido->codigo }} · {{ $pedido->usuario->name ?? 'Cliente' }}</div><div class="history-time">{{ optional($pedido->cancelado_em)->format('H:i') }}</div></div><div class="pedido-note obs" style="margin-bottom:10px;"><i class="fas fa-ban"></i> {{ $pedido->motivo_cancelamento ?: 'Cancelado sem motivo informado.' }}</div>@foreach ($pedido->itens->take(2) as $item)<div class="history-item"><img src="{{ $item->foto_url }}" alt="{{ $item->nome_exibicao }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';"><div><div class="history-item-name">{{ $item->quantidade }}x {{ $item->nome_exibicao }}</div><div class="history-item-desc">{{ $item->descricao_curta }}</div></div></div>@endforeach</div>@empty<div class="history-empty">Nenhum cancelamento hoje.</div>@endforelse</div></div>
      </section>
    </div>

    <aside class="side">
      <div class="side-card"><div class="side-title">Mais Produzidos Hoje</div><div class="mini-list">@forelse ($pratosMaisProduzidos as $prato)<div class="mini-row"><img class="mini-thumb" src="{{ $prato->imagem ?: 'https://via.placeholder.com/48x48.png?text=Prato' }}" alt="{{ $prato->nome }}"><div><strong>{{ $prato->nome }}</strong><span>{{ $prato->quantidade }} unidades no dia</span></div></div>@empty<div class="history-empty">Sem producao suficiente para ranquear pratos hoje.</div>@endforelse</div></div>
      <div class="side-card"><div class="side-title">Insumos Criticos</div><div class="mini-list">@forelse ($insumosCriticos as $insumo)<div class="mini-row" style="display:block;"><strong>{{ $insumo->nome }}</strong><span>{{ number_format($insumo->quantidade_atual, 3, ',', '.') }} {{ $insumo->unidade }} disponiveis · minimo {{ number_format($insumo->quantidade_minima, 3, ',', '.') }}</span><div class="stock-meter"><span style="width: {{ min(100, max(6, $insumo->percentual_estoque)) }}%"></span></div></div>@empty<div class="history-empty">Nenhum insumo em nivel critico agora.</div>@endforelse</div></div>
    </aside>
  </section>
</div>

<script>
function updateDashboardClock(){const time=document.getElementById('liveClock');const date=document.getElementById('liveDate');if(time){const now=new Date();time.textContent=now.toLocaleTimeString('pt-BR');if(date){date.textContent=now.toLocaleDateString('pt-BR',{weekday:'long',day:'2-digit',month:'long',year:'numeric'});}}}
updateDashboardClock();setInterval(updateDashboardClock,1000);
</script>

@include('cozinha.partials.layout-end')
