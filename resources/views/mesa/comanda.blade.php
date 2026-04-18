<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda Mesa {{ $mesa->numero }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    @include('layouts.partials.restaurant-theme')

    <style>
        :root {
            --amber:   var(--color-secondary);
            --brand:   var(--color-secondary); 
            --dark:    #FFFFFF;
            --dark2:   #F8FAFC;
            --surface: #FFFFFF;
            --text:    var(--text-main);
            --text-m:  var(--text-muted);
            --text-s:  #94A3B8;
            --border:  var(--color-secondary-border);
            --font-h:  'Playfair Display', serif;
            --font-b:  'DM Sans', sans-serif;
        }


        *{box-sizing:border-box;margin:0;padding:0}
        body{background:var(--dark2);color:var(--text);font-family:var(--font-b);min-height:100vh;}

        header{
            position:sticky;top:0;z-index:100;
            background:rgba(255,255,255,0.9);
            backdrop-filter:blur(12px);
            border-bottom:1px solid var(--border);
            padding:0 2rem;
            display:flex;align-items:center;justify-content:space-between;
            height:72px;
        }
        @media(max-width: 640px) {
            header { padding: 0 1rem; height: 64px; }
            .mesa-badge { padding: 6px 12px; }
            .mesa-label { display: none; }
        }

        .logo{ text-decoration:none; display:flex; align-items:center; }
        .logo img{ max-height:40px; }

        .mesa-badge {
            display: flex; align-items: center; gap: 10px;
            background: #F1F5F9; border: 1px solid #E2E8F0;
            padding: 8px 16px; border-radius: 50px;
        }
        .mesa-label { font-size: 10px; text-transform: uppercase; font-weight: 800; color: var(--text-s); }
        .mesa-number { font-size: 14px; font-weight: 800; color: var(--brand); font-family: var(--font-h); }

        .btn-comanda {
            display: flex; align-items: center; gap: 8px;
            background: var(--surface-soft); color: var(--brand); border: 1px solid var(--border);
            padding: 10px 18px; border-radius: 50px; font-weight: 700; font-size: 13px;
            text-decoration: none; transition: 0.3s;
        }
        .btn-comanda:hover { background: var(--surface-accent); transform: translateY(-1px); }

        .container { max-width: 600px; margin: 2rem auto; padding: 0 1.5rem; }

        .comanda-card { background: #fff; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid var(--border); overflow: hidden; }
        .comanda-header { background: var(--brand); padding: 2.5rem 2rem; text-align: center; color: #fff; }
        .comanda-body { padding: 2rem; }

        .pedido-block { background: #f8fafc; border-radius: 18px; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0; }
        .pedido-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px dashed #cbd5e1; padding-bottom: 0.75rem; }
        .pedido-id { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; }
        
        .item-row { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; font-size: 0.95rem; }
        .item-qty { font-weight: 800; color: var(--brand); background: #e0e7ff; padding: 2px 8px; border-radius: 6px; font-size: 0.8rem; margin-right: 8px; }
        .item-name { flex: 1; color: #1e293b; font-weight: 500; }
        .item-price { font-weight: 600; color: #475569; }

        .total-mesa-box { margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--brand); }
        .total-row { display: flex; justify-content: space-between; align-items: center; }
        .total-label { font-size: 1.1rem; font-weight: 600; color: var(--text-muted); }
        .total-value { font-size: 2rem; font-weight: 900; color: #16a34a; }

        .btn-fechar {
            width: 100%; background: var(--brand); color: #fff; border: none; padding: 1.25rem;
            border-radius: 18px; font-size: 1.1rem; font-weight: 800; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 12px;
            margin-top: 2rem; transition: 0.3s;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .btn-fechar:hover { opacity: 0.9; transform: translateY(-2px); }


        .status-badge { padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .status-pendente { background: #fee2e2; color: #ef4444; }
        .status-concluido { background: #dcfce7; color: #16a34a; }

        /* CONFIRMATION MODAL */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px); z-index: 1000;
            display: none; align-items: center; justify-content: center; padding: 20px;
        }
        .modal-overlay.open { display: flex; animation: fadeIn 0.3s ease; }
        
        .c-modal {
            background: #fff; width: 100%; max-width: 420px;
            border-radius: 28px; overflow: hidden; border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .c-modal-header { padding: 2rem 2rem 1.5rem; text-align: center; }
        .c-modal-icon { 
            width: 64px; height: 64px; background: #FFF7ED; color: #F97316;
            margin: 0 auto 1.5rem; border-radius: 20px;
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }

        .c-modal-body { padding: 0 2rem 2rem; text-align: center; }
        .c-modal-title { font-family: var(--font-h); font-size: 1.5rem; color: #1e293b; margin-bottom: 0.75rem; }
        .c-modal-text { color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem; }

        .c-total-box {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 18px;
            padding: 1.25rem; margin-bottom: 2rem;
        }
        .c-total-label { font-size: 0.8rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; display: block; }
        .c-total-price { font-size: 2rem; font-weight: 900; color: var(--brand); }

        .c-modal-actions { display: grid; grid-template-columns: 1fr; gap: 12px; padding: 0 2rem 2rem; }
        .btn-confirm {
            background: var(--brand); color: #fff; border: none; padding: 1rem;
            border-radius: 14px; font-weight: 800; font-size: 1rem; cursor: pointer;
            transition: 0.3s;
        }
        .btn-confirm:hover { filter: brightness(1.1); transform: translateY(-2px); }
        .btn-cancel {
            background: transparent; color: #94a3b8; border: none; padding: 0.75rem;
            font-weight: 700; font-size: 0.9rem; cursor: pointer;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes zoomIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        /* RESET/DENIED MODAL SPECIFIC */
        #orderDeniedModal .p-modal-icon { background: #fee2e2; color: #dc2626; }
        .denied-item {
            background: #fef2f2; border: 1px solid #fee2e2; padding: 0.75rem 1rem;
            border-radius: 12px; margin-bottom: 0.5rem; color: #991b1b; font-weight: 600; font-size: 0.9rem;
            text-align: left; display: flex; align-items: center; gap: 3px;
        }

        /* FINALIZED MODAL SPECIFIC */
        .modal-success-icon {
            width: 80px; height: 80px; background: #dcfce7; color: #16a34a;
            margin: 0 auto 1.5rem; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 2.5rem;
            animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes scaleIn { from { transform: scale(0); } to { transform: scale(1); } }

    </style>

</head>
<body>

<header>
    <div style="display:flex; align-items:center; gap:20px;">
        <a href="{{ route('mesa.cardapio', ['hash' => $mesa->token_hash]) }}">
            <div class="logo">
                <div class="bg-slate-100 p-1 rounded-xl mr-4">
                    @if($restaurantConfig->logo_url)
                        <img src="{{ $restaurantConfig->logo_url }}" alt="Logo" style="max-height: 48px; width: auto;">
                    @else
                        <span style="font-family:var(--font-h); font-weight:900; color:var(--brand); font-style: italic;">{{ config('app.name') }}</span>
                    @endif
                </div>
            </div>
        </a>
        <div class="mesa-badge">
            <span class="mesa-label">Você está na</span>
            <span class="mesa-number">Mesa {{ $mesa->numero }}</span>
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('mesa.cardapio', ['hash' => $mesa->token_hash ?? session('mesa_token')]) }}" class="btn-comanda" style="background: var(--brand); color: #fff; border:none;">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar ao Menu</span>
        </a>
    </div>


</header>

<div class="container">
    <div class="comanda-card">
        <div class="comanda-header">
            <h1 style="font-family: var(--font-h); font-size: 2rem; margin-bottom: 0.5rem;">Seu Pedido</h1>
            <p style="font-size: 0.9rem; opacity: 0.8;">Mesa {{ $mesa->numero }} • Resumo do pedido atual</p>
        </div>

        <div class="comanda-body">
            @if($mesa->pedidos->isEmpty())
                @php
                    $meusPedidosIds = session('meus_pedidos_ids', []);
                    $foiResetado = !empty($meusPedidosIds) && \App\Models\Pedido::whereIn('id', $meusPedidosIds)->where('status', 'cancelado')->exists();
                @endphp

                @if($foiResetado)

                    <!-- ESTADO: PEDIDO NEGADO/RESETADO -->
                    <div style="text-align: center; padding: 3rem 0; color: var(--text-m);">
                        <div style="width: 80px; height: 80px; background: #fee2e2; color: #ef4444; border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; rotate: -10deg;">
                            <i class="fas fa-hand-paper"></i>
                        </div>
                        <h2 style="color: var(--text-main); font-weight: 800; margin-bottom: 0.5rem; font-size: 1.5rem;">Atendimento Encerrado</h2>
                        <p style="font-weight: 500; font-size: 0.95rem; line-height: 1.6; padding: 0 1rem;">O garçom cancelou seus pedidos ou reiniciou a mesa manualmente.</p>
                        <a href="{{ route('mesa.abrir', ['hash' => $mesa->token_hash]) }}" style="display: inline-block; margin-top: 2rem; background: var(--brand); color: #fff; padding: 16px 32px; border-radius: 16px; font-weight: 800; text-decoration: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">Abrir Novo Atendimento</a>
                    </div>
                @elseif(empty($meusPedidosIds))
                    <!-- ESTADO: BEM-VINDO (PRIMEIRO ACESSO) -->
                    <div style="text-align: center; padding: 3rem 0; color: var(--text-m);">
                        <div style="width: 80px; height: 80px; background: var(--surface-accent); color: var(--brand); border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; animation: bounce 2s infinite;">
                            <i class="fas fa-smile-beam"></i>
                        </div>
                        <h2 style="color: var(--text-main); font-weight: 800; margin-bottom: 0.5rem; font-size: 1.5rem;">Bem-vindo!</h2>
                        <p style="font-weight: 500; font-size: 0.95rem; line-height: 1.6;">Você ainda não fez nenhum pedido nesta mesa.</p>
                        <div style="margin-top: 1.5rem; padding: 1rem; background: var(--surface-soft); border-radius: 16px; display: inline-block;">
                            <p style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Dica Profissional</p>
                            <p style="font-size: 0.9rem; color: var(--text-main); margin-top: 4px;">Escolha seus pratos no menu e acompanhe<br>o status da cozinha por aqui!</p>
                        </div>
                        <br>
                        <a href="{{ route('mesa.cardapio', ['hash' => $mesa->token_hash]) }}" style="display: inline-block; margin-top: 2rem; color: var(--brand); font-weight: 800; text-decoration: none; font-size: 1.1rem;">Ver Menu →</a>
                    </div>
                @else
                    <!-- ESTADO: AGUARDANDO COZINHA (OU OUTRO) -->
                    <div style="text-align: center; padding: 3rem 0; color: var(--text-m);">
                        <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--brand); opacity: 0.2; margin-bottom: 1rem;"></i>
                        <h2 style="color: var(--text-main); font-weight: 800; margin-bottom: 0.5rem;">Pedido Enviado</h2>
                        <p style="font-weight: 600;">Obrigado! Seu pedido foi enviado para a cozinha.</p>
                        <a href="{{ route('mesa.cardapio', ['hash' => $mesa->token_hash]) }}" style="display: inline-block; margin-top: 1rem; color: var(--brand); font-weight: 700; text-decoration: none;">Fazer outro pedido →</a>
                    </div>
                @endif
            @else



                @php $totalGeral = 0; @endphp
                @foreach($mesa->pedidos as $pedido)
                    @php $totalGeral += $pedido->total; @endphp
                    <div class="pedido-block">
                        <div class="pedido-meta">
                            <span class="pedido-id">Pedido #{{ $pedido->codigo }}</span>
                            <span class="status-badge" style="background: {{ $pedido->statusCor == 'success' ? '#dcfce7' : '#fef9c3' }}; color: {{ $pedido->statusCor == 'success' ? '#16a34a' : '#854d0e' }}">
                                {{ $pedido->statusLabel }}
                            </span>
                        </div>
                        @foreach($pedido->itens as $item)
                        <div class="item-row">
                            <span class="item-qty">{{ $item->quantidade }}x</span>
                            <span class="item-name">{{ $item->nome_prato }}</span>
                            <span class="item-price">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                @endforeach

                <div class="total-mesa-box">
                    <div class="total-row">
                        <span class="total-label">Total do Pedido</span>
                        <span class="total-value">R$ {{ number_format($totalGeral, 2, ',', '.') }}</span>
                    </div>
                </div>


                    @if($mesa->status === 'aguardando_pagamento')
                        <div style="margin-top: 2rem; background: #eff6ff; border: 1px solid #bfdbfe; padding: 1.5rem; border-radius: 18px; text-align: center;">
                            <i class="fas fa-concierge-bell" style="font-size: 2rem; color: var(--brand); margin-bottom: 0.5rem;"></i>
                            <p style="font-weight: 800; color: var(--brand);">Garçom Notificado!</p>
                            <p style="font-size: 0.85rem; color: #475569;">Estamos vindo até a sua mesa para processar o pagamento.</p>
                        </div>
                    @else
                        <form id="formPedirConta" method="POST" action="{{ route('mesa.fechar') }}">
                            @csrf
                            <button type="button" class="btn-fechar" onclick="openConfirmModal()">
                                <i class="fas fa-receipt"></i>
                                Pedir a Conta
                            </button>
                            <p style="text-align: center; font-size: 0.75rem; color: #94a3b8; margin-top: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                O pagamento é feito diretamente ao garçom
                            </p>
                        </form>
                    @endif

                </div>
            @endif
        </div>
    </div>

    @if($mesa->status !== 'aguardando_pagamento')
    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('mesa.cardapio', ['hash' => $mesa->token_hash]) }}" style="color: #64748b; font-size: 0.9rem; font-weight: 600; text-decoration: none;">
            <i class="fas fa-plus-circle"></i> Adicionar mais itens ao pedido
        </a>
    </div>
    @endif


</div>

<!-- MODAL DE CONFIRMAÇÃO -->
<div class="modal-overlay" id="confirmModal">
    <div class="c-modal">
        <div class="c-modal-header">
            <div class="c-modal-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <h2 class="c-modal-title">Fechar Conta?</h2>
        </div>
        <div class="c-modal-body">
            <p class="c-modal-text">Ao solicitar a conta, um garçom virá até sua mesa para processar o pagamento dos seus itens consumidos.</p>
            
            <div class="c-total-box">
                <span class="c-total-label">Total a Pagar</span>
                <span class="c-total-price">R$ {{ number_format($totalGeral ?? 0, 2, ',', '.') }}</span>
            </div>
        </div>
        <div class="c-modal-actions">
            <button class="btn-confirm" onclick="confirmarFechamento()">
                <i class="fas fa-check mr-2"></i> Confirmar e Pedir Conta
            </button>
            <button class="btn-cancel" onclick="closeConfirmModal()">Ainda não, quero continuar</button>
        </div>
    </div>
</div>

<!-- MODAL DE FINALIZAÇÃO (CONTA PAGA) -->
<div class="modal-overlay" id="finalizedModal">
    <div class="c-modal">
        <div class="c-modal-header">
            <div class="modal-success-icon">
                <i class="fas fa-check-double"></i>
            </div>
            <h2 class="c-modal-title">Pagamento Confirmado!</h2>
        </div>
        <div class="c-modal-body">
            <p class="c-modal-text" style="font-size: 1.1rem; color: #1e293b; font-weight: 500;">Sua conta foi finalizada com sucesso pelo garçom.</p>
            <p class="c-modal-text">Obrigado pela preferência! Caso queira fazer um novo pedido futuramente, basta escanear o QR Code da mesa novamente.</p>
            
            <div id="countdown-box" style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1.2rem; border-radius: 14px; color: #166534; font-size: 0.9rem; font-weight: 700;">
                <i class="fas fa-sync-alt fa-spin mr-2"></i> 
                Sessão reiniciando em <span id="timer-num" style="font-size: 1.2rem;">10</span>s...
            </div>
            <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 1rem;">O navegador será fechado por motivos de segurança.</p>
        </div>
        <div class="c-modal-actions">
            <button class="btn-confirm" onclick="location.reload()" style="background: #16a34a;">
                Reiniciar Agora
            </button>
        </div>
    </div>
</div>

<!-- Modal: PEDIDO NEGADO / MESA RESETADA -->
<div id="orderDeniedModal" class="modal-overlay">
    <div class="c-modal">
        <div class="c-modal-header !pt-8">
            <div class="c-modal-icon p-modal-icon mx-auto">
                <i class="fas fa-hand-paper"></i>
            </div>
            <h2 class="c-modal-title">Atendimento Encerrado</h2>
            <p class="text-slate-500 font-medium mt-2 px-6">O garçom cancelou os pedidos desta mesa ou encerrou a sessão manualmente.</p>
        </div>
        
        <div class="px-8 py-4">
            <p class="text-[11px] uppercase font-black text-slate-400 mb-3 tracking-widest">Itens Cancelados:</p>
            <div id="deniedItemsList" class="max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                <!-- JS Injects items here -->
            </div>
        </div>

        <div class="p-8 pt-4">
            <a href="{{ route('mesa.abrir', ['hash' => $mesa->token_hash]) }}" class="w-full bg-slate-800 text-white font-black py-4 rounded-2xl shadow-xl hover:bg-slate-900 transition-all flex items-center justify-center gap-2 text-center no-underline">
                <i class="fas fa-sync-alt"></i> REINICIAR ATENDIMENTO
            </a>
        </div>

    </div>
</div>

<script>
    let isFinalized = false;

    function openConfirmModal() {
        document.getElementById('confirmModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    function confirmarFechamento() {
        const btn = document.querySelector('.btn-confirm');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processando...';
            btn.disabled = true;
        }
        document.getElementById('formPedirConta').submit();
    }

    function startCountdown() {
        let timeLeft = 10;
        const timerEl = document.getElementById('timer-num');
        const countdown = setInterval(() => {
            timeLeft--;
            if (timerEl) timerEl.innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.close();
                setTimeout(() => {
                    window.location.href = "{{ route('mesa.cardapio', ['hash' => $mesa->token_hash]) }}";
                }, 500);
            }
        }, 1000);
    }

    async function checkStatus() {
        if (isFinalized) return;
        try {
            const res = await fetch('{{ route("mesa.status-check") }}');
            if (!res.ok) return;
            const d = await res.json();
            
            console.log("Status Check Response:", d);

            if (d.status === 'livre') {
                isFinalized = true;
                
                if (d.reason === 'reseted') {
                    // Caso o garçom tenha cancelado o pedido (Mesa Resetada)
                    const list = document.getElementById('deniedItemsList');
                    if (list && d.items) {
                        list.innerHTML = d.items.map(it => `
                            <div class="denied-item">
                                <i class="fas fa-times-circle opacity-50"></i> ${it}
                            </div>
                        `).join('');
                    }
                    document.getElementById('orderDeniedModal').classList.add('open');
                } else {
                    // Caso de pagamento normal confirmado
                    document.getElementById('finalizedModal').classList.add('open');
                    startCountdown();
                }
                
                // Limpa cache local do carrinho
                localStorage.removeItem('mesa_cart_{{ $mesa->id }}');
            }
        } catch (e) {
            console.error("Erro no polling:", e);
        }
    }

    // Inicia o polling a cada 4 segundos para resposta rápida
    setInterval(checkStatus, 4000);

    // Fecha o modal ao clicar fora dele
    window.onclick = function(event) {
        const modal = document.getElementById('confirmModal');
        if (event.target == modal) {
            closeConfirmModal();
        }
    }
</script>



</body>
</html>

