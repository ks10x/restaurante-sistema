@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
  --bg:       #f0f4fb;
  --bg2:      #ffffff;
  --bg3:      #e8eef8;
  --bg4:      #dbe4f4;
  --border:   rgba(15,40,100,0.09);
  --border-h: rgba(15,40,100,0.18);
  --text:     #0d1b38;
  --muted:    #7a8db5;
  --muted2:   #4e6490;
  --accent:   #1a3f8f;
  --accent2:  #2558c4;
  --green:    #059669;
  --red:      #dc2626;
  --amber:    #d97706;
  --blue:     #1d6fdf;
  --purple:   #6d28d9;
  --r:        12px;
  --r-sm:     8px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Sora', sans-serif;
  background: var(--bg);
  color: var(--text);
  -webkit-font-smoothing: antialiased;
  line-height: 1.5;
}

/* ─── SIDEBAR ─── */
.sidebar {
  position: fixed; inset: 0 auto 0 0;
  width: 232px;
  background: #0c1e47;
  border-right: 1px solid rgba(255,255,255,0.06);
  display: flex; flex-direction: column;
  z-index: 50;
}

.s-brand {
  padding: 22px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  display: flex; align-items: center; gap: 10px;
}
.s-brand-icon {
  width: 34px; height: 34px; border-radius: 9px;
  background: var(--accent2);
  display: grid; place-items: center;
  font-size: 16px; flex-shrink: 0;
}
.s-brand-name { font-size: 14px; font-weight: 700; letter-spacing: -.02em; color: #ffffff; }
.s-brand-sub  { font-size: 10px; color: rgba(255,255,255,0.4); margin-top: 1px; letter-spacing: .06em; text-transform: uppercase; }

.s-nav { flex: 1; padding: 14px 10px; overflow-y: auto; display: flex; flex-direction: column; gap: 2px; }
.s-section-label {
  font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.28);
  text-transform: uppercase; letter-spacing: .1em;
  padding: 12px 10px 4px;
}
.s-item {
  display: flex; align-items: center; gap: 9px;
  padding: 9px 10px; border-radius: var(--r-sm);
  color: rgba(255,255,255,0.5); font-size: 13px; font-weight: 500;
  text-decoration: none; transition: all .15s; position: relative;
  cursor: pointer;
}
.s-item:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.85); }
.s-item.active { background: rgba(37,88,196,0.35); color: #ffffff; }
.s-item.active::before {
  content: ''; position: absolute; left: 0; top: 7px; bottom: 7px;
  width: 3px; border-radius: 0 3px 3px 0; background: var(--accent2);
}
.s-item svg { width: 15px; height: 15px; flex-shrink: 0; opacity: .7; }
.s-item.active svg { opacity: 1; }
.s-badge {
  margin-left: auto; font-size: 10px; font-weight: 700;
  padding: 1px 7px; border-radius: 20px;
  background: rgba(220,38,38,.2); color: #fca5a5;
}

.s-footer { padding: 14px 10px; border-top: 1px solid rgba(255,255,255,0.07); }
.s-user {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 10px; border-radius: var(--r-sm);
}
.s-avatar {
  width: 30px; height: 30px; border-radius: 50%;
  background: var(--accent2); display: grid; place-items: center;
  font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.s-user-name { font-size: 12px; font-weight: 600; color: #ffffff; }
.s-user-role { font-size: 10px; color: rgba(255,255,255,0.38); }

/* ─── MAIN ─── */
.main { margin-left: 232px; min-height: 100vh; display: flex; flex-direction: column; }

/* ─── TOPBAR ─── */
.topbar {
  height: 60px;
  background: var(--bg2);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 28px;
  position: sticky; top: 0; z-index: 40; flex-shrink: 0;
  box-shadow: 0 1px 0 var(--border);
}
.topbar-title { font-size: 16px; font-weight: 600; letter-spacing: -.02em; color: var(--text); }
.topbar-sub   { font-size: 11px; color: var(--muted); margin-top: 1px; }
.topbar-right { display: flex; align-items: center; gap: 8px; }
.t-btn {
  width: 34px; height: 34px; border-radius: var(--r-sm);
  background: var(--bg3); border: 1px solid var(--border);
  display: grid; place-items: center; cursor: pointer; color: var(--muted);
  transition: all .15s; position: relative;
}
.t-btn:hover { border-color: var(--border-h); color: var(--text); }
.t-btn-dot {
  position: absolute; top: 6px; right: 6px;
  width: 6px; height: 6px; border-radius: 50%;
  background: var(--red); border: 1.5px solid var(--bg2);
}

/* ─── PAGE ─── */
.page { padding: 40px; display: flex; flex-direction: column; gap: 56px; flex: 1; }

/* ─── ALERT BANNER ─── */
.alert-banner {
  display: flex; align-items: center; gap: 12px;
  background: rgba(220,38,38,.05);
  border: 1px solid rgba(220,38,38,.2);
  border-radius: var(--r); padding: 13px 16px;
  animation: pulse-b 2.5s ease-in-out infinite;
}
@keyframes pulse-b {
  0%,100% { border-color: rgba(220,38,38,.2); }
  50%      { border-color: rgba(220,38,38,.45); }
}
.alert-ico {
  width: 30px; height: 30px; border-radius: 8px;
  background: rgba(220,38,38,.1);
  display: grid; place-items: center; flex-shrink: 0;
}
.alert-txt { font-size: 13px; flex: 1; }
.alert-txt b { color: var(--red); font-weight: 600; }
.alert-txt span { color: var(--muted2); }
.alert-close {
  background: none; border: none; color: var(--muted);
  font-size: 18px; cursor: pointer; line-height: 1; padding: 2px 4px;
}
.alert-close:hover { color: var(--text); }

/* ─── KPI GRIDS ─── */
.kpi-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; margin-top: 2% }
.kpi-row-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 28px; margin-top: 2%;}

.kpi {
  background: var(--bg2); border: 1px solid var(--border);
  border-radius: var(--r); padding: 20px 22px 18px;
  position: relative; overflow: hidden;
  transition: border-color .2s, transform .2s, box-shadow .2s; cursor: default;
  box-shadow: 0 1px 4px rgba(15,40,100,0.04);
}
.kpi:hover { border-color: var(--border-h); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(15,40,100,0.08); }
.kpi::before {
  content: ''; position: absolute;
  top: 0; left: 0; right: 0; height: 3px;
  background: var(--kc, transparent);
}
.kpi.c-orange { --kc: var(--accent2); }
.kpi.c-green  { --kc: var(--green);   }
.kpi.c-red    { --kc: var(--red);     }
.kpi.c-blue   { --kc: var(--blue);    }
.kpi.c-amber  { --kc: var(--amber);   }
.kpi.c-purple { --kc: var(--purple);  }

.kpi-label {
  font-size: 10.5px; font-weight: 600; color: var(--muted);
  text-transform: uppercase; letter-spacing: .08em; margin-bottom: 10px;
}
.kpi-value {
  font-size: 26px; font-weight: 700; letter-spacing: -.03em;
  font-family: 'JetBrains Mono', monospace; line-height: 1;
}
.kpi-value.orange { color: var(--accent2); }
.kpi-value.green  { color: var(--green);   }
.kpi-value.red    { color: var(--red);     }
.kpi-value.blue   { color: var(--blue);    }
.kpi-value.amber  { color: var(--amber);   }
.kpi-value.purple { color: var(--purple);  }
.kpi-value.dim    { color: var(--text);    }

.kpi-sub {
  font-size: 11px; color: var(--muted); margin-top: 7px;
  display: flex; align-items: center; gap: 5px; flex-wrap: wrap;
}
.badge {
  display: inline-flex; align-items: center;
  font-size: 10px; font-weight: 700; padding: 2px 7px;
  border-radius: 20px; letter-spacing: .02em;
}
.badge-ok   { background: rgba(5,150,105,.1);   color: var(--green); }
.badge-warn { background: rgba(217,119,6,.1);   color: var(--amber); }
.badge-crit { background: rgba(220,38,38,.1);   color: var(--red);   }

/* ─── PANELS ─── */
.panel {
  background: var(--bg2); border: 1px solid var(--border);
  border-radius: var(--r); overflow: hidden;
  box-shadow: 0 1px 4px rgba(15,40,100,0.04);
  margin-top: 5%;
}
.panel-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 22px 0; margin-bottom: 18px;
}
.panel-title { font-size: 13.5px; font-weight: 600; letter-spacing: -.01em; color: var(--text); }
.panel-chip {
  font-size: 10.5px; font-weight: 600; padding: 3px 10px;
  border-radius: 20px; background: var(--bg3);
  color: var(--muted); border: 1px solid var(--border);
}
.panel-link {
  font-size: 11.5px; color: var(--accent2); text-decoration: none;
  font-weight: 500; transition: color .15s;
}
.panel-link:hover { color: var(--accent); }
.panel-body { padding: 0 22px 20px; }

