<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tela da Cozinha — Bella Cucina</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --bg:    #0D0D0D;
  --bg2:   #141414;
  --bg3:   #1A1A1A;
  --col:   rgba(255,255,255,0.06);
  --text:  #F0F0F0;
  --text-m:#A0A0A0;
  --text-s:#606060;
  --amber: #F5A623;
  --green: #2ECC71;
  --red:   #E74C3C;
  --blue:  #3498DB;
  --orange:#E67E22;
  --mono:  'JetBrains Mono', monospace;
  --sans:  'DM Sans', sans-serif;
}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:var(--sans);min-height:100vh;display:flex;flex-direction:column}

/* TOP BAR */
.topbar{
  background:var(--bg2);border-bottom:1px solid var(--col);
  padding:0 1.5rem;height:56px;display:flex;align-items:center;justify-content:space-between;
}
.topbar-left{display:flex;align-items:center;gap:1.5rem}
.topbar-logo{font-family:var(--mono);font-size:.85rem;color:var(--amber);letter-spacing:.05em;font-weight:700}
.topbar-status{display:flex;align-items:center;gap:.5rem;font-size:.75rem;color:var(--green)}
.status-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
.topbar-clock{font-family:var(--mono);font-size:1.1rem;font-weight:700;color:var(--text);letter-spacing:.05em}
.topbar-right{display:flex;align-items:center;gap:.75rem}
.topbar-stat{
  background:var(--bg3);border:1px solid var(--col);
  border-radius:8px;padding:.35rem .85rem;
  text-align:center;font-size:.7rem;
}
.stat-num{font-family:var(--mono);font-size:1rem;font-weight:700}
.stat-num.amber{color:var(--amber)}
.stat-num.green{color:var(--green)}
.stat-num.red{color:var(--red)}

/* COLUNAS KANBAN */
.kanban{flex:1;display:grid;grid-template-columns:repeat(3,1fr);gap:0;padding:0}
.col{
  display:flex;flex-direction:column;
  border-right:1px solid var(--col);
  overflow:hidden;
}
.col:last-child{border-right:none}
.col-header{
  padding:1rem 1.25rem;
  border-bottom:1px solid var(--col);
  display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:10;
  backdrop-filter:blur(10px);
}
.col-header.confirmado{background:rgba(52,152,219,0.08);border-bottom-color:rgba(52,152,219,0.2)}
.col-header.em_producao{background:rgba(245,166,35,0.08);border-bottom-color:rgba(245,166,35,0.2)}
.col-header.saindo{background:rgba(46,204,113,0.08);border-bottom-color:rgba(46,204,113,0.2)}
.col-title{display:flex;align-items:center;gap:.6rem;font-size:.8rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase}
.col-dot{width:8px;height:8px;border-radius:50%}
.col-count{
  font-family:var(--mono);font-size:.8rem;font-weight:700;
  background:var(--bg3);border:1px solid var(--col);
  padding:.2rem .55rem;border-radius:6px;
}
.col-body{flex:1;overflow-y:auto;padding:.75rem;display:flex;flex-direction:column;gap:.75rem}

