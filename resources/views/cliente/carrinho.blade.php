<div class="container">
    <h1 class="page-header">Seu Carrinho</h1>
    
    <div class="cart-grid">
        <div class="cart-items">
            @foreach($cartItems as $item)
                <div class="cart-card">
                    <img src="{{ $item->image }}" alt="{{ $item->name }}">
                    <div class="item-info">
                        <h3>{{ $item->name }}</h3>
                        <p>{{ $item->description }}</p>
                        <span class="price">R$ {{ number_format($item->price, 2, ',', '.') }}</span>
                    </div>
                    <div class="quantity-control">
                        <button onclick="updateQty({{ $item->id }}, -1)">-</button>
                        <span>{{ $item->quantity }}</span>
                        <button onclick="updateQty({{ $item->id }}, 1)">+</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cart-summary">
            <span class="info-title">Resumo do Pedido</span>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Taxa de Entrega</span>
                <span class="free">Grátis</span>
            </div>
            <hr>
            <div class="summary-row total">
                <span>Total</span>
                <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
            </div>
            
            <a href="{{ route('checkout.index') }}" class="btn-confirm">
                Confirmar e Ir para Pagamento
            </a>
        </div>
    </div>
</div>