/* ─── CHART ─── */
.chart-area { height: 210px; position: relative; }

/* ─── GRID LAYOUTS ─── */
.row-chart { display: grid; grid-template-columns: 1fr 320px; gap: 28px; }
.row-mid   { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; }
.row-bot   { display: grid; grid-template-columns: 1fr 370px; gap: 28px; }

/* ─── RANK LIST ─── */
.rank-list { display: flex; flex-direction: column; gap: 13px; }
.rank-item { display: flex; align-items: center; gap: 12px; }
.rank-n {
  width: 20px; height: 20px; border-radius: 5px;
  background: var(--bg3); border: 1px solid var(--border);
  font-size: 10px; font-weight: 700; color: var(--muted);
  display: grid; place-items: center; flex-shrink: 0;
}
.rank-n.gold { background: rgba(37,88,196,.1); color: var(--accent2); border-color: rgba(37,88,196,.25); }
.rank-info { flex: 1; min-width: 0; }
.rank-name { font-size: 12.5px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.rank-bar-bg { margin-top: 5px; height: 3px; background: var(--bg3); border-radius: 3px; }
.rank-bar-fg { height: 3px; border-radius: 3px; background: linear-gradient(90deg, var(--accent), var(--accent2)); }
.rank-rev { font-size: 10.5px; color: var(--muted); margin-top: 3px; }
.rank-qty { font-size: 12px; font-weight: 600; font-family: 'JetBrains Mono', monospace; color: var(--accent2); flex-shrink: 0; }

/* ─── ALERT LIST ─── */
.alert-list { display: flex; flex-direction: column; gap: 9px; }
.alert-item {
  background: var(--bg3); border: 1px solid var(--border);
  border-left: 3px solid var(--amber);
  border-radius: var(--r-sm); padding: 13px 14px;
  transition: border-left-color .15s;
}
.alert-item:hover { border-left-color: var(--red); }
.alert-item-name { font-size: 12.5px; font-weight: 600; color: var(--text); }
.alert-item-cat  { font-size: 11px; color: var(--muted); margin-top: 2px; }
.tags { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 8px; }
.tag {
  font-size: 10px; padding: 2px 8px; border-radius: 4px; font-weight: 500;
  background: rgba(217,119,6,.07); color: var(--amber);
  border: 1px solid rgba(217,119,6,.2);
}

/* ─── INGREDIENTES ─── */
.donut-row { display: flex; align-items: center; gap: 20px; margin-bottom: 18px; }
.donut-canvas { width: 110px !important; height: 110px !important; flex-shrink: 0; }
.legend { display: flex; flex-direction: column; gap: 7px; flex: 1; }
.legend-item { display: flex; align-items: center; gap: 8px; }
.legend-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.legend-label { font-size: 11.5px; color: var(--muted2); flex: 1; }
.legend-pct { font-size: 11.5px; font-weight: 600; font-family: 'JetBrains Mono', monospace; }

.ingr-list { display: flex; flex-direction: column; }
.ingr-item {
  display: flex; align-items: center; gap: 10px;
  padding: 11px 0; border-bottom: 1px solid var(--border);
}
.ingr-item:last-child { border-bottom: none; }
.ingr-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--accent2); flex-shrink: 0; }
.ingr-name { font-size: 12.5px; font-weight: 500; color: var(--text); }
.ingr-consumo { font-size: 10.5px; color: var(--muted); margin-top: 1px; }
.ingr-custo { font-size: 12px; font-weight: 600; font-family: 'JetBrains Mono', monospace; color: var(--green); flex-shrink: 0; margin-left: auto; }