/* CARD DE PEDIDO */
.pedido-card{
  background:var(--bg2);border:1px solid var(--col);
  border-radius:12px;overflow:hidden;
  transition:.2s;
}
.pedido-card.urgente{border-color:rgba(231,76,60,0.5);animation:urgente 1.5s ease infinite}
@keyframes urgente{0%,100%{box-shadow:0 0 0 0 rgba(231,76,60,0)}50%{box-shadow:0 0 0 4px rgba(231,76,60,0.2)}}
.pedido-header{
  padding:.75rem 1rem;
  display:flex;align-items:center;justify-content:space-between;
  border-bottom:1px solid var(--col);
}
.pedido-codigo{font-family:var(--mono);font-size:1.1rem;font-weight:700;letter-spacing:.05em}
.pedido-timer{
  font-family:var(--mono);font-size:.8rem;font-weight:600;
  padding:.2rem .5rem;border-radius:6px;
}
.timer-ok{background:rgba(46,204,113,0.12);color:var(--green)}
.timer-warn{background:rgba(245,166,35,0.12);color:var(--amber)}
.timer-bad{background:rgba(231,76,60,0.15);color:var(--red)}
.pedido-cliente{font-size:.75rem;color:var(--text-m);margin-top:.2rem}
.pedido-body{padding:.85rem 1rem}
.item-row{
  display:flex;align-items:flex-start;gap:.75rem;
  padding:.6rem 0;border-bottom:1px solid rgba(255,255,255,0.04);
}
.item-row:last-child{border-bottom:none}
.item-qty{
  font-family:var(--mono);font-size:.85rem;font-weight:700;
  color:var(--amber);min-width:24px;padding-top:.1rem;
}
.item-info{flex:1}
.item-nome{font-size:.875rem;font-weight:500;line-height:1.3}
.item-opts{font-size:.75rem;color:var(--text-s);margin-top:.2rem;font-style:italic}
.item-obs{
  font-size:.72rem;color:var(--amber);
  background:rgba(245,166,35,0.08);border:1px solid rgba(245,166,35,0.2);
  border-radius:5px;padding:.2rem .5rem;margin-top:.3rem;display:inline-block;
}
.pedido-entrega{
  margin:.5rem 0 0;padding:.5rem .85rem;
  background:var(--bg3);border-radius:7px;
  font-size:.75rem;color:var(--text-m);display:flex;align-items:center;gap:.5rem;
}
.pedido-obs-geral{
  margin:.5rem 0 0;padding:.5rem .85rem;
  background:rgba(231,76,60,0.06);border:1px solid rgba(231,76,60,0.15);
  border-radius:7px;font-size:.75rem;color:#E09080;
}
.pedido-footer{
  padding:.75rem 1rem;border-top:1px solid var(--col);
  display:flex;align-items:center;justify-content:space-between;gap:.5rem;
}
.btn-avancar{
  flex:1;padding:.65rem;border:none;border-radius:8px;
  font-family:var(--sans);font-size:.8rem;font-weight:600;cursor:pointer;
  transition:.2s;letter-spacing:.03em;
}
.btn-avancar.confirmar{background:rgba(52,152,219,0.15);color:var(--blue);border:1px solid rgba(52,152,219,0.3)}
.btn-avancar.confirmar:hover{background:rgba(52,152,219,0.25)}
.btn-avancar.produzir{background:rgba(245,166,35,0.15);color:var(--amber);border:1px solid rgba(245,166,35,0.3)}
.btn-avancar.produzir:hover{background:rgba(245,166,35,0.25)}
.btn-avancar.entregar{background:rgba(46,204,113,0.15);color:var(--green);border:1px solid rgba(46,204,113,0.3)}
.btn-avancar.entregar:hover{background:rgba(46,204,113,0.25)}
.btn-cancel{
  padding:.65rem .75rem;border:1px solid rgba(231,76,60,0.3);
  background:rgba(231,76,60,0.08);color:var(--red);
  border-radius:8px;cursor:pointer;font-size:.8rem;transition:.2s;
}
.btn-cancel:hover{background:rgba(231,76,60,0.15)}

/* TOAST / NOTIFICAÇÃO */
.toast{
  position:fixed;top:70px;right:1.5rem;z-index:999;
  background:var(--bg2);border:1px solid rgba(46,204,113,0.3);
  border-radius:10px;padding:1rem 1.25rem;
  font-size:.875rem;color:var(--green);
  display:flex;align-items:center;gap:.75rem;
  animation:slideIn .3s ease;box-shadow:0 8px 32px rgba(0,0,0,0.5);
  max-width:320px;
}
.toast.novo{border-color:rgba(245,166,35,0.5);color:var(--amber);animation:slideIn .3s ease,ring .3s ease .3s}
@keyframes slideIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
.toast-icon{font-size:1.2rem}

/* BARRA DE PROGRESSO */
.progress-bar{height:3px;background:var(--col)}
.progress-fill{height:100%;transition:width .5s ease;border-radius:0 2px 2px 0}

/* SCROLL */
.col-body::-webkit-scrollbar{width:4px}
.col-body::-webkit-scrollbar-track{background:transparent}
.col-body::-webkit-scrollbar-thumb{background:var(--col);border-radius:2px}

/* EMPTY STATE */
.empty-col{
  flex:1;display:flex;align-items:center;justify-content:center;
  flex-direction:column;gap:.75rem;color:var(--text-s);padding:2rem;text-align:center;
}
.empty-col .icon{font-size:2rem;opacity:.4}
.empty-col p{font-size:.8rem}
</style>
</head>
<body>

