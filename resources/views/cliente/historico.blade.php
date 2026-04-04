<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos — Bella Cucina</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>
    <style>
        :root {
            --brand: #1e3a8a;
            --brand-d: #1e40af;
            --bg: #f8fafc;
            --bg2: #f1f5f9;
            --surface: #ffffff;
            --text: #0f172a;
            --text-m: #64748b;
            --text-s: #94a3b8;
            --border: rgba(30, 58, 138, 0.12);
            --green: #059669;
            --red: #dc2626;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: 'DM Sans', sans-serif; min-height: 100vh; }

        .container { max-width: 800px; margin: 0 auto; padding: 40px 20px 80px; }
        .back-link { color: var(--brand); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 30px; font-weight: 500; font-size: 0.9rem; }
        .back-link:hover { text-decoration: underline; }

        .page-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 8px; color: var(--text); display: flex; align-items: center; gap: 12px; }
        .page-title i { color: var(--brand); }
        .page-subtitle { color: var(--text-m); font-size: 0.9rem; margin-bottom: 30px; }

        /* Pedido Card */
        .pedido-card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; margin-bottom: 20px; overflow: hidden; transition: box-shadow 0.3s; }
        .pedido-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.06); }

        .pedido-header { padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none; }
        .pedido-header:hover { background: var(--bg); }

        .pedido-info { display: flex; align-items: center; gap: 16px; }
        .pedido-icon { width: 48px; height: 48px; background: rgba(30,58,138,0.06); border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pedido-icon i { color: var(--brand); font-size: 1.2rem; }

        .pedido-meta h3 { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
        .pedido-meta p { font-size: 0.8rem; color: var(--text-m); }

        .pedido-right { text-align: right; display: flex; align-items: center; gap: 12px; }
        .pedido-total { font-weight: 800; font-size: 1rem; color: var(--text); }

        .badge { padding: 4px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-gray { background: #f1f5f9; color: #64748b; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-purple { background: #ede9fe; color: #6b21a8; }

        .chevron { color: var(--text-s); transition: transform 0.3s; font-size: 0.8rem; }
        .pedido-card.open .chevron { transform: rotate(180deg); }

        /* Detalhes Expandidos */
        .pedido-details { display: none; padding: 0 24px 24px; border-top: 1px solid var(--border); }
        .pedido-card.open .pedido-details { display: block; }

        .detail-section { padding: 16px 0; border-bottom: 1px solid rgba(0,0,0,0.04); }
        .detail-section:last-child { border-bottom: none; }
        .detail-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--brand); margin-bottom: 10px; }

        .item-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 8px 0; font-size: 0.9rem; }
        .item-nome { color: var(--text); font-weight: 600; }
        .item-qtd { color: var(--text-m); font-size: 0.8rem; }
        .item-obs { color: #ea580c; font-size: 0.75rem; font-style: italic; margin-top: 2px; }
        .item-preco { color: var(--text); font-weight: 700; white-space: nowrap; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .info-item { font-size: 0.85rem; }
        .info-item .label { color: var(--text-s); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .info-item .value { color: var(--text); font-weight: 600; margin-top: 2px; }

        .total-box { background: var(--bg); border-radius: 14px; padding: 16px; margin-top: 8px; }
        .total-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-m); padding: 4px 0; }
        .total-row.final { font-size: 1.1rem; font-weight: 800; color: var(--text); padding-top: 10px; margin-top: 6px; border-top: 2px solid var(--border); }

        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state i { font-size: 3rem; color: var(--text-s); margin-bottom: 20px; }
        .empty-state h3 { font-size: 1.2rem; color: var(--text-m); margin-bottom: 8px; }
        .empty-state p { color: var(--text-s); font-size: 0.9rem; }
        .empty-state a { display: inline-block; margin-top: 20px; background: var(--brand); color: #fff; padding: 14px 32px; border-radius: 14px; text-decoration: none; font-weight: 700; }

        .pagination-wrapper { display: flex; justify-content: center; margin-top: 30px; }
        .pagination-wrapper nav span, .pagination-wrapper nav a { padding: 8px 14px; margin: 0 4px; border-radius: 10px; font-size: 0.85rem; }

        @media (max-width: 600px) { .info-grid { grid-template-columns: 1fr; } .pedido-header { flex-wrap: wrap; gap: 12px; } }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('cardapio.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar ao Menu
    </a>

    <h1 class="page-title"><i class="fas fa-receipt"></i> Meus Pedidos</h1>
    <p class="page-subtitle">Histórico completo de todos os seus pedidos na Bella Cucina.</p>

    @forelse($pedidos as $pedido)
    @php
        $statusConf = \App\Models\Pedido::STATUS_LABELS[$pedido->status] ?? ['label' => $pedido->status, 'cor' => 'gray'];
        $badgeClass = match($statusConf['cor']) {
            'gray' => 'badge-gray', 'blue' => 'badge-blue', 'amber' => 'badge-amber',
            'orange' => 'badge-orange', 'green' => 'badge-green', 'red' => 'badge-red',
            'purple' => 'badge-purple', default => 'badge-gray',
        };
    @endphp
    <div class="pedido-card" id="pedido-{{ $pedido->id }}">
        <div class="pedido-header" onclick="togglePedido($pedido=id)">
            <div class="pedido-info">
                <div class="pedido-icon">
                    <i class="fas fa-{{ $pedido->tipo_entrega == 'entrega' ? 'motorcycle' : 'store' }}"></i>
                </div>
                <div class="pedido-meta">
                    <h3>Pedido #{{ $pedido->codigo }}</h3>
                    <p>{{ $pedido->created_at->format('d/m/Y') }} às {{ $pedido->created_at->format('H:i') }} • {{ $pedido->itens->count() }} {{ $pedido->itens->count() == 1 ? 'item' : 'itens' }}</p>
                </div>
            </div>
            <div class="pedido-right">
                <span class="badge {{ $badgeClass }}">{{ $statusConf['label'] }}</span>
                <span class="pedido-total">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                <i class="fas fa-chevron-down chevron"></i>
            </div>
        </div>

        <div class="pedido-details">
            <!-- Itens -->
            <div class="detail-section">
                <div class="detail-label"><i class="fas fa-utensils"></i> Itens do Pedido</div>
                @foreach($pedido->itens as $item)
                <div class="item-row">
                    <div>
                        <div class="item-nome">{{ $item->prato_nome ?? ($item->prato->nome ?? 'Item') }}</div>
                        <div class="item-qtd">{{ $item->quantidade }}x • R$ {{ number_format($item->preco_unitario, 2, ',', '.') }} un.</div>
                        @if($item->observacoes)
                            <div class="item-obs">Obs: {{ $item->observacoes }}</div>
                        @endif
                    </div>
                    <div class="item-preco">R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</div>
                </div>
                @endforeach
            </div>

            <!-- Informações -->
            <div class="detail-section">
                <div class="detail-label"><i class="fas fa-info-circle"></i> Informações do Pedido</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="label">Tipo de Entrega</div>
                        <div class="value">{{ $pedido->tipo_entrega == 'entrega' ? '🛵 Delivery' : '🏪 Retirada no Local' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Forma de Pagamento</div>
                        <div class="value">
                            @php
                                $pgto = match($pedido->pagamento_metodo) {
                                    'pix' => '💠 PIX',
                                    'cartao_credito' => '💳 Cartão de Crédito',
                                    'cartao_debito' => '💳 Cartão de Débito',
                                    'dinheiro' => '💵 Dinheiro',
                                    'vale_alimentacao' => '🎫 Vale Alimentação',
                                    default => $pedido->pagamento_metodo ?? 'Não informado',
                                };
                            @endphp
                            {{ $pgto }}
                        </div>
                    </div>
                    @if($pedido->tipo_entrega == 'entrega' && $pedido->endereco)
                    <div class="info-item" style="grid-column: span 2;">
                        <div class="label">Endereço de Entrega</div>
                        <div class="value">{{ $pedido->endereco->logradouro ?? '' }}, {{ $pedido->endereco->numero ?? '' }} - {{ $pedido->endereco->bairro ?? '' }}</div>
                    </div>
                    @endif
                    @if($pedido->troco_para)
                    <div class="info-item">
                        <div class="label">Troco Para</div>
                        <div class="value" style="color: #ea580c;">R$ {{ number_format($pedido->troco_para, 2, ',', '.') }}</div>
                    </div>
                    @endif
                    @if($pedido->observacoes)
                    <div class="info-item" style="grid-column: span 2;">
                        <div class="label">Observações</div>
                        <div class="value" style="font-weight: 500; font-style: italic;">{{ $pedido->observacoes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Valores -->
            <div class="detail-section">
                <div class="detail-label"><i class="fas fa-calculator"></i> Resumo Financeiro</div>
                <div class="total-box">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Taxa de Entrega</span>
                        <span>R$ {{ number_format($pedido->taxa_entrega, 2, ',', '.') }}</span>
                    </div>
                    @if($pedido->desconto > 0)
                    <div class="total-row" style="color: #059669;">
                        <span>Desconto</span>
                        <span>-R$ {{ number_format($pedido->desconto, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="total-row final">
                        <span>Total Pago</span>
                        <span>R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-shopping-bag"></i>
        <h3>Nenhum pedido realizado</h3>
        <p>Quando você fizer seu primeiro pedido, ele aparecerá aqui.</p>
        <a href="{{ route('cardapio.index') }}"><i class="fas fa-utensils"></i> Explorar Cardápio</a>
    </div>
    @endforelse

    @if($pedidos->hasPages())
    <div class="pagination-wrapper">
        {{ $pedidos->links() }}
    </div>
    @endif
</div>

<script>
function togglePedido(id) {
    document.getElementById('pedido-' + id).classList.toggle('open');
}
</script>

</body>
</html>