/* ─── PEDIDOS TABLE ─── */
.orders-table { width: 100%; border-collapse: collapse; }
.orders-table thead th {
  font-size: 10px; font-weight: 600; text-transform: uppercase;
  letter-spacing: .07em; color: var(--muted);
  padding: 0 12px 12px; text-align: left; border-bottom: 1px solid var(--border);
}
.orders-table tbody tr { border-bottom: 1px solid var(--border); transition: background .1s; }
.orders-table tbody tr:last-child { border-bottom: none; }
.orders-table tbody tr:hover { background: var(--bg3); }
.orders-table td { padding: 12px; font-size: 12.5px; vertical-align: middle; }
.order-id { font-family: 'JetBrains Mono', monospace; font-size: 11.5px; color: var(--muted2); }
.order-name { font-weight: 500; color: var(--text); }
.order-items { font-size: 11px; color: var(--muted); margin-top: 1px; }
.order-val { font-family: 'JetBrains Mono', monospace; font-weight: 600; color: var(--text); }

.status-pill {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 10.5px; font-weight: 600; padding: 3px 9px;
  border-radius: 20px; white-space: nowrap;
}
.status-pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.s-confirmado  { background: rgba(29,111,223,.1);  color: var(--blue);   }
.s-em_producao { background: rgba(217,119,6,.1);   color: var(--amber);  }
.s-saindo      { background: rgba(109,40,217,.1);  color: var(--purple); }
.s-entregue    { background: rgba(5,150,105,.1);   color: var(--green);  }
.s-cancelado   { background: rgba(220,38,38,.1);   color: var(--red);    }
.s-pendente    { background: rgba(122,141,181,.1); color: var(--muted2); }