<!-- TOP BAR -->
<div class="topbar">
  <div class="topbar-left">
    <div class="topbar-logo">COZINHA // BELLA CUCINA</div>
    <div class="topbar-status">
      <div class="status-dot"></div>
      Sistema Online
    </div>
  </div>
  <div class="topbar-clock" id="clock">00:00:00</div>
  <div class="topbar-right">
    <div class="topbar-stat">
      <div class="stat-num amber" id="contConfirmado">2</div>
      <div style="font-size:.65rem;color:var(--text-s)">Aguardando</div>
    </div>
    <div class="topbar-stat">
      <div class="stat-num amber" id="contProducao">1</div>
      <div style="font-size:.65rem;color:var(--text-s)">Em Produção</div>
    </div>
    <div class="topbar-stat">
      <div class="stat-num green" id="contEntregues">14</div>
      <div style="font-size:.65rem;color:var(--text-s)">Entregues hoje</div>
    </div>
    <div class="topbar-stat">
      <div class="stat-num" style="color:var(--text-m)">R$ 1.284,50</div>
      <div style="font-size:.65rem;color:var(--text-s)">Faturamento hoje</div>
    </div>
  </div>
</div>

<!-- KANBAN -->
<div class="kanban">

  <!-- COLUNA: CONFIRMADO (Aguardando Produção) -->
  <div class="col">
    <div class="col-header confirmado">
      <div class="col-title">
        <div class="col-dot" style="background:var(--blue)"></div>
        Aguardando Produção
      </div>
      <span class="col-count" style="color:var(--blue)" id="cnt-confirmado">2</span>
    </div>
    <div class="col-body" id="col-confirmado">

      <!-- PEDIDO 1 -->
      <div class="pedido-card urgente" id="pedido-247">
        <div class="progress-bar"><div class="progress-fill" style="width:80%;background:var(--red)"></div></div>
        <div class="pedido-header">
          <div>
            <div class="pedido-codigo">#AB3K9X2M</div>
            <div class="pedido-cliente">👤 Carlos Mendes · Entrega</div>
          </div>
          <div>
            <div class="pedido-timer timer-bad">⏱ 24 min</div>
          </div>
        </div>
        <div class="pedido-body">
          <div class="item-row">
            <div class="item-qty">2x</div>
            <div class="item-info">
              <div class="item-nome">Filé Mignon Grelhado</div>
              <div class="item-opts">Ponto: Ao ponto · Sem cebola</div>
            </div>
          </div>
          <div class="item-row">
            <div class="item-qty">1x</div>
            <div class="item-info">
              <div class="item-nome">Bruschetta Clássica</div>
              <div class="item-opts">—</div>
            </div>
          </div>
          <div class="item-row">
            <div class="item-qty">2x</div>
            <div class="item-info">
              <div class="item-nome">Suco de Laranja Natural</div>
              <div class="item-obs">⚠ Sem gelo por favor</div>
            </div>
          </div>
          <div class="pedido-entrega">🏠 Rua das Flores, 142 · Bairro Jardins</div>
          <div class="pedido-obs-geral">⚠️ Alergia a amendoim! Verificar ingredientes.</div>
        </div>
        <div class="pedido-footer">
          <button class="btn-avancar confirmar" onclick="avancarStatus('247', 'em_producao', this)">
            ▶ Iniciar Produção
          </button>
          <button class="btn-cancel" onclick="cancelarPedido('247')">✕</button>
        </div>
      </div>

      <!-- PEDIDO 2 -->
      <div class="pedido-card" id="pedido-251">
        <div class="progress-bar"><div class="progress-fill" style="width:20%;background:var(--green)"></div></div>
        <div class="pedido-header">
          <div>
            <div class="pedido-codigo">#XR72MK9P</div>
            <div class="pedido-cliente">👤 Ana Lima · Retirada no local</div>
          </div>
          <div>
            <div class="pedido-timer timer-ok">⏱ 5 min</div>
          </div>
        </div>
        <div class="pedido-body">
          <div class="item-row">
            <div class="item-qty">1x</div>
            <div class="item-info">
              <div class="item-nome">Tagliatelle Bolognese</div>
              <div class="item-opts">Parmesão extra</div>
            </div>
          </div>
          <div class="item-row">
            <div class="item-qty">1x</div>
            <div class="item-info">
              <div class="item-nome">Tiramisù Artesanal</div>
            </div>
          </div>
          <div class="pedido-entrega">🏪 Retirada no balcão</div>
        </div>
        <div class="pedido-footer">
          <button class="btn-avancar confirmar" onclick="avancarStatus('251', 'em_producao', this)">
            ▶ Iniciar Produção
          </button>
          <button class="btn-cancel" onclick="cancelarPedido('251')">✕</button>
        </div>
      </div>

    </div>
  </div>

  <!-- COLUNA: EM PRODUÇÃO -->
  <div class="col">
    <div class="col-header em_producao">
      <div class="col-title">
        <div class="col-dot" style="background:var(--amber)"></div>
        Em Produção
      </div>
      <span class="col-count" style="color:var(--amber)" id="cnt-producao">1</span>
    </div>
    <div class="col-body" id="col-producao">

      <div class="pedido-card" id="pedido-244">
        <div class="progress-bar"><div class="progress-fill" style="width:60%;background:var(--amber)"></div></div>
        <div class="pedido-header">
          <div>
            <div class="pedido-codigo">#PQ91LF4C</div>
            <div class="pedido-cliente">👤 Roberto Costa · Entrega</div>
          </div>
          <div>
            <div class="pedido-timer timer-warn">⏱ 15 min</div>
          </div>
        </div>
        <div class="pedido-body">
          <div class="item-row">
            <div class="item-qty">1x</div>
            <div class="item-info">
              <div class="item-nome">Risoto de Cogumelos</div>
              <div class="item-opts">Sem parmesão</div>
            </div>
          </div>
          <div class="item-row">
            <div class="item-qty">1x</div>
            <div class="item-info">
              <div class="item-nome">Filé ao Molho Madeira</div>
              <div class="item-opts">Bem passado · Batata frita</div>
            </div>
          </div>
          <div class="item-row">
            <div class="item-qty">1x</div>
            <div class="item-info">
              <div class="item-nome">Água com gás 500ml</div>
            </div>
          </div>
          <div class="pedido-entrega">🏠 Av. Paulista, 1200 · Bela Vista</div>
        </div>
        <div class="pedido-footer">
          <button class="btn-avancar produzir" onclick="avancarStatus('244', 'saindo_entrega', this)">
            🛵 Enviar para Entrega
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- COLUNA: SAINDO PARA ENTREGA -->
  <div class="col">
    <div class="col-header saindo">
      <div class="col-title">
        <div class="col-dot" style="background:var(--green)"></div>
        Saindo para Entrega
      </div>
      <span class="col-count" style="color:var(--green)" id="cnt-saindo">0</span>
    </div>
    <div class="col-body" id="col-saindo">
      <div class="empty-col">
        <div class="icon">🛵</div>
        <p>Nenhum pedido<br>saindo no momento</p>
      </div>
    </div>
  </div>

