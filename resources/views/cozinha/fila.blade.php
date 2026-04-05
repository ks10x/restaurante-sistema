@include('cozinha.partials.layout-start', ['title' => 'Fila da Cozinha - Bella Cucina'])

@php
$serializePedido = function ($pedido) {
    return [
        'id' => $pedido->id,
        'codigo' => $pedido->codigo,
        'status' => $pedido->status,
        'cliente' => $pedido->usuario->name ?? null,
        'tipo_entrega' => $pedido->tipo_entrega,
        'minutos_decorridos' => $pedido->minutos_decorridos,
        'faixa_tempo' => $pedido->faixa_tempo,
        'progresso' => $pedido->progresso,
        'total' => (float) $pedido->total,
        'total_itens' => $pedido->total_itens,
        'observacoes' => $pedido->observacoes_limpa,
        'endereco' => $pedido->endereco ? [
            'logradouro' => $pedido->endereco->logradouro,
            'numero' => $pedido->endereco->numero,
            'bairro' => $pedido->endereco->bairro,
            'cidade' => $pedido->endereco->cidade,
            'estado' => $pedido->endereco->estado ?? $pedido->endereco->uf,
            'texto' => $pedido->endereco_formatado,
        ] : null,
        'avancar_url' => route('cozinha.pedido.avancar', $pedido),
        'cancelar_url' => route('cozinha.pedido.cancelar', $pedido),
        'itens' => $pedido->itens->map(function ($item) {
            return [
                'nome' => $item->nome_exibicao,
                'quantidade' => $item->quantidade,
                'descricao' => $item->descricao_curta,
                'imagem' => $item->foto_url,
                'observacao' => $item->observacao_limpa,
                'ingredientes' => $item->ingredientes_lista,
                'opcoes' => is_array($item->opcoes ?? null) ? $item->opcoes : [],
            ];
        })->values()->all(),
    ];
};
$initialPedidos = [
    'confirmado' => $pedidosPorStatus['confirmado']->map($serializePedido)->values()->all(),
    'em_producao' => $pedidosPorStatus['em_producao']->map($serializePedido)->values()->all(),
    'saindo_entrega' => $pedidosPorStatus['saindo_entrega']->map($serializePedido)->values()->all(),
];
@endphp

