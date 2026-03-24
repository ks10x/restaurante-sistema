<div class="payment-container" style="text-align: center; padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <div class="status-badge" style="background: rgba(212, 163, 115, 0.1); color: var(--amber); padding: 8px 16px; border-radius: 50px; display: inline-block; margin-bottom: 20px; font-weight: 700;">
        <i class="fas fa-clock"></i> Aguardando Pagamento
    </div>
    
    <h1 style="font-family: 'Playfair Display', serif; color: var(--text); margin-bottom: 10px;">Quase lá!</h1>
    <p style="color: var(--text-m); margin-bottom: 30px;">Escaneie o QR Code abaixo com o app do seu banco para finalizar seu pedido na Bella Cucina.</p>
    
    <div class="qr-code-wrapper" style="background: #FFF; padding: 25px; display: inline-block; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <img src="{{ $qr_code_url }}" alt="QR Code PIX" style="width: 250px; height: 250px; display: block;">
    </div>

    <div class="pix-copy-paste" style="margin-top: 40px; background: var(--surface); padding: 20px; border-radius: 16px; border: 1px solid var(--border);">
        <label style="display: block; color: var(--amber); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">Código PIX Copia e Cola</label>
        <div style="display: flex; gap: 10px;">
            <input type="text" value="{{ $pix_copy_paste }}" id="pixCode" readonly 
                   style="flex: 1; padding: 12px; background: var(--dark); border: 1px solid var(--border); color: var(--text-m); border-radius: 8px; font-size: 0.9rem;">
            <button onclick="copyPix()" class="btn-primary" style="background: var(--amber); border: none; padding: 0 20px; border-radius: 8px; cursor: pointer; color: var(--dark);">
                <i class="fas fa-copy"></i>
            </button>
        </div>
    </div>

    <div style="margin-top: 40px; display: flex; flex-direction: column; gap: 15px;">
        <a href="{{ route('orders.index') }}" class="btn-outline" style="text-decoration: none; color: var(--text); font-weight: 500;">
            <i class="fas fa-receipt"></i> Ver detalhes do pedido
        </a>
        <p style="font-size: 0.8rem; color: var(--text-s);">O pedido será cancelado automaticamente se não for pago em 30 minutos.</p>
    </div>
</div>

<script>

function copyPix() {
    var copyText = document.getElementById("pixCode");
    copyText.select();
    navigator.clipboard.writeText(copyText.value);
    // Aqui você pode adicionar um toast do SweetAlert para ficar mais "Tech"
    alert("Código copiado com sucesso!");
}

setInterval(function() {
        // Faz uma chamada rápida para uma rota que checa o status do pedido
        fetch('/api/order-status/{{ $order->id }}')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'pedido enviado') {
                    window.location.href = "{{ route('checkout.sucesso') }}";
                }
            });
}, 5000); // 5 segundos

</script>