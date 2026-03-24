<div class="success-container" style="text-align: center; padding: 80px 20px;">
    <div class="success-icon" style="width: 100px; height: 100px; background: var(--green); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 3rem; box-shadow: 0 0 40px rgba(34, 197, 94, 0.3);">
        <i class="fas fa-check"></i>
    </div>

    <h1 style="font-family: 'Playfair Display', serif; color: var(--text); font-size: 2.5rem;">Pagamento Confirmado!</h1>
    <p style="color: var(--text-m); font-size: 1.1rem; max-width: 500px; margin: 20px auto 40px;">
        Recebemos seu pagamento com sucesso. Nossa cozinha já foi notificada e em breve seu pedido entrará em preparo.
    </p>

    <div style="display: inline-flex; flex-direction: column; gap: 20px; min-width: 300px;">
        <a href="{{ route('orders.index') }}" style="background: var(--amber); color: var(--dark); padding: 18px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: 0.3s;">
            <i class="fas fa-motorcycle"></i> Acompanhar Entrega
        </a>
        <a href="{{ route('cardapio') }}" style="color: var(--text-m); text-decoration: none; font-size: 0.9rem;">
            Voltar para a página inicial
        </a>
    </div>
</div>