/* ─── MINI STATS ─── */
.mini-stats { display: flex; flex-direction: column; gap: 14px; }
.mini-stat {
  background: var(--bg2); border: 1px solid var(--border);
  border-radius: var(--r-sm); padding: 14px 16px;
  display: flex; align-items: center; gap: 12px;
  box-shadow: 0 1px 4px rgba(15,40,100,0.04);
}
.mini-stat-ico {
  width: 34px; height: 34px; border-radius: 8px;
  display: grid; place-items: center; flex-shrink: 0; font-size: 15px;
}
.mini-stat-label { font-size: 11px; color: var(--muted); }
.mini-stat-val { font-size: 17px; font-weight: 700; font-family: 'JetBrains Mono', monospace; line-height: 1.2; }

/* ─── EMPTY STATE ─── */
.empty { text-align: center; padding: 28px 16px; color: var(--muted); font-size: 12.5px; }
.empty-ico { font-size: 26px; margin-bottom: 6px; opacity: .45; }

/* ─── SCROLLBAR ─── */
::-webkit-scrollbar { width: 4px; height: 4px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border-h); border-radius: 4px; }
</style>
@endpush

@section('content')

{{-- ── ALERTA CRÍTICO ── --}}
@if($kpis['estoque_critico'] > 0)
<div class="alert-banner" id="alertBanner">
  <div class="alert-ico">
    <svg width="15" height="15" fill="none" stroke="var(--red)" stroke-width="2.2" viewBox="0 0 24 24">
      <path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
  </div>
  <div class="alert-txt">
    <b>{{ $kpis['estoque_critico'] }} ingrediente{{ $kpis['estoque_critico'] > 1 ? 's' : '' }} abaixo do mínimo.</b>
    <span> Reabasteça para manter o cardápio disponível.</span>
  </div>
  <a href="{{ route('admin.estoque.index') }}" style="font-size:12px;color:var(--red);text-decoration:none;font-weight:600;margin-right:12px;white-space:nowrap">Ver estoque →</a>
  <button class="alert-close" onclick="this.closest('.alert-banner').remove()">×</button>