<style>
.page-wrap{max-width:1600px;margin:0 auto}
.topbar{display:flex;justify-content:space-between;align-items:center;gap:16px;background:rgba(255,255,255,.88);backdrop-filter:blur(14px);border:1px solid var(--color-secondary-border);border-radius:24px;padding:18px 20px;box-shadow:0 20px 40px rgba(15,23,42,.08);margin-bottom:18px}
.brand-inline{display:flex;align-items:center;gap:14px}.brand-mark-inline{width:52px;height:52px;border-radius:16px;background:#fff;display:flex;align-items:center;justify-content:center;padding:8px;border:1px solid var(--color-secondary-border);box-shadow:0 10px 24px rgba(15,23,42,.08);overflow:hidden;flex-shrink:0}.brand-mark-inline img{width:100%;height:100%;object-fit:contain;display:block}.brand-mark-inline span{color:var(--color-secondary);font-size:.72rem;font-weight:700;line-height:1.1;text-align:center}.brand-title-inline{font-size:1.2rem;font-weight:700;color:var(--text-main)}.brand-sub-inline{font-size:.86rem;color:#64748b}.clock{font-family:'IBM Plex Mono',monospace;font-size:1.1rem;font-weight:600;color:var(--color-secondary-dark)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}.stat{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:18px;padding:16px;box-shadow:0 20px 40px rgba(15,23,42,.08)}.stat small{display:block;color:#64748b;text-transform:uppercase;letter-spacing:.08em;font-size:.73rem;margin-bottom:8px}.stat strong{font-family:'IBM Plex Mono',monospace;font-size:1.45rem}
.board{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.col{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:24px;overflow:hidden;box-shadow:0 20px 40px rgba(15,23,42,.08)}.col-head{padding:16px 18px;border-bottom:1px solid rgba(15,23,42,.08);display:flex;align-items:center;justify-content:space-between}.col-title{display:flex;gap:10px;align-items:center;font-weight:700}.dot{width:10px;height:10px;border-radius:50%}.count{font-family:'IBM Plex Mono',monospace;background:#eff6ff;padding:6px 10px;border-radius:999px}.col-body{padding:14px;display:flex;flex-direction:column;gap:14px;min-height:72vh}
.card{background:#fff;border:2px solid rgba(15,23,42,.08);border-radius:20px;overflow:hidden}.card.status-confirmado{border-color:rgba(29,78,216,.34);box-shadow:0 0 0 3px rgba(29,78,216,.08)}.card.status-em_producao{border-color:rgba(217,119,6,.34);box-shadow:0 0 0 3px rgba(217,119,6,.08)}.card.status-saindo_entrega{border-color:rgba(5,150,105,.34);box-shadow:0 0 0 3px rgba(5,150,105,.08)}.card.critico{box-shadow:0 0 0 4px rgba(220,38,38,.12)}.progress{height:5px;background:#e2e8f0}.progress span{display:block;height:100%}.card-main{padding:16px}.card-top{display:flex;justify-content:space-between;gap:10px;margin-bottom:12px}.code{font-family:'IBM Plex Mono',monospace;font-size:1rem;font-weight:600}.client{font-size:.86rem;color:#64748b;margin-top:4px}.timer{padding:7px 10px;border-radius:999px;font-size:.76rem;font-weight:700}.timer.ok{background:rgba(5,150,105,.1);color:#059669}.timer.atencao{background:rgba(217,119,6,.1);color:#d97706}.timer.critico{background:rgba(220,38,38,.1);color:#dc2626}
.items{display:flex;flex-direction:column;gap:10px}.item{display:grid;grid-template-columns:58px 1fr;gap:10px;background:#f8fafc;border-radius:16px;padding:10px}.item img{width:58px;height:58px;border-radius:12px;object-fit:cover;background:#dbeafe}.item-name{font-weight:700;font-size:.92rem}.item-desc,.item-meta{font-size:.8rem;color:#64748b;margin-top:4px;line-height:1.4}.tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:7px}.tag{font-size:.72rem;border:1px solid rgba(15,23,42,.08);background:#fff;padding:4px 8px;border-radius:999px;color:#64748b}
.note{margin-top:10px;padding:10px 12px;border-radius:14px;font-size:.82rem}.address{background:var(--surface-accent);color:var(--color-secondary-dark)}.obs{background:#fef2f2;color:#b91c1c}.actions{display:flex;gap:10px;margin-top:12px}.btn{border:none;border-radius:14px;padding:12px 14px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif}.btn-primary{flex:1;background:linear-gradient(135deg,var(--color-secondary-dark),var(--color-secondary));color:#fff}.btn-danger{background:#fff;color:#dc2626;border:1px solid rgba(220,38,38,.18)}.empty{min-height:180px;border:1px dashed rgba(148,163,184,.45);border-radius:18px;display:flex;align-items:center;justify-content:center;text-align:center;color:#64748b;padding:20px}
.modal{position:fixed;inset:0;background:rgba(15,23,42,.58);display:none;align-items:center;justify-content:center;padding:18px}.modal.open{display:flex}.modal-card{width:min(100%,440px);background:#fff;border-radius:24px;padding:22px;box-shadow:0 20px 40px rgba(15,23,42,.08)}.modal-card h3{margin-bottom:8px}.modal-card p{color:#64748b;line-height:1.5;margin-bottom:14px}.modal-card textarea{width:100%;min-height:110px;border:1px solid rgba(15,23,42,.08);border-radius:16px;padding:12px 14px;font-family:'DM Sans',sans-serif;resize:vertical}.modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:14px}.toast{position:fixed;right:20px;bottom:20px;background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:18px;padding:14px 16px;display:none;align-items:center;gap:10px;box-shadow:0 20px 40px rgba(15,23,42,.08)}.toast.show{display:flex}
@media(max-width:1200px){.stats{grid-template-columns:repeat(2,1fr)}.board{grid-template-columns:1fr}}@media(max-width:720px){.topbar,.actions{flex-direction:column;align-items:stretch}.stats{grid-template-columns:1fr}}
</style>

<div class="page-wrap">
  <div class="topbar">
    <div class="brand-inline">
      <div class="brand-mark-inline">
        @if(($restaurantConfig->logo_url ?? null))
          <img src="{{ $restaurantConfig->logo_url }}" alt="Logo do restaurante">
        @else
          <span>{{ config('app.name', 'Restaurante') }}</span>
        @endif
      </div>
      <div>
        <div class="brand-title-inline">Fila da Cozinha</div>
        <div class="brand-sub-inline">Ambiente operacional para marcar pedidos dinamicos em tempo real</div>
      </div>
    </div>
    <div class="clock" id="liveClock">00:00:00</div>
  </div>

  <div class="stats">
    <div class="stat"><small>Aguardando</small><strong id="stat-confirmado">{{ $pedidosPorStatus['confirmado']->count() }}</strong></div>
    <div class="stat"><small>Em Producao</small><strong id="stat-producao">{{ $pedidosPorStatus['em_producao']->count() }}</strong></div>
    <div class="stat"><small>Prontos / Saindo</small><strong id="stat-saindo">{{ $pedidosPorStatus['saindo_entrega']->count() }}</strong></div>
    <div class="stat"><small>Total Ativo</small><strong id="stat-total">{{ $pedidosPorStatus['confirmado']->count() + $pedidosPorStatus['em_producao']->count() + $pedidosPorStatus['saindo_entrega']->count() }}</strong></div>
  </div>

  <div class="board">
    <section class="col"><div class="col-head"><div class="col-title"><span class="dot" style="background:#1d4ed8"></span>Aguardando Producao</div><div class="count" id="count-confirmado">{{ $pedidosPorStatus['confirmado']->count() }}</div></div><div class="col-body" id="col-confirmado"></div></section>
    <section class="col"><div class="col-head"><div class="col-title"><span class="dot" style="background:#d97706"></span>Em Producao</div><div class="count" id="count-em_producao">{{ $pedidosPorStatus['em_producao']->count() }}</div></div><div class="col-body" id="col-em_producao"></div></section>
    <section class="col"><div class="col-head"><div class="col-title"><span class="dot" style="background:#059669"></span>Pronto / Saindo</div><div class="count" id="count-saindo_entrega">{{ $pedidosPorStatus['saindo_entrega']->count() }}</div></div><div class="col-body" id="col-saindo_entrega"></div></section>
  </div>
</div>

<div class="modal" id="cancelModal"><div class="modal-card"><h3>Cancelar pedido</h3><p>Informe o motivo do cancelamento para registrar no historico da operacao.</p><textarea id="cancelReason" placeholder="Ex: item indisponivel, erro no preparo, atraso operacional..."></textarea><div class="modal-actions"><button class="btn" id="dismissCancel">Voltar</button><button class="btn btn-danger" id="confirmCancel">Confirmar cancelamento</button></div></div></div>
<div class="toast" id="appToast"></div>

<script>
const initialPedidos = @json($initialPedidos);
const apiUrl = '{{ route('cozinha.api.pedidos') }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const toast = document.getElementById('appToast');
const cancelModal = document.getElementById('cancelModal');
const cancelReason = document.getElementById('cancelReason');
let pedidosState = [...initialPedidos.confirmado, ...initialPedidos.em_producao, ...initialPedidos.saindo_entrega];
let pendingCancelUrl = null;
function updateClock(){document.getElementById('liveClock').textContent=new Date().toLocaleTimeString('pt-BR')}
setInterval(updateClock,1000);updateClock();
function showToast(message,color='var(--green)'){toast.innerHTML=`<i class="fas fa-circle-check" style="color:${color}"></i><span>${message}</span>`;toast.classList.add('show');setTimeout(()=>toast.classList.remove('show'),2500)}
function escapeHtml(value){return String(value||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}
function timerClass(level){return level==='critico'?'critico':level==='atencao'?'atencao':'ok'}
function statusProgressColor(status){return status==='confirmado'?'#1d4ed8':status==='em_producao'?'#d97706':'#059669'}
function statusCardClass(status){return status==='confirmado'?'status-confirmado':status==='em_producao'?'status-em_producao':'status-saindo_entrega'}
function renderPedido(p){return `<article class="card ${statusCardClass(p.status)} ${p.faixa_tempo==='critico'?'critico':''}" data-id="${p.id}"><div class="progress"><span style="width:${p.progresso}%;background:${statusProgressColor(p.status)}"></span></div><div class="card-main"><div class="card-top"><div><div class="code">#${escapeHtml(p.codigo)}</div><div class="client">${escapeHtml(p.cliente||'Cliente')} · ${p.tipo_entrega==='entrega'?'Entrega':'Retirada'}</div></div><div class="timer ${timerClass(p.faixa_tempo)}">${Math.round(Number(p.minutos_decorridos||0))} min</div></div><div class="items">${p.itens.map(item=>`<div class="item"><img src="${escapeHtml(item.imagem||'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop')}" alt="${escapeHtml(item.nome||'Prato')}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';"><div><div class="item-name">${item.quantidade}x ${escapeHtml(item.nome)}</div>${item.descricao?`<div class="item-desc">${escapeHtml(item.descricao)}</div>`:''}<div class="tags">${(item.ingredientes||[]).slice(0,4).map(i=>`<span class="tag">${escapeHtml(i)}</span>`).join('')}${(item.opcoes||[]).slice(0,2).map(op=>`<span class="tag">${escapeHtml(op.nome||'Opcao')}: ${escapeHtml(op.valor||'')}</span>`).join('')}</div>${item.observacao?`<div class="item-meta">Observacao: ${escapeHtml(item.observacao)}</div>`:''}</div></div>`).join('')}</div>${p.endereco?.texto?`<div class="note address">${escapeHtml(p.endereco.texto)}</div>`:`<div class="note address">Retirada no balcao</div>`}${p.observacoes?`<div class="note obs">${escapeHtml(p.observacoes)}</div>`:''}<div class="actions">${p.status!=='saindo_entrega'?`<button class="btn btn-primary" data-action="advance" data-url="${escapeHtml(p.avancar_url)}">${p.status==='confirmado'?'Iniciar producao':'Enviar para entrega'}</button><button class="btn btn-danger" data-action="cancel" data-url="${escapeHtml(p.cancelar_url)}">Cancelar</button>`:`<button class="btn btn-primary" data-action="advance" data-url="${escapeHtml(p.avancar_url)}">Marcar entregue</button>`}</div></div></article>`;}
function renderBoard(){['confirmado','em_producao','saindo_entrega'].forEach(status=>{const col=document.getElementById(`col-${status}`);const items=pedidosState.filter(p=>p.status===status);document.getElementById(`count-${status}`).textContent=items.length;col.innerHTML=items.length?items.map(renderPedido).join(''):'<div class="empty">Nenhum pedido nesta etapa agora.</div>';});document.getElementById('stat-confirmado').textContent=pedidosState.filter(p=>p.status==='confirmado').length;document.getElementById('stat-producao').textContent=pedidosState.filter(p=>p.status==='em_producao').length;document.getElementById('stat-saindo').textContent=pedidosState.filter(p=>p.status==='saindo_entrega').length;document.getElementById('stat-total').textContent=pedidosState.length;}
async function fetchPedidos(){try{const response=await fetch(apiUrl,{headers:{'Accept':'application/json'}});const data=await response.json();pedidosState=data;renderBoard();}catch(e){}}
async function sendPatch(url,body={}){const response=await fetch(url,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(body)});const data=await response.json();if(!response.ok) throw new Error(data.error||data.message||'Nao foi possivel concluir a acao.');return data;}
document.addEventListener('click',async event=>{const advanceButton=event.target.closest('[data-action="advance"]');const cancelButton=event.target.closest('[data-action="cancel"]');if(advanceButton){const original=advanceButton.textContent;advanceButton.disabled=true;advanceButton.textContent='Atualizando...';try{const data=await sendPatch(advanceButton.dataset.url);showToast(`Pedido ${data.pedido.codigo} atualizado para ${data.pedido.status_label}.`,'var(--navy)');await fetchPedidos();}catch(error){showToast(error.message,'var(--red)');advanceButton.disabled=false;advanceButton.textContent=original;}}if(cancelButton){pendingCancelUrl=cancelButton.dataset.url;cancelReason.value='';cancelModal.classList.add('open');cancelReason.focus();}});
document.getElementById('dismissCancel').addEventListener('click',()=>{cancelModal.classList.remove('open');pendingCancelUrl=null;});
document.getElementById('confirmCancel').addEventListener('click',async()=>{if(!pendingCancelUrl) return;try{await sendPatch(pendingCancelUrl,{motivo:cancelReason.value});cancelModal.classList.remove('open');showToast('Pedido cancelado com sucesso.','var(--red)');await fetchPedidos();}catch(error){showToast(error.message,'var(--red)')}});
cancelModal.addEventListener('click',event=>{if(event.target===cancelModal) cancelModal.classList.remove('open');});
renderBoard();
setInterval(fetchPedidos,10000);
</script>

@include('cozinha.partials.layout-end')