</div>

<script>
// ---- RELÓGIO ----
function atualizarRelogio() {
  const agora = new Date();
  document.getElementById('clock').textContent =
    agora.toLocaleTimeString('pt-BR', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(atualizarRelogio, 1000);
atualizarRelogio();

// ---- TIMERS ----
const timers = {
  '247': Date.now() - 24 * 60000,
  '251': Date.now() - 5 * 60000,
  '244': Date.now() - 15 * 60000,
};

function atualizarTimers() {
  Object.entries(timers).forEach(([id, start]) => {
    const mins = Math.floor((Date.now() - start) / 60000);
    const el = document.querySelector(`#pedido-${id} .pedido-timer`);
    if (!el) return;
    el.textContent = `⏱ ${mins} min`;
    el.className = 'pedido-timer ' + (mins >= 30 ? 'timer-bad' : mins >= 15 ? 'timer-warn' : 'timer-ok');

    // Progresso
    const pct = Math.min(100, (mins / 45) * 100);
    const bar = document.querySelector(`#pedido-${id} .progress-fill`);
    if (bar) {
      bar.style.width = pct + '%';
      bar.style.background = pct >= 80 ? 'var(--red)' : pct >= 50 ? 'var(--amber)' : 'var(--green)';
    }

    // Urgente
    const card = document.getElementById(`pedido-${id}`);
    if (card) card.classList.toggle('urgente', mins >= 25);
  });
}
setInterval(atualizarTimers, 30000);

// ---- AVANÇAR STATUS ----
function avancarStatus(id, novoStatus, btn) {
  btn.disabled = true;
  btn.textContent = '...';

  // Simulação — em produção: fetch PATCH /cozinha/pedidos/{id}/avancar
  setTimeout(() => {
    const card = document.getElementById(`pedido-${id}`);
    if (!card) return;

    const origemId = novoStatus === 'em_producao' ? 'col-confirmado' : 'col-producao';
    const destinoId = novoStatus === 'em_producao' ? 'col-producao' : 'col-saindo';

    const destino = document.getElementById(destinoId);
    // Remover empty state se existir
    const empty = destino.querySelector('.empty-col');
    if (empty) empty.remove();

    // Mover card
    destino.prepend(card);

    // Atualizar botão
    const footer = card.querySelector('.pedido-footer');
    if (novoStatus === 'saindo_entrega') {
      footer.innerHTML = `<button class="btn-avancar entregar" onclick="marcarEntregue('${id}', this)">✅ Confirmar Entrega</button>`;
      // Notificar cliente (via WebSocket em produção)
      showNotificacao('🛵 Pedido #' + id + ' saiu para entrega!', 'verde');
    } else {
      footer.innerHTML = `<button class="btn-avancar produzir" onclick="avancarStatus('${id}', 'saindo_entrega', this)">🛵 Enviar para Entrega</button>`;
      showNotificacao('🍳 Pedido #' + id + ' em produção!', 'amber');
    }

    atualizarContadores();
    tocarSom();
  }, 600);
}

function marcarEntregue(id, btn) {
  btn.disabled = true;
  setTimeout(() => {
    const card = document.getElementById(`pedido-${id}`);
    if (card) {
      card.style.opacity = '0';
      card.style.transform = 'scale(0.95)';
      card.style.transition = '.3s';
      setTimeout(() => card.remove(), 300);
    }
    showNotificacao('✅ Pedido #' + id + ' entregue! +1', 'verde');
    const el = document.getElementById('contEntregues');
    el.textContent = parseInt(el.textContent) + 1;
    atualizarContadores();
  }, 400);
}

function cancelarPedido(id) {
  if (!confirm('Cancelar este pedido?')) return;
  const card = document.getElementById(`pedido-${id}`);
  if (card) { card.style.opacity='0';card.style.transition='.2s';setTimeout(()=>card.remove(),200); }
  atualizarContadores();
}

function atualizarContadores() {
  ['confirmado','producao','saindo'].forEach(key => {
    const colId = key === 'confirmado' ? 'col-confirmado' : key === 'producao' ? 'col-producao' : 'col-saindo';
    const cnt = document.getElementById('col-' + key) || document.getElementById(colId);
    const qtd = cnt ? cnt.querySelectorAll('.pedido-card').length : 0;
    const el = document.getElementById('cnt-' + key);
    if (el) el.textContent = qtd;
  });
}

// ---- NOTIFICAÇÕES ----
function showNotificacao(msg, tipo) {
  const toast = document.createElement('div');
  toast.className = 'toast ' + (tipo === 'amber' ? 'novo' : '');
  toast.innerHTML = `<span class="toast-icon">${tipo === 'verde' ? '✅' : '🔔'}</span><span>${msg}</span>`;
  document.body.appendChild(toast);
  setTimeout(() => { toast.style.opacity='0';toast.style.transition='.2s';setTimeout(()=>toast.remove(),200); }, 3500);
}

// ---- SOM DE NOTIFICAÇÃO (AudioContext) ----
function tocarSom() {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.frequency.setValueAtTime(880, ctx.currentTime);
    osc.frequency.setValueAtTime(1108, ctx.currentTime + 0.1);
    gain.gain.setValueAtTime(0.3, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
    osc.start(ctx.currentTime);
    osc.stop(ctx.currentTime + 0.4);
  } catch(e) {}
}

// ---- WebSocket (Laravel Echo) — ativar em produção ----
/*
window.Echo.channel('cozinha')
  .listen('.novo.pedido', (data) => {
    showNotificacao(`🔔 Novo pedido #${data.codigo} de ${data.cliente}!`, 'amber');
    tocarSom();
    // Injetar card na coluna confirmado via AJAX
    fetch('/cozinha/pedidos').then(r => r.json()).then(renderPedidos);
  })
  .listen('.pedido.status', (data) => {
    // Atualizar card existente
  });
*/

// Simular novo pedido a cada 30s para demonstração
let demoCount = 0;
setTimeout(() => {
  showNotificacao('🔔 Novo pedido de Maria Silva chegou!', 'amber');
  tocarSom();
}, 5000);
</script>
</body>
</html>