</div>
@endif

{{-- ── KPIs LINHA 1: estoque ── --}}
<div class="kpi-row-3">
  <div class="kpi c-orange">
    <div class="kpi-label">Custo Total em Estoque</div>
    <div class="kpi-value orange">R$&nbsp;{{ number_format($kpis['custo_total_estoque'], 2, ',', '.') }}</div>
    <div class="kpi-sub">valor dos ingredientes ativos</div>
  </div>
  <div class="kpi c-amber">
    <div class="kpi-label">Pratos Comprometidos</div>
    <div class="kpi-value {{ $kpis['pratos_comprometidos'] > 0 ? 'amber' : 'dim' }}">{{ $kpis['pratos_comprometidos'] }}</div>
    <div class="kpi-sub">
      @if($kpis['pratos_comprometidos'] > 0)
        <span class="badge badge-warn">⚠ atenção</span> fora de condição
      @else
        <span class="badge badge-ok">✓ ok</span> todos disponíveis
      @endif
    </div>
  </div>
  <div class="kpi c-red">
    <div class="kpi-label">Alertas Ativos</div>
    <div class="kpi-value {{ $kpis['estoque_critico'] > 0 ? 'red' : 'dim' }}">{{ $kpis['estoque_critico'] }}</div>
    <div class="kpi-sub">
      @if($kpis['estoque_critico'] > 0)
        <span class="badge badge-crit">crítico</span> abaixo do mínimo
      @else
        <span class="badge badge-ok">✓ estável</span>
      @endif
    </div>
  </div>
</div>

{{-- ── KPIs LINHA 2: operacional ── --}}
<div class="kpi-row-4">
  <div class="kpi c-orange">
    <div class="kpi-label">Faturamento Hoje</div>
    <div class="kpi-value orange">R$&nbsp;{{ number_format($kpis['faturamento_hoje'], 2, ',', '.') }}</div>
    <div class="kpi-sub">receita do dia</div>
  </div>
  <div class="kpi c-blue">
    <div class="kpi-label">Pedidos Hoje</div>
    <div class="kpi-value blue">{{ $kpis['pedidos_hoje'] }}</div>
    <div class="kpi-sub">
      <span class="badge" style="background:rgba(29,111,223,.1);color:var(--blue)">{{ $kpis['pedidos_ativos'] }} ativos</span>
      &middot; {{ $kpis['cancelados_hoje'] }} cancelados
    </div>
  </div>
  <div class="kpi c-green">
    <div class="kpi-label">Ticket Médio</div>
    <div class="kpi-value green">R$&nbsp;{{ number_format($kpis['ticket_medio'], 2, ',', '.') }}</div>
    <div class="kpi-sub">por pedido aprovado</div>
  </div>
  <div class="kpi c-purple">
    <div class="kpi-label">Clientes Hoje</div>
    <div class="kpi-value purple">{{ $kpis['clientes_hoje'] }}</div>
    <div class="kpi-sub">clientes únicos</div>
  </div>
</div>

{{-- ── LINHA 1: Gráfico + Top Pratos ── --}}
<div class="row-chart">
  <div class="panel">
    <div class="panel-head">
      <span class="panel-title">Faturamento — Últimos 7 dias</span>
      <span class="panel-chip">semanal</span>
    </div>
    <div class="panel-body">
      <div class="chart-area">
        <canvas id="vendasChart"></canvas>
      </div>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <span class="panel-title">Top 5 Pratos do Mês</span>
      <a href="{{ route('admin.cardapio.index') }}" class="panel-link">Ver cardápio →</a>
    </div>
    <div class="panel-body">
      @php $maxQty = $topPratos->max('total') ?: 1; @endphp
      @if($topPratos->isEmpty())
        <div class="empty"><div class="empty-ico">🍽️</div>Nenhum dado disponível</div>
      @else
      <div class="rank-list">
        @foreach($topPratos as $i => $prato)
        <div class="rank-item">
          <div class="rank-n {{ $i === 0 ? 'gold' : '' }}">{{ $i + 1 }}</div>
          <div class="rank-info">
            <div class="rank-name">{{ $prato->nome }}</div>
            <div class="rank-bar-bg">
              <div class="rank-bar-fg" style="width: {{ round($prato->total / $maxQty * 100) }}%"></div>
            </div>
            <div class="rank-rev">R$ {{ number_format($prato->receita, 2, ',', '.') }}</div>
          </div>
          <div class="rank-qty">{{ $prato->total }}×</div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>

