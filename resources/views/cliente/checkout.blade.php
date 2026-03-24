<form action="{{ route('checkout.process') }}" method="POST" id="payment-form">
    @csrf
    <div class="checkout-grid">
        <div class="checkout-section">
            <h3><i class="fas fa-map-marker-alt"></i> Endereço de Entrega</h3>
            <p>{{ auth()->user()->address ?? 'Cadastre um endereço nas configurações' }}</p>
            
            <h3 style="margin-top: 30px;"><i class="fas fa-credit-card"></i> Método de Pagamento</h3>
            <div class="payment-options">
                <label class="pay-card">
                    <input type="radio" name="payment_method" value="pix" checked>
                    <span><i class="fab fa-pix"></i> PIX (Desconto 5%)</span>
                </label>
                <label class="pay-card">
                    <input type="radio" name="payment_method" value="card">
                    <span><i class="fas fa-credit-card"></i> Cartão de Crédito</span>
                </label>
            </div>
        </div>

        <div class="order-lock">
            <button type="submit" class="btn-finalize">
                <i class="fas fa-lock"></i> Finalizar Compra Segura
            </button>
            <p class="secure-text">Sua transação é criptografada pela Trindade Tech</p>
        </div>
    </div>
</form>