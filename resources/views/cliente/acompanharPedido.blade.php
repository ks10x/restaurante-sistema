<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhar Pedido — Bella Cucina</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>

    <style>
        :root {
            --navy: #1E3A8A; --navy-d: #1E40AF; --navy-l: #3B82F6;
            --bg: #F8FAFC; --surface: #FFFFFF; --surface2: #F1F5F9;
            --text: #0F172A; --text-m: #64748B; --text-s: #94A3B8;
            --border: rgba(30,58,138,0.12); --green: #10B981; --green-bg: #ECFDF5;
            --amber: #F59E0B; --amber-bg: #FFFBEB; --red: #EF4444;
            --radius: 16px; --font-h: 'Playfair Display', serif; --font-b: 'DM Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: var(--font-b); min-height: 100vh; }

        .track-header {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border); padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between; height: 72px;
            position: sticky; top: 0; z-index: 50;
        }
        .track-header a { text-decoration: none; color: var(--text-m); display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .track-header a:hover { color: var(--navy); }
        .logo { font-family: var(--font-h); font-size: 1.5rem; color: var(--navy); }
        .logo span { color: var(--text-m); font-weight: 400; font-size: 1.1rem; }

        .track-wrap { max-width: 600px; margin: 0 auto; padding: 2rem; }

        /* STATUS HERO */
        .status-hero {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-d) 100%);
            border-radius: 20px; padding: 2rem; color: #fff; text-align: center;
            margin-bottom: 1.5rem; position: relative; overflow: hidden;
        }
        .status-hero::before {
            content: ''; position: absolute; top: -50%; right: -30%; width: 200px; height: 200px;
            background: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .status-hero .code { font-size: 0.85rem; opacity: 0.7; letter-spacing: 1px; margin-bottom: 0.5rem; }
        .status-hero .current-status { font-family: var(--font-h); font-size: 1.8rem; margin-bottom: 0.3rem; }
        .status-hero .eta { font-size: 0.95rem; opacity: 0.8; }
        .status-hero .eta strong { color: #fff; font-size: 1.1rem; }

        /* TIMELINE */
        .section { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; margin-bottom: 1.5rem; }
        .section-title { font-family: var(--font-h); font-size: 1.2rem; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: var(--navy); }

        .timeline { position: relative; padding-left: 40px; }
        .timeline::before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background: var(--border); }

        .timeline-step { position: relative; padding-bottom: 1.5rem; }
        .timeline-step:last-child { padding-bottom: 0; }

        .timeline-dot {
            position: absolute; left: -40px; top: 2px; width: 32px; height: 32px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; z-index: 1; transition: 0.3s;
        }
        .timeline-dot.done { background: var(--green); color: #fff; }
        .timeline-dot.active { background: var(--navy); color: #fff; animation: pulseStep 2s infinite; box-shadow: 0 0 0 4px rgba(30,58,138,0.2); }
        .timeline-dot.pending { background: var(--surface2); color: var(--text-s); border: 2px solid var(--border); }
        @keyframes pulseStep { 0%, 100% { box-shadow: 0 0 0 4px rgba(30,58,138,0.2); } 50% { box-shadow: 0 0 0 8px rgba(30,58,138,0.1); } }

        .timeline-content {}
        .timeline-label { font-weight: 700; font-size: 0.95rem; }
        .timeline-label.done { color: var(--green); }
        .timeline-label.active { color: var(--navy); }
        .timeline-label.pending { color: var(--text-s); }
        .timeline-time { font-size: 0.8rem; color: var(--text-m); margin-top: 2px; }

        /* ORDER DETAILS */
        .item-row { display: flex; gap: 12px; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .item-row:last-child { border-bottom: none; }
        .item-row img { width: 48px; height: 48px; border-radius: 10px; object-fit: cover; }
        .item-name { font-weight: 600; font-size: 0.9rem; }
        .item-qty { color: var(--text-m); font-size: 0.85rem; }
        .item-price { margin-left: auto; font-weight: 700; color: var(--navy); font-size: 0.9rem; }

        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 0.9rem; }
        .detail-label { color: var(--text-m); }
        .detail-value { font-weight: 600; }
        .detail-total { font-size: 1.2rem; font-weight: 800; color: var(--navy); }

        .address-box {
            display: flex; gap: 12px; align-items: flex-start;
            background: var(--surface2); padding: 14px; border-radius: 12px; margin-top: 8px;
        }
        .address-box i { color: var(--navy); font-size: 1.1rem; margin-top: 3px; }
        .address-box-text { font-size: 0.9rem; color: var(--text-m); line-height: 1.4; }

        .btn-back {
            display: block; width: 100%; text-align: center; margin-top: 1rem;
            background: var(--surface); border: 1px solid var(--border); border-radius: 14px;
            padding: 14px; text-decoration: none; color: var(--navy); font-weight: 700; font-size: 0.95rem; transition: 0.2s;
        }
        .btn-back:hover { background: var(--surface2); }

        .cancelled-banner {
            background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
            border-radius: 12px; padding: 1rem; text-align: center; color: var(--red); font-weight: 600;
        }
    </style>
</head>
<body>

<div class="track-header">
    <a href="{{ route('cardapio.index') }}">
        <i class="fas fa-arrow-left"></i> CardÃ¡pio
    </a>
    <div class="logo">Bella<span>Cucina</span></div>
    <a href="{{ route('cliente.pedidos') }}">
        Meus Pedidos <i class="fas fa-list"></i>
    </a>
</div>

<div class="track-wrap">
    @php
        $steps = [
            ['key' => 'confirmado',      'label' => 'Pedido Confirmado',    'icon' => 'fa-check',       'time' => $pedido->confirmado_em],
            ['key' => 'em_producao',     'label' => 'Em ProduÃ§Ã£o',         'icon' => 'fa-fire',        'time' => $pedido->producao_em],
            ['key' => 'saindo_entrega',  'label' => 'Saindo para Entrega', 'icon' => 'fa-motorcycle',  'time' => $pedido->saiu_em],
            ['key' => 'entregue',        'label' => 'Entregue',            'icon' => 'fa-home',        'time' => $pedido->entregue_em],
        ];
        $statusOrder = ['aguardando_pagamento' => -1, 'confirmado' => 0, 'em_producao' => 1, 'saindo_entrega' => 2, 'entregue' => 3, 'cancelado' => -2];
        $currentIdx = $statusOrder[$pedido->status] ?? -1;

        $statusLabels = [
            'aguardando_pagamento' => 'Aguardando Pagamento',
            'confirmado' => 'Pedido Confirmado',
            'em_producao' => 'Em ProduÃ§Ã£o',
            'saindo_entrega' => 'Saindo para Entrega',
            'entregue' => 'Entregue!',
            'cancelado' => 'Cancelado',
        ];
    @endphp

    <!-- STATUS HERO -->
    <div class="status-hero" id="statusHero">
        <div class="code">PEDIDO #{{ $pedido->codigo }}</div>
        <div class="current-status" id="currentStatusLabel">{{ $statusLabels[$pedido->status] ?? $pedido->status }}</div>
        @if($pedido->status !== 'entregue' && $pedido->status !== 'cancelado')
            <div class="eta">
                Tempo estimado: <strong>{{ $pedido->tempo_estimado ?? 45 }} min</strong>
            </div>
        @endif
    </div>

    @if($pedido->status === 'cancelado')
        <div class="cancelled-banner" style="margin-bottom: 1.5rem;">
            <i class="fas fa-times-circle"></i> Este pedido foi cancelado.
            @if($pedido->motivo_cancelamento)
                <br><small>{{ $pedido->motivo_cancelamento }}</small>
            @endif
        </div>
    @endif

    <!-- TIMELINE -->
    <div class="section">
        <h2 class="section-title"><i class="fas fa-route"></i> Progresso</h2>
        <div class="timeline" id="timeline">
            @foreach($steps as $i => $step)
                @php
                    $stepState = 'pending';
                    if ($i < $currentIdx) $stepState = 'done';
                    elseif ($i === $currentIdx) $stepState = 'active';
                @endphp
                <div class="timeline-step" data-step="{{ $step['key'] }}">
                    <div class="timeline-dot {{ $stepState }}">
                        <i class="fas {{ $step['icon'] }}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-label {{ $stepState }}">{{ $step['label'] }}</div>
                        <div class="timeline-time">
                            @if($step['time'])
                                {{ $step['time']->format('H:i') }}
                            @elseif($stepState === 'active')
                                Agora
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ORDER ITEMS -->
    <div class="section">
        <h2 class="section-title"><i class="fas fa-utensils"></i> Itens do Pedido</h2>
        @foreach($pedido->itens as $item)
            <div class="item-row">
                @if($item->prato)
                    <img src="{{ asset('storage/' . $item->prato->imagem) }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100&auto=format&fit=crop'">
                @endif
                <div>
                    <div class="item-name">{{ $item->prato->nome ?? 'Item' }}</div>
                    <div class="item-qty">{{ $item->quantidade }}x R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</div>
                </div>
                <div class="item-price">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</div>
            </div>
        @endforeach

        <hr style="border: none; border-top: 1px solid var(--border); margin: 12px 0;">
        <div class="detail-row"><span class="detail-label">Subtotal</span><span class="detail-value">R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</span></div>
        @if($pedido->taxa_entrega > 0)
            <div class="detail-row"><span class="detail-label">Taxa de Entrega</span><span class="detail-value">R$ {{ number_format($pedido->taxa_entrega, 2, ',', '.') }}</span></div>
        @endif
        @if($pedido->desconto > 0)
            <div class="detail-row" style="color: var(--green);"><span class="detail-label">Desconto PIX</span><span class="detail-value">- R$ {{ number_format($pedido->desconto, 2, ',', '.') }}</span></div>
        @endif
        <hr style="border: none; border-top: 1px solid var(--border); margin: 12px 0;">
        <div class="detail-row"><span class="detail-label">Total</span><span class="detail-total">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span></div>
    </div>

    <!-- ADDRESS -->
    @if($pedido->endereco)
        <div class="section">
            <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> EndereÃ§o de Entrega</h2>
            <div class="address-box">
                <i class="fas fa-map-pin"></i>
                <div class="address-box-text">
                    {{ $pedido->endereco->logradouro }}, {{ $pedido->endereco->numero }}
                    @if($pedido->endereco->complemento) — {{ $pedido->endereco->complemento }} @endif
                    <br>
                    {{ $pedido->endereco->bairro }} — {{ $pedido->endereco->cidade }}/{{ $pedido->endereco->uf ?? $pedido->endereco->estado }}
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('cardapio.index') }}" class="btn-back">
        <i class="fas fa-utensils" style="margin-right: 8px;"></i> Fazer Novo Pedido
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const status = '{{ $pedido->status }}';
    if (status === 'entregue' || status === 'cancelado') return;

    // Poll for status updates every 10 seconds
    const statusApiUrl = '{{ route("cliente.pedido.status-api", $pedido->codigo) }}';
    const statusLabels = {
        'aguardando_pagamento': 'Aguardando Pagamento',
        'confirmado': 'Pedido Confirmado',
        'em_producao': 'Em ProduÃ§Ã£o',
        'saindo_entrega': 'Saindo para Entrega',
        'entregue': 'Entregue!',
    };
    const stepKeys = ['confirmado', 'em_producao', 'saindo_entrega', 'entregue'];

    setInterval(() => {
        fetch(statusApiUrl, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                const newIdx = stepKeys.indexOf(data.status);
                document.getElementById('currentStatusLabel').textContent = statusLabels[data.status] || data.status;

                // Update timeline
                document.querySelectorAll('.timeline-step').forEach((el, i) => {
                    const dot = el.querySelector('.timeline-dot');
                    const label = el.querySelector('.timeline-label');
                    const time = el.querySelector('.timeline-time');

                    dot.className = 'timeline-dot ' + (i < newIdx ? 'done' : i === newIdx ? 'active' : 'pending');
                    label.className = 'timeline-label ' + (i < newIdx ? 'done' : i === newIdx ? 'active' : 'pending');

                    // Update times from API
                    const timeFields = ['confirmado_em', 'producao_em', 'saiu_em', 'entregue_em'];
                    if (data[timeFields[i]]) {
                        time.textContent = data[timeFields[i]];
                    } else if (i === newIdx) {
                        time.textContent = 'Agora';
                    }
                });

                if (data.status === 'entregue') {
                    document.querySelector('.eta')?.remove();
                }
            })
            .catch(() => {});
    }, 10000);
});
</script>

</body>
</html>