{{-- ── LINHA 2: Comprometidos + Ingredientes ── --}}
<div class="row-mid">
  <div class="panel">
    <div class="panel-head">
      <span class="panel-title">Pratos Comprometidos</span>
      @if($pratosComprometidos->isNotEmpty())
        <span class="panel-chip" style="background:rgba(217,119,6,.07);color:var(--amber);border-color:rgba(217,119,6,.2)">
          {{ $pratosComprometidos->count() }} prato{{ $pratosComprometidos->count() > 1 ? 's' : '' }}
        </span>
      @endif
    </div>
    <div class="panel-body">
      @if($pratosComprometidos->isEmpty())
        <div class="empty"><div class="empty-ico">✅</div>Todos os pratos com estoque completo</div>
      @else
      <div class="alert-list">
        @foreach($pratosComprometidos as $prato)
        <div class="alert-item">
          <div class="alert-item-name">{{ $prato->nome }}</div>
          <div class="alert-item-cat">{{ $prato->categoria->nome ?? 'Sem categoria' }}</div>
          <div class="tags">
            @foreach($prato->insumos as $insumo)
              <span class="tag">{{ $insumo->nome }}</span>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <span class="panel-title">Ingredientes Mais Usados</span>
      <a href="{{ route('admin.estoque.index') }}" class="panel-link">Gerenciar →</a>
    </div>
    <div class="panel-body">
      @if($ingredientesMaisUsados->isNotEmpty())
      <div class="donut-row">
        <canvas id="donutChart" class="donut-canvas"></canvas>
        <div class="legend" id="donutLegend"></div>
      </div>
      @endif
      @if($ingredientesMaisUsados->isEmpty())
        <div class="empty"><div class="empty-ico">📦</div>Sem dados de consumo ainda</div>
      @else
      <div class="ingr-list">
        @foreach($ingredientesMaisUsados as $ingrediente)
        <div class="ingr-item">
          <div class="ingr-dot"></div>
          <div style="flex:1;min-width:0">
            <div class="ingr-name">{{ $ingrediente->nome }}</div>
            <div class="ingr-consumo">{{ number_format($ingrediente->consumo_estimado, 3, ',', '.') }} {{ $ingrediente->unidade }}</div>
          </div>
          <div class="ingr-custo">R$ {{ number_format($ingrediente->custo_estimado, 2, ',', '.') }}</div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>

