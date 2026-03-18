<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin — Bella Cucina</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<style>
:root{
  --bg:    #F4F1EC;
  --bg2:   #FFFFFF;
  --bg3:   #FAF8F4;
  --dark:  #1C1A17;
  --dark2: #2E2B26;
  --amber: #C47A1A;
  --amber-l:#F0A030;
  --amber-bg:#FEF3E0;
  --green: #2A7D4F;
  --green-bg:#E8F5EE;
  --red:   #B83232;
  --red-bg:#FEE8E8;
  --blue:  #1A5FA8;
  --blue-bg:#E8F0FD;
  --text:  #1C1A17;
  --text-m:#6B6455;
  --text-s:#9C9283;
  --border:#E8E1D5;
  --font-h:'Syne', sans-serif;
  --font-b:'DM Sans', sans-serif;
  --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:var(--font-b);min-height:100vh;display:flex}

/* SIDEBAR NAV */
.sidenav{
  width:240px;flex-shrink:0;background:var(--dark);
  display:flex;flex-direction:column;
  border-right:1px solid rgba(255,255,255,0.06);
  position:fixed;top:0;bottom:0;left:0;z-index:50;
}
.sidenav-logo{
  padding:1.5rem 1.25rem;border-bottom:1px solid rgba(255,255,255,0.06);
  font-family:var(--font-h);font-size:1.1rem;color:#E8A045;
  display:flex;flex-direction:column;gap:.15rem;
}
.sidenav-logo span{font-size:.65rem;color:rgba(255,255,255,0.35);font-family:var(--font-b);font-weight:400;letter-spacing:.1em;text-transform:uppercase}
.sidenav-section{font-size:.62rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,0.25);padding:.75rem 1.25rem .4rem;margin-top:.5rem}
.nav-item{
  display:flex;align-items:center;gap:.75rem;
  padding:.65rem 1.25rem;font-size:.875rem;color:rgba(255,255,255,0.55);
  cursor:pointer;transition:.15s;text-decoration:none;border-radius:0;
  border-left:3px solid transparent;
}
.nav-item:hover{background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.85)}
.nav-item.active{background:rgba(232,160,69,0.1);color:#E8A045;border-left-color:#E8A045}
.nav-icon{width:16px;height:16px;opacity:.7}
.nav-badge{margin-left:auto;background:var(--red);color:#fff;font-size:.65rem;font-weight:700;padding:.1rem .45rem;border-radius:10px}
.sidenav-footer{margin-top:auto;padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,0.06)}
.user-chip{display:flex;align-items:center;gap:.75rem}
.user-avatar{width:32px;height:32px;border-radius:50%;background:var(--amber);display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:var(--dark);flex-shrink:0}
.user-info{flex:1;min-width:0}
.user-name{font-size:.8rem;color:rgba(255,255,255,0.8);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-role{font-size:.65rem;color:rgba(255,255,255,0.3)}

/* MAIN */
.main{margin-left:240px;flex:1;display:flex;flex-direction:column;min-height:100vh}

/* TOP BAR */
.topbar{
  background:var(--bg2);border-bottom:1px solid var(--border);
  padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:40;
}
.page-title{font-family:var(--font-h);font-size:1rem;font-weight:600;color:var(--dark)}
.topbar-right{display:flex;align-items:center;gap:1rem}
.topbar-btn{
  display:flex;align-items:center;gap:.5rem;
  padding:.45rem .9rem;border-radius:8px;
  font-size:.8rem;font-weight:500;cursor:pointer;border:none;
  font-family:var(--font-b);transition:.2s;
}
.btn-secondary{background:var(--bg3);color:var(--text-m);border:1px solid var(--border)}
.btn-primary{background:var(--dark);color:#fff}
.btn-primary:hover{background:var(--dark2)}

/* CONTENT */
.content{padding:1.75rem 2rem;flex:1}

/* PERIOD SELECTOR */
.period-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
.period-title{font-family:var(--font-h);font-size:1.4rem;font-weight:600}
.period-tabs{display:flex;background:var(--bg2);border:1px solid var(--border);border-radius:8px;overflow:hidden}
.period-tab{padding:.45rem 1rem;font-size:.8rem;font-weight:500;cursor:pointer;color:var(--text-m);border:none;background:none;font-family:var(--font-b)}
.period-tab.active{background:var(--dark);color:#fff}

/* KPI GRID */
.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
.kpi-card{
  background:var(--bg2);border:1px solid var(--border);
  border-radius:12px;padding:1.25rem 1.5rem;
  display:flex;flex-direction:column;gap:.5rem;
  box-shadow:var(--shadow);transition:.2s;
}
.kpi-card:hover{transform:translateY(-1px);box-shadow:0 4px 20px rgba(0,0,0,0.08)}
.kpi-label{font-size:.75rem;font-weight:500;color:var(--text-s);letter-spacing:.03em;text-transform:uppercase}
.kpi-value{font-family:var(--font-h);font-size:1.9rem;font-weight:700;line-height:1;color:var(--text)}
.kpi-delta{font-size:.78rem;display:flex;align-items:center;gap:.3rem}
.delta-up{color:var(--green)}
.delta-dn{color:var(--red)}
.kpi-accent{border-top:3px solid var(--amber)}
.kpi-badge{
  align-self:flex-start;font-size:.65rem;font-weight:600;
  padding:.2rem .6rem;border-radius:6px;
}
.badge-amber{background:var(--amber-bg);color:var(--amber)}
.badge-green{background:var(--green-bg);color:var(--green)}
.badge-red{background:var(--red-bg);color:var(--red)}
.badge-blue{background:var(--blue-bg);color:var(--blue)}

/* GRID PRINCIPAL */
.grid-2{display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;margin-bottom:1.25rem}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:1.25rem}
.card{
  background:var(--bg2);border:1px solid var(--border);
  border-radius:12px;padding:1.25rem 1.5rem;
  box-shadow:var(--shadow);
}
.card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem}
.card-title{font-family:var(--font-h);font-size:.95rem;font-weight:600;color:var(--dark)}
.card-action{font-size:.78rem;color:var(--amber);cursor:pointer;font-weight:500;border:none;background:none;font-family:var(--font-b)}
.card-action:hover{color:var(--amber-l)}

/* CHART */
.chart-wrap{height:220px;position:relative}

/* TABELA DE PEDIDOS */
table{width:100%;border-collapse:collapse}
th{font-size:.7rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--text-s);padding:.6rem 1rem;text-align:left;border-bottom:2px solid var(--border)}
td{font-size:.82rem;padding:.75rem 1rem;border-bottom:1px solid var(--border);color:var(--text-m)}
td:first-child{color:var(--text);font-weight:500}
tr:hover td{background:var(--bg3)}
tr:last-child td{border-bottom:none}
.status-pill{
  display:inline-flex;align-items:center;gap:.35rem;
  font-size:.7rem;font-weight:600;padding:.25rem .65rem;border-radius:20px;
}
.pill-blue{background:var(--blue-bg);color:var(--blue)}
.pill-amber{background:var(--amber-bg);color:var(--amber)}
.pill-green{background:var(--green-bg);color:var(--green)}
.pill-red{background:var(--red-bg);color:var(--red)}
.pill-gray{background:var(--bg3);color:var(--text-s);border:1px solid var(--border)}

/* TOP PRATOS */
.prato-row{display:flex;align-items:center;gap:.75rem;padding:.7rem 0;border-bottom:1px solid var(--border)}
.prato-row:last-child{border-bottom:none}
.prato-rank{font-family:var(--font-h);font-size:1rem;font-weight:700;color:var(--border);min-width:24px}
.prato-rank.top{color:var(--amber)}
.prato-info{flex:1;min-width:0}
.prato-nome{font-size:.875rem;font-weight:500;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.prato-vendas{font-size:.75rem;color:var(--text-s);margin-top:.1rem}
.prato-bar-wrap{width:60px;height:4px;background:var(--border);border-radius:2px;overflow:hidden;margin-left:.5rem}
.prato-bar{height:100%;background:var(--amber);border-radius:2px}
.prato-receita{font-size:.8rem;font-weight:600;color:var(--text);text-align:right;min-width:70px}

/* ESTOQUE CRÍTICO */
.estoque-item{padding:.7rem 0;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.75rem}
.estoque-item:last-child{border-bottom:none}
.estoque-info{flex:1}
.estoque-nome{font-size:.875rem;font-weight:500;color:var(--text)}
.estoque-qtd{font-size:.75rem;color:var(--text-s);margin-top:.15rem}
.estoque-bar-wrap{width:80px}
.estoque-bar-track{height:5px;background:var(--border);border-radius:3px;overflow:hidden;margin-bottom:.2rem}
.estoque-bar-fill{height:100%;border-radius:3px}
.fill-red{background:var(--red)}
.fill-amber{background:var(--amber-l)}
.fill-green{background:var(--green)}
.estoque-pct{font-size:.65rem;color:var(--text-s);text-align:right}
.btn-repor{padding:.25rem .65rem;font-size:.72rem;background:var(--amber-bg);color:var(--amber);border:1px solid rgba(196,122,26,0.2);border-radius:6px;cursor:pointer;font-family:var(--font-b);font-weight:500}

/* ATIVIDADE */
.atividade-item{display:flex;gap:.75rem;align-items:flex-start;padding:.6rem 0;border-bottom:1px solid var(--border)}
.atividade-item:last-child{border-bottom:none}
.atividade-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:.4rem}
.atividade-text{font-size:.82rem;color:var(--text-m);line-height:1.4}
.atividade-text strong{color:var(--text);font-weight:500}
.atividade-time{font-size:.72rem;color:var(--text-s);margin-top:.15rem}

@media(max-width:1200px){.kpi-grid{grid-template-columns:repeat(2,1fr)}.grid-2{grid-template-columns:1fr}}
@media(max-width:900px){.sidenav{transform:translateX(-100%)}.main{margin-left:0}}
</style>
</head>
<body>

<!-- SIDEBAR -->
<nav class="sidenav">
  <div class="sidenav-logo">
    Bella Cucina
    <span>Painel Administrativo</span>
  </div>

  <div class="sidenav-section">Principal</div>
  <a href="#" class="nav-item active">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="#" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    Pedidos
    <span class="nav-badge">3</span>
  </a>
  <a href="#" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
    Ao Vivo (Cozinha)
  </a>

  <div class="sidenav-section">Gestão</div>
  <a href="#" class="nav-item" onclick="mostrarSecao('cardapio')">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    Cardápio
  </a>
  <a href="#" class="nav-item" onclick="mostrarSecao('estoque')">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
    Estoque
    <span class="nav-badge">2</span>
  </a>
  <a href="#" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
    Funcionários
  </a>
  <a href="#" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    Clientes
  </a>

  <div class="sidenav-section">Análise</div>
  <a href="#" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/></svg>
    Relatórios
  </a>
  <a href="#" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16,8 20,8 23,11 23,16 16,16"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Entregas
  </a>

  <div class="sidenav-section">Sistema</div>
  <a href="#" class="nav-item">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
    Configurações
  </a>

  <div class="sidenav-footer">
    <div class="user-chip">
      <div class="user-avatar">A</div>
      <div class="user-info">
        <div class="user-name">Administrador</div>
        <div class="user-role">Super Admin</div>
      </div>
    </div>
  </div>
</nav>

<!-- MAIN -->
<div class="main">
  <!-- TOP BAR -->
  <div class="topbar">
    <div class="page-title">Dashboard — Visão Geral</div>
    <div class="topbar-right">
      <span style="font-size:.78rem;color:var(--text-s)">Atualizado há 2 min</span>
      <button class="topbar-btn btn-secondary">📥 Exportar</button>
      <button class="topbar-btn btn-primary">+ Novo Prato</button>
    </div>
  </div>

  <div class="content">
    <!-- PERIOD -->
    <div class="period-bar">
      <div class="period-title">Hoje, 18 de Março</div>
      <div class="period-tabs">
        <button class="period-tab active">Hoje</button>
        <button class="period-tab">7 dias</button>
        <button class="period-tab">30 dias</button>
        <button class="period-tab">Mês</button>
      </div>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
      <div class="kpi-card kpi-accent">
        <div class="kpi-label">Faturamento Hoje</div>
        <div class="kpi-value">R$ 2.847</div>
        <div class="kpi-delta delta-up">▲ +12,4% vs ontem</div>
        <span class="kpi-badge badge-green">🟢 Meta atingida</span>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Pedidos Hoje</div>
        <div class="kpi-value">34</div>
        <div class="kpi-delta delta-up">▲ +5 vs ontem</div>
        <span class="kpi-badge badge-blue">3 ativos agora</span>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Ticket Médio</div>
        <div class="kpi-value">R$ 83,74</div>
        <div class="kpi-delta delta-dn">▼ −3,1% vs ontem</div>
        <span class="kpi-badge badge-amber">Abaixo da meta</span>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Avaliação Média</div>
        <div class="kpi-value">4.8 ⭐</div>
        <div class="kpi-delta delta-up">▲ +0.2 esta semana</div>
        <span class="kpi-badge badge-green">127 avaliações</span>
      </div>
    </div>

    <!-- LINHA 2: GRÁFICO + TOP PRATOS -->
    <div class="grid-2">
      <div class="card">
        <div class="card-header">
          <span class="card-title">Faturamento — Últimos 7 dias</span>
          <button class="card-action">Ver detalhes →</button>
        </div>
        <div class="chart-wrap">
          <canvas id="chartVendas"></canvas>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <span class="card-title">Top Pratos (30 dias)</span>
          <button class="card-action">Ver todos →</button>
        </div>
        <div class="prato-row">
          <div class="prato-rank top">1</div>
          <div class="prato-info">
            <div class="prato-nome">Filé Mignon Grelhado</div>
            <div class="prato-vendas">142 vendidos</div>
          </div>
          <div class="prato-bar-wrap"><div class="prato-bar" style="width:100%"></div></div>
          <div class="prato-receita">R$ 13.348</div>
        </div>
        <div class="prato-row">
          <div class="prato-rank top">2</div>
          <div class="prato-info">
            <div class="prato-nome">Risoto de Cogumelos</div>
            <div class="prato-vendas">118 vendidos</div>
          </div>
          <div class="prato-bar-wrap"><div class="prato-bar" style="width:83%"></div></div>
          <div class="prato-receita">R$ 7.316</div>
        </div>
        <div class="prato-row">
          <div class="prato-rank top">3</div>
          <div class="prato-info">
            <div class="prato-nome">Tagliatelle Bolognese</div>
            <div class="prato-vendas">97 vendidos</div>
          </div>
          <div class="prato-bar-wrap"><div class="prato-bar" style="width:68%"></div></div>
          <div class="prato-receita">R$ 4.360</div>
        </div>
        <div class="prato-row">
          <div class="prato-rank">4</div>
          <div class="prato-info">
            <div class="prato-nome">Bruschetta Clássica</div>
            <div class="prato-vendas">84 vendidos</div>
          </div>
          <div class="prato-bar-wrap"><div class="prato-bar" style="width:59%"></div></div>
          <div class="prato-receita">R$ 2.688</div>
        </div>
        <div class="prato-row">
          <div class="prato-rank">5</div>
          <div class="prato-info">
            <div class="prato-nome">Tiramisù Artesanal</div>
            <div class="prato-vendas">71 vendidos</div>
          </div>
          <div class="prato-bar-wrap"><div class="prato-bar" style="width:50%"></div></div>
          <div class="prato-receita">R$ 2.698</div>
        </div>
      </div>
    </div>

    <!-- LINHA 3: PEDIDOS RECENTES + ESTOQUE + ATIVIDADE -->
    <div class="grid-2">
      <div class="card">
        <div class="card-header">
          <span class="card-title">Pedidos Recentes</span>
          <button class="card-action">Ver todos →</button>
        </div>
        <table>
          <thead>
            <tr>
              <th>Código</th><th>Cliente</th><th>Total</th><th>Status</th><th>Horário</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#AB3K9X2M</td><td>Carlos Mendes</td><td>R$ 194,80</td>
              <td><span class="status-pill pill-amber">⏳ Em produção</span></td>
              <td style="color:var(--text-s)">14:23</td>
            </tr>
            <tr>
              <td>#XR72MK9P</td><td>Ana Lima</td><td>R$ 82,90</td>
              <td><span class="status-pill pill-blue">🔵 Confirmado</span></td>
              <td style="color:var(--text-s)">14:31</td>
            </tr>
            <tr>
              <td>#PQ91LF4C</td><td>Roberto Costa</td><td>R$ 156,00</td>
              <td><span class="status-pill pill-amber">🍳 Em produção</span></td>
              <td style="color:var(--text-s)">14:18</td>
            </tr>
            <tr>
              <td>#MN55KJ2R</td><td>Fernanda Souza</td><td>R$ 67,80</td>
              <td><span class="status-pill pill-green">✅ Entregue</span></td>
              <td style="color:var(--text-s)">13:52</td>
            </tr>
            <tr>
              <td>#GH28PL7X</td><td>Paulo Barros</td><td>R$ 248,50</td>
              <td><span class="status-pill pill-green">✅ Entregue</span></td>
              <td style="color:var(--text-s)">13:40</td>
            </tr>
            <tr>
              <td>#KC63DW9A</td><td>Júlia Martins</td><td>R$ 43,00</td>
              <td><span class="status-pill pill-red">✕ Cancelado</span></td>
              <td style="color:var(--text-s)">13:15</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div style="display:flex;flex-direction:column;gap:1.25rem">
        <!-- ESTOQUE CRÍTICO -->
        <div class="card">
          <div class="card-header">
            <span class="card-title">⚠️ Estoque Crítico</span>
            <button class="card-action">Gerenciar →</button>
          </div>
          <div class="estoque-item">
            <div class="estoque-info">
              <div class="estoque-nome">Filé Mignon</div>
              <div class="estoque-qtd">0.8 kg restante · Mínimo: 2 kg</div>
            </div>
            <div class="estoque-bar-wrap">
              <div class="estoque-bar-track"><div class="estoque-bar-fill fill-red" style="width:40%"></div></div>
              <div class="estoque-pct">40%</div>
            </div>
            <button class="btn-repor">Repor</button>
          </div>
          <div class="estoque-item">
            <div class="estoque-info">
              <div class="estoque-nome">Camarão VG</div>
              <div class="estoque-qtd">1.2 kg restante · Mínimo: 1 kg</div>
            </div>
            <div class="estoque-bar-wrap">
              <div class="estoque-bar-track"><div class="estoque-bar-fill fill-amber" style="width:120%"></div></div>
              <div class="estoque-pct">120%</div>
            </div>
            <button class="btn-repor">OK</button>
          </div>
          <div class="estoque-item">
            <div class="estoque-info">
              <div class="estoque-nome">Cogumelo Paris</div>
              <div class="estoque-qtd">400g restante · Mínimo: 800g</div>
            </div>
            <div class="estoque-bar-wrap">
              <div class="estoque-bar-track"><div class="estoque-bar-fill fill-red" style="width:50%"></div></div>
              <div class="estoque-pct">50%</div>
            </div>
            <button class="btn-repor">Repor</button>
          </div>
        </div>

        <!-- ATIVIDADE RECENTE -->
        <div class="card">
          <div class="card-header">
            <span class="card-title">Atividade Recente</span>
          </div>
          <div class="atividade-item">
            <div class="atividade-dot" style="background:var(--green)"></div>
            <div>
              <div class="atividade-text">Pedido <strong>#GH28PL7X</strong> entregue com sucesso</div>
              <div class="atividade-time">Há 12 minutos</div>
            </div>
          </div>
          <div class="atividade-item">
            <div class="atividade-dot" style="background:var(--amber)"></div>
            <div>
              <div class="atividade-text">Estoque de <strong>Filé Mignon</strong> atingiu nível crítico</div>
              <div class="atividade-time">Há 25 minutos</div>
            </div>
          </div>
          <div class="atividade-item">
            <div class="atividade-dot" style="background:var(--blue)"></div>
            <div>
              <div class="atividade-text"><strong>Ana Lima</strong> criou nova conta e fez 1º pedido</div>
              <div class="atividade-time">Há 31 minutos</div>
            </div>
          </div>
          <div class="atividade-item">
            <div class="atividade-dot" style="background:var(--red)"></div>
            <div>
              <div class="atividade-text">Pedido <strong>#KC63DW9A</strong> cancelado pelo cliente</div>
              <div class="atividade-time">Há 57 minutos</div>
            </div>
          </div>
          <div class="atividade-item">
            <div class="atividade-dot" style="background:var(--green)"></div>
            <div>
              <div class="atividade-text">Avaliação 5⭐ recebida de <strong>Roberto Costa</strong></div>
              <div class="atividade-time">Há 1 hora</div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /content -->
</div><!-- /main -->

<script>
// ---- GRÁFICO ----
const ctx = document.getElementById('chartVendas').getContext('2d');
const dias = ['Qui','Sex','Sáb','Dom','Seg','Ter','Hoje'];
const valores = [1820, 2640, 3120, 2480, 1950, 2210, 2847];
const meta = [2500, 2500, 2500, 2500, 2500, 2500, 2500];

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: dias,
    datasets: [
      {
        label: 'Faturamento',
        data: valores,
        backgroundColor: valores.map((v,i) => i === 6 ? '#C47A1A' : 'rgba(196,122,26,0.35)'),
        borderRadius: 6,
        borderSkipped: false,
        order: 2,
      },
      {
        label: 'Meta',
        data: meta,
        type: 'line',
        borderColor: 'rgba(42,125,79,0.5)',
        borderDash: [4,4],
        borderWidth: 1.5,
        pointRadius: 0,
        fill: false,
        order: 1,
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#1C1A17',
        titleColor: '#F4F1EC',
        bodyColor: '#9C9283',
        padding: 10,
        callbacks: {
          label: ctx => `R$ ${ctx.parsed.y.toLocaleString('pt-BR')}`
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: { color: '#9C9283', font: { size: 11, family: 'DM Sans' } }
      },
      y: {
        grid: { color: 'rgba(0,0,0,0.05)' },
        ticks: {
          color: '#9C9283',
          font: { size: 11, family: 'DM Sans' },
          callback: v => 'R$ ' + (v/1000).toFixed(1) + 'k'
        }
      }
    }
  }
});

// ---- PERIOD TABS ----
document.querySelectorAll('.period-tab').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.period-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  });
});

// ---- MOSTRAR SEÇÃO ----
function mostrarSecao(secao) {
  // Navegação SPA simulada — em produção navegar por route()
  console.log('Navegar para:', secao);
}
</script>
</body>
</html>