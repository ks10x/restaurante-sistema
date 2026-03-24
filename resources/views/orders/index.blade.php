<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos — Bella Cucina</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amber: #D4A373; --amber-d: #B38456; --dark: #1A1512;
            --dark2: #24201A; --surface: #2A2420; --text: #F5EDD8;
            --text-m: #A0A0A0; --border: rgba(212,163,115,0.15);
            --green: #22C55E;
        }
        body { background: var(--dark); color: var(--text); font-family: 'DM Sans', sans-serif; margin: 0; padding-bottom: 80px; }
        
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .back-link { color: var(--amber); text-decoration: none; display: flex; align-items: center; gap: 8px; margin-bottom: 30px; font-weight: 500; }
        
        .page-header { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 30px; }

        /* CARD DE PEDIDO */
        .order-card { background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 30px; margin-bottom: 25px; transition: 0.3s; }
        .order-card:hover { border-color: var(--amber); }

        .order-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 1px solid var(--border); padding-bottom: 20px; }
        .order-id { font-size: 0.85rem; color: var(--text-m); text-transform: uppercase; letter-spacing: 1px; }
        .order-id span { color: var(--amber); font-weight: 700; margin-left: 5px; }
        .order-date { font-size: 0.9rem; color: var(--text-s); margin-top: 5px; }

        /* RASTREIO (STEPPER) */
        .tracking-wrapper { margin: 40px 0; position: relative; }
        .tracking-line { position: absolute; top: 15px; left: 0; width: 100%; height: 2px; background: var(--dark2); z-index: 1; }
        .tracking-progress { position: absolute; top: 15px; left: 0; height: 2px; background: var(--amber); z-index: 2; transition: 1s ease-in-out; }
        
        .steps { display: flex; justify-content: space-between; position: relative; z-index: 3; }
        .step { display: flex; flex-direction: column; align-items: center; gap: 10px; flex: 1; }
        .step-icon { width: 32px; height: 32px; border-radius: 50%; background: var(--dark2); border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 12px; transition: 0.3s; }
        .step.active .step-icon { background: var(--amber); border-color: var(--amber); color: var(--dark); box-shadow: 0 0 15px rgba(212, 163, 115, 0.4); }
        .step-label { font-size: 0.75rem; font-weight: 600; color: var(--text-m); text-align: center; }
        .step.active .step-label { color: var(--amber); }

        /* DETALHES DO PEDIDO */
        .order-content { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; margin-top: 25px; }
        .items-list { color: var(--text-m); font-size: 0.95rem; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        
        .info-group { background: var(--dark); padding: 20px; border-radius: 16px; border: 1px solid var(--border); }
        .info-title { font-size: 0.75rem; text-transform: uppercase; color: var(--amber); font-weight: 700; margin-bottom: 12px; display: block; }
        .info-text { font-size: 0.85rem; line-height: 1.5; color: var(--text-m); }

        .total-box { margin-top: 20px; text-align: right; }
        .total-label { color: var(--text-m); font-size: 0.9rem; }
        .total-value { color: var(--amber); font-size: 1.4rem; font-weight: 800; margin-left: 10px; }

        @media (max-width: 768px) { .order-content { grid-template-columns: 1fr; } .order-top { flex-direction: column; gap: 15px; } }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('cardapio') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar ao Cardápio
    </a>

    <h1 class="page-header">Meus Pedidos</h1>

    @forelse($pedidos as $pedido)
    <div class="order-card">
        <div class="order-top">
            <div>
                <div class="order-id">Pedido <span>#{{ $pedido->id_unico }}</span></div>
                <div class="order-date">{{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</div>
            </div>
            <div style="text-align: right;">
                <span style="background: rgba(34, 197, 94, 0.1); color: var(--green); padding: 6px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 700;">
                    {{ $pedido->metodo_pagamento }}
                </span>
            </div>
        </div>

        <div class="tracking-wrapper">
            <div class="tracking-line"></div>
            <div class="tracking-progress" style="width: {{ $pedido->status == 'pedido enviado' ? '0%' : ($pedido->status == 'pedido em preparo' ? '50%' : '100%') }}"></div>
            
            <div class="steps">
                <div class="step {{ in_array($pedido->status, ['pedido enviado', 'pedido em preparo', 'saindo para entrega']) ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-paper-plane"></i></div>
                    <span class="step-label">Enviado</span>
                </div>
                <div class="step {{ in_array($pedido->status, ['pedido em preparo', 'saindo para entrega']) ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-utensils"></i></div>
                    <span class="step-label">Em Preparo</span>
                </div>
                <div class="step {{ $pedido->status == 'saindo para entrega' ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-motorcycle"></i></div>
                    <span class="step-label">Saiu para Entrega</span>
                </div>
            </div>
        </div>

        <div class="order-content">
            <div class="items-list">
                <span class="info-title">Descrição do Pedido</span>
                @foreach($pedido->itens as $item)
                    <div class="item-row">
                        <span>{{ $item->quantidade }}x {{ $item->nome }}</span>
                        <span>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                    </div>
                @endforeach
                
                <div class="total-box">
                    <span class="total-label">Total Pago</span>
                    <span class="total-value">R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>

            <div class="info-group">
                <span class="info-title">Endereço de Entrega</span>
                <div class="info-text">
                    <i class="fas fa-map-marker-alt" style="color: var(--amber); margin-right: 5px;"></i>
                    {{ $pedido->endereco_entrega }}
                </div>
            </div>
        </div>
    </div>
    @empty
        <div style="text-align: center; padding: 100px 20px; color: var(--text-m);">
            <i class="fas fa-receipt" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.3;"></i>
            <p>Você ainda não realizou nenhum pedido.</p>
        </div>
    @endforelse
</div>

</body>
</html>