{{-- ── LINHA 3: Pedidos recentes + mini stats + bar chart ── --}}
<div class="row-bot">
  <div class="panel">
    <div class="panel-head">
      <span class="panel-title">Pedidos Recentes</span>
      <a href="{{ route('admin.pedidos.index') }}" class="panel-link">Ver todos →</a>
    </div>
    <div class="panel-body" style="padding-top:0">
      @if($pedidosRecentes->isEmpty())
        <div class="empty"><div class="empty-ico">📋</div>Nenhum pedido registrado</div>
      @else
      <table class="orders-table">
        <thead>
          <tr>
            <th>Pedido</th>
            <th>Cliente</th>
            <th>Status</th>
            <th style="text-align:right">Valor</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pedidosRecentes as $pedido)
          @php
            $statusMap = [
              'confirmado'     => ['label' => 'Confirmado', 'class' => 's-confirmado'],
              'em_producao'    => ['label' => 'Produção',   'class' => 's-em_producao'],
              'saindo_entrega' => ['label' => 'A caminho',  'class' => 's-saindo'],
              'entregue'       => ['label' => 'Entregue',   'class' => 's-entregue'],
              'cancelado'      => ['label' => 'Cancelado',  'class' => 's-cancelado'],
            ];
            $s = $statusMap[$pedido->status] ?? ['label' => ucfirst($pedido->status), 'class' => 's-pendente'];
          @endphp
          <tr>
            <td>
              <div class="order-id">#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</div>
              <div style="font-size:10.5px;color:var(--muted);margin-top:1px">{{ $pedido->created_at->format('d/m H:i') }}</div>
            </td>
            <td>
              <div class="order-name">{{ $pedido->usuario?->name ?? 'Cliente' }}</div>
              <div class="order-items">{{ $pedido->itens?->count() ?? 0 }} {{ ($pedido->itens?->count() ?? 0) === 1 ? 'item' : 'itens' }}</div>
            </td>
            <td><span class="status-pill {{ $s['class'] }}">{{ $s['label'] }}</span></td>
            <td style="text-align:right">
              <div class="order-val">R$ {{ number_format($pedido->total, 2, ',', '.') }}</div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:20px">
    <div class="mini-stats">
      <div class="mini-stat">
        <div class="mini-stat-ico" style="background:rgba(29,111,223,.1)">🛵</div>
        <div>
          <div class="mini-stat-label">Pedidos Ativos</div>
          <div class="mini-stat-val" style="color:var(--blue)">{{ $kpis['pedidos_ativos'] }}</div>
        </div>
      </div>
      <div class="mini-stat">
        <div class="mini-stat-ico" style="background:rgba(220,38,38,.1)">✕</div>
        <div>
          <div class="mini-stat-label">Cancelamentos Hoje</div>
          <div class="mini-stat-val" style="color:var(--red)">{{ $kpis['cancelados_hoje'] }}</div>
        </div>
      </div>
      <div class="mini-stat">
        <div class="mini-stat-ico" style="background:rgba(5,150,105,.1)">👥</div>
        <div>
          <div class="mini-stat-label">Clientes Únicos Hoje</div>
          <div class="mini-stat-val" style="color:var(--green)">{{ $kpis['clientes_hoje'] }}</div>
        </div>
      </div>
    </div>

    <div class="panel" style="flex:1">
      <div class="panel-head">
        <span class="panel-title">Status dos Pedidos</span>
        <span class="panel-chip">hoje</span>
      </div>
      <div class="panel-body">
        <div style="height:130px;position:relative">
          <canvas id="statusChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Sora', sans-serif";
Chart.defaults.color = '#7a8db5';

const ACCENT = '#2558c4', GREEN = '#059669', BLUE  = '#1d6fdf',
      AMBER  = '#d97706', RED   = '#dc2626', PURPLE = '#6d28d9';

/* ── Vendas 7 dias (linha dupla: faturamento + pedidos) ── */
const vCtx = document.getElementById('vendasChart');
if (vCtx) {
  const labels  = {!! json_encode($vendas7dias->pluck('data')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!};
  const values  = {!! json_encode($vendas7dias->pluck('total')) !!};
  const pedidos = {!! json_encode($vendas7dias->pluck('pedidos')) !!};

  new Chart(vCtx, {
    type: 'line',
    data: {
      labels,
      datasets: [
        {
          label: 'Faturamento (R$)',
          data: values,
          yAxisID: 'y',
          borderColor: ACCENT,
          backgroundColor: ctx => {
            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 210);
            g.addColorStop(0,   'rgba(37,88,196,.18)');
            g.addColorStop(.8,  'rgba(37,88,196,.03)');
            g.addColorStop(1,   'rgba(37,88,196,0)');
            return g;
          },
          borderWidth: 2.5, fill: true, tension: 0.42,
          pointBackgroundColor: ACCENT, pointBorderColor: '#ffffff',
          pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
        },
        {
          label: 'Pedidos',
          data: pedidos,
          yAxisID: 'y2',
          borderColor: BLUE,
          backgroundColor: 'transparent',
          borderWidth: 1.5, tension: 0.42,
          borderDash: [4, 3],
          pointRadius: 0, pointHoverRadius: 4,
          pointBackgroundColor: BLUE,
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#ffffff',
          borderColor: 'rgba(15,40,100,0.12)', borderWidth: 1,
          titleColor: '#0d1b38', bodyColor: '#4e6490',
          padding: 12, cornerRadius: 8,
          callbacks: {
            label: ctx => ctx.datasetIndex === 0
              ? ` R$ ${ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits:2})}`
              : ` ${ctx.parsed.y} pedidos`
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true, position: 'left',
          grid: { color: 'rgba(15,40,100,0.05)', drawBorder: false },
          ticks: { callback: v => 'R$' + v.toLocaleString('pt-BR'), font: { size: 10 } }
        },
        y2: {
          beginAtZero: true, position: 'right',
          grid: { display: false },
          ticks: { font: { size: 10 }, stepSize: 1 }
        },
        x: {
          grid: { display: false },
          ticks: { font: { size: 10 } }
        }
      }
    }
  });
}

/* ── Donut: custo por ingrediente ── */
const dCtx = document.getElementById('donutChart');
if (dCtx) {
  const ingrs  = {!! json_encode($ingredientesMaisUsados->pluck('nome')) !!};
  const custos = {!! json_encode($ingredientesMaisUsados->pluck('custo_estimado')) !!};
  const colors = [ACCENT, GREEN, BLUE, AMBER, RED, PURPLE];
  const total  = custos.reduce((a, b) => a + b, 0) || 1;

  new Chart(dCtx, {
    type: 'doughnut',
    data: {
      labels: ingrs,
      datasets: [{ data: custos, backgroundColor: colors, borderColor: '#ffffff', borderWidth: 3 }]
    },
    options: {
      responsive: false, cutout: '74%',
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#ffffff',
          borderColor: 'rgba(15,40,100,0.12)', borderWidth: 1,
          titleColor: '#0d1b38', bodyColor: '#4e6490',
          callbacks: { label: ctx => ` R$ ${Number(ctx.raw).toLocaleString('pt-BR', {minimumFractionDigits:2})}` }
        }
      }
    }
  });

  const lg = document.getElementById('donutLegend');
  if (lg) {
    ingrs.forEach((name, i) => {
      lg.innerHTML += `<div class="legend-item">
        <div class="legend-dot" style="background:${colors[i]}"></div>
        <span class="legend-label">${name}</span>
        <span class="legend-pct" style="color:${colors[i]}">${Math.round(custos[i] / total * 100)}%</span>
      </div>`;
    });
  }
}

/* ── Bar: pedidos por status (dos recentes) ── */
const sCtx = document.getElementById('statusChart');
if (sCtx) {
  const sLabels = ['Confirmado', 'Produção', 'A caminho', 'Entregue', 'Cancelado'];
  const sColors = [BLUE, AMBER, PURPLE, GREEN, RED];
  const sData   = [
    {{ $pedidosRecentes->where('status','confirmado')->count() }},
    {{ $pedidosRecentes->where('status','em_producao')->count() }},
    {{ $pedidosRecentes->where('status','saindo_entrega')->count() }},
    {{ $pedidosRecentes->where('status','entregue')->count() }},
    {{ $pedidosRecentes->where('status','cancelado')->count() }},
  ];

  new Chart(sCtx, {
    type: 'bar',
    data: {
      labels: sLabels,
      datasets: [{
        data: sData,
        backgroundColor: sColors.map(c => c + '18'),
        borderColor: sColors,
        borderWidth: 1.5, borderRadius: 5, borderSkipped: false,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#ffffff',
          borderColor: 'rgba(15,40,100,0.12)', borderWidth: 1,
          titleColor: '#0d1b38', bodyColor: '#4e6490',
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(15,40,100,0.05)', drawBorder: false },
          ticks: { stepSize: 1, font: { size: 9 } }
        },
        x: {
          grid: { display: false },
          ticks: { font: { size: 9 } }
        }
      }
    }
  });
}
</script>
@endpush
