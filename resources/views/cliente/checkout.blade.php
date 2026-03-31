<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Bella Cucina</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --navy: #1E3A8A;
            --navy-d: #1E40AF;
            --navy-l: #3B82F6;
            --bg: #F8FAFC;
            --surface: #FFFFFF;
            --surface2: #F1F5F9;
            --text: #0F172A;
            --text-m: #64748B;
            --text-s: #94A3B8;
            --border: rgba(30, 58, 138, 0.12);
            --red: #EF4444;
            --green: #10B981;
            --green-bg: #ECFDF5;
            --radius: 16px;
            --font-h: 'Playfair Display', serif;
            --font-b: 'DM Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: var(--font-b); min-height: 100vh; }

        /* HEADER */
        .checkout-header {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border); padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between; height: 72px;
            position: sticky; top: 0; z-index: 50;
        }
        .checkout-header a { text-decoration: none; color: var(--text-m); display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .checkout-header a:hover { color: var(--navy); }
        .logo { font-family: var(--font-h); font-size: 1.5rem; color: var(--navy); letter-spacing: -0.5px; }
        .logo span { color: var(--text-m); font-weight: 400; font-size: 1.1rem; }

        /* LAYOUT */
        .checkout-wrap { max-width: 1100px; margin: 0 auto; padding: 2rem; display: grid; grid-template-columns: 1fr 380px; gap: 2rem; }
        @media(max-width: 900px) { .checkout-wrap { grid-template-columns: 1fr; } }

        /* SECTIONS */
        .section { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; margin-bottom: 1.5rem; }
        .section-title { font-family: var(--font-h); font-size: 1.3rem; color: var(--text); margin-bottom: 1rem; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: var(--navy); font-size: 1.1rem; }

        /* ITEMS */
        .cart-item { display: flex; gap: 12px; align-items: center; padding: 12px; border-radius: 14px; background: var(--surface2); margin-bottom: 10px; border: 1px solid transparent; transition: 0.2s; }
        .cart-item:hover { border-color: var(--border); }
        .cart-item img { width: 56px; height: 56px; border-radius: 12px; object-fit: cover; }
        .cart-item-info { flex: 1; }
        .cart-item-name { font-weight: 600; font-size: 0.95rem; }
        .cart-item-price { color: var(--navy); font-weight: 700; font-size: 0.9rem; margin-top: 2px; }
        .cart-item-qty { color: var(--text-m); font-size: 0.85rem; }
        .cart-item-remove { background: none; border: none; color: var(--red); cursor: pointer; padding: 8px; border-radius: 8px; transition: 0.2s; }
        .cart-item-remove:hover { background: rgba(239,68,68,0.1); }

        /* ADDRESS */
        .address-card {
            border: 2px solid var(--border); border-radius: 14px; padding: 14px 16px;
            cursor: pointer; transition: 0.2s; margin-bottom: 10px; position: relative;
        }
        .address-card:hover { border-color: var(--navy-l); }
        .address-card.selected { border-color: var(--navy); background: rgba(30,58,138,0.04); }
        .address-card .check { position: absolute; top: 14px; right: 14px; width: 22px; height: 22px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .address-card.selected .check { background: var(--navy); border-color: var(--navy); color: #fff; }
        .address-label { font-weight: 700; font-size: 0.85rem; color: var(--navy); margin-bottom: 4px; }
        .address-text { font-size: 0.9rem; color: var(--text-m); line-height: 1.4; }

        .btn-add-address { background: none; border: 2px dashed var(--border); border-radius: 14px; padding: 14px; width: 100%; cursor: pointer; color: var(--navy); font-weight: 600; font-size: 0.9rem; transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-add-address:hover { border-color: var(--navy); background: rgba(30,58,138,0.03); }

        /* NEW ADDRESS FORM */
        .new-address-form { display: none; background: var(--surface2); border-radius: 14px; padding: 1.2rem; margin-top: 10px; }
        .new-address-form.open { display: block; animation: slideDown 0.3s ease; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
        .form-row.full { grid-template-columns: 1fr; }
        .form-row.triple { grid-template-columns: 1fr 100px 1fr; }
        .form-field label { font-size: 0.75rem; font-weight: 700; color: var(--text-s); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block; }
        .form-field input { width: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; font-size: 0.9rem; font-family: var(--font-b); outline: none; transition: 0.2s; background: var(--surface); }
        .form-field input:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(30,58,138,0.1); }
        .btn-save-address { background: var(--navy); color: #fff; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 700; cursor: pointer; font-size: 0.9rem; transition: 0.2s; width: 100%; margin-top: 5px; }
        .btn-save-address:hover { background: var(--navy-d); }

        /* PAYMENT METHOD */
        .payment-option {
            border: 2px solid var(--border); border-radius: 14px; padding: 16px;
            cursor: pointer; transition: 0.2s; margin-bottom: 10px; display: flex; align-items: center; gap: 14px;
        }
        .payment-option:hover { border-color: var(--navy-l); }
        .payment-option.selected { border-color: var(--navy); background: rgba(30,58,138,0.04); }
        .payment-option .radio { width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .payment-option.selected .radio { border-color: var(--navy); }
        .payment-option.selected .radio::after { content: ''; width: 10px; height: 10px; border-radius: 50%; background: var(--navy); }
        .payment-icon { font-size: 1.3rem; color: var(--navy); }
        .payment-label { font-weight: 600; font-size: 0.95rem; }
        .payment-badge { font-size: 0.7rem; background: var(--green-bg); color: var(--green); padding: 3px 8px; border-radius: 6px; font-weight: 700; margin-left: auto; }
        .payment-option.disabled { opacity: 0.45; cursor: not-allowed; }

        /* SIDEBAR */
        .sidebar-summary { position: sticky; top: 90px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 0.95rem; }
        .summary-row.discount { color: var(--green); }
        .summary-divider { border: none; border-top: 1px solid var(--border); margin: 10px 0; }
        .summary-total { display: flex; justify-content: space-between; font-size: 1.3rem; font-weight: 800; color: var(--navy); padding: 8px 0; }

        .btn-confirm {
            width: 100%; background: linear-gradient(135deg, var(--navy) 0%, var(--navy-d) 100%);
            color: #fff; border: none; padding: 18px; border-radius: 14px; font-weight: 800; font-size: 1.05rem;
            cursor: pointer; transition: all 0.3s; margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(30,58,138,0.25);
        }
        .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30,58,138,0.35); }
        .btn-confirm:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-confirm .spinner { display: none; }
        .btn-confirm.loading .spinner { display: inline-block; }
        .btn-confirm.loading .label { display: none; }

        /* EMPTY */
        .empty-cart { text-align: center; padding: 4rem 2rem; }
        .empty-cart i { font-size: 3rem; color: var(--border); margin-bottom: 15px; }
        .empty-cart p { color: var(--text-m); }
        .empty-cart a { display: inline-block; margin-top: 15px; background: var(--navy); color: #fff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: 0.2s; }
        .empty-cart a:hover { background: var(--navy-d); }

        /* NOTES */
        .notes-field { width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px; font-family: var(--font-b); font-size: 0.9rem; resize: vertical; min-height: 60px; outline: none; transition: 0.2s; }
        .notes-field:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(30,58,138,0.1); }

        .secure-badge { display: flex; align-items: center; justify-content: center; gap: 8px; color: var(--text-s); font-size: 0.8rem; margin-top: 12px; }
        .secure-badge i { color: var(--green); }
    </style>
</head>
<body>

<div class="checkout-header">
    <a href="{{ route('cardapio.index') }}">
        <i class="fas fa-arrow-left"></i> Voltar ao Cardápio
    </a>
    <div class="logo">Bella<span>Cucina</span></div>
    <div style="width: 140px;"></div>
</div>

<div id="checkoutContent" 
     data-enderecos='{!! json_encode($enderecos ?? []) !!}'
     data-config='{!! json_encode($config ?? ["taxa_entrega" => 5, "pedido_minimo" => 30, "desconto_pix" => 5]) !!}'></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('bellacucina_cart') || '[]');
    const container = document.getElementById('checkoutContent');
    const enderecos = JSON.parse(container.dataset.enderecos || '[]');
    const config = JSON.parse(container.dataset.config || '{}');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let selectedEnderecoId = enderecos.length > 0 ? (enderecos.find(e => e.principal)?.id || enderecos[0].id) : null;
    let selectedPayment = 'pix';
    let isProcessing = false;

    // Removed redundant getElementById here and moved up to handle data-attributes
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-basket"></i>
                <p style="font-size: 1.2rem; font-weight: 600; margin-bottom: 5px;">Seu carrinho está vazio</p>
                <p>Adicione pratos deliciosos do nosso cardápio!</p>
                <a href="{{ route('cardapio.index') }}">Ver Cardápio</a>
            </div>
        `;
        return;
    }

    render();

    function render() {
        const subtotal = cart.reduce((sum, item) => sum + (item.preco * item.qtd), 0);
        const taxa = config.taxa_entrega;
        const desconto = selectedPayment === 'pix' ? Math.round(subtotal * (config.desconto_pix / 100) * 100) / 100 : 0;
        const total = Math.max(0, subtotal + taxa - desconto);

        container.innerHTML = `
        <div class="checkout-wrap">
            <div>
                <!-- ITEMS -->
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-shopping-bag"></i> Resumo do Pedido</h2>
                    ${cart.map((item, i) => `
                        <div class="cart-item">
                            <img src="/storage/${item.imagem}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100&auto=format&fit=crop'">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.nome}</div>
                                <div class="cart-item-price">R$ ${(item.preco * item.qtd).toLocaleString('pt-br', {minimumFractionDigits: 2})}</div>
                                <div class="cart-item-qty">${item.qtd}x R$ ${item.preco.toLocaleString('pt-br', {minimumFractionDigits: 2})}</div>
                            </div>
                            <button class="cart-item-remove js-remove" data-index="${i}"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    `).join('')}
                </div>

                <!-- ADDRESS -->
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Endereço de Entrega</h2>
                    ${enderecos.map(e => `
                        <div class="address-card js-addr ${e.id === selectedEnderecoId ? 'selected' : ''}" data-id="${e.id}">
                            <div class="address-label">${e.apelido || (e.tipo || 'Casa')}</div>
                            <div class="address-text">${e.logradouro}, ${e.numero}${e.complemento ? ' - ' + e.complemento : ''}<br>${e.bairro} — ${e.cidade}/${e.uf || e.estado}</div>
                            <div class="check"><i class="fas fa-check" style="font-size: 10px;"></i></div>
                        </div>
                    `).join('')}
                    <button class="btn-add-address" id="btnNewAddr"><i class="fas fa-plus"></i> Adicionar Novo Endereço</button>
                    <div class="new-address-form" id="newAddrForm">
                        <div class="form-row full">
                            <div class="form-field">
                                <label>CEP</label>
                                <input type="text" id="addr-cep" placeholder="00000-000" maxlength="9">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-field"><label>Rua</label><input type="text" id="addr-rua" placeholder="Rua / Av."></div>
                            <div class="form-field"><label>Número</label><input type="text" id="addr-num" placeholder="123"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-field"><label>Complemento</label><input type="text" id="addr-comp" placeholder="Apto, Bloco..."></div>
                            <div class="form-field"><label>Bairro</label><input type="text" id="addr-bairro" placeholder="Bairro"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-field"><label>Cidade</label><input type="text" id="addr-cidade" placeholder="Cidade"></div>
                            <div class="form-field"><label>Estado</label><input type="text" id="addr-estado" placeholder="SP" maxlength="2"></div>
                        </div>
                        <button class="btn-save-address" id="btnSaveAddr"><i class="fas fa-check"></i> Salvar Endereço</button>
                    </div>
                </div>

                <!-- PAYMENT -->
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-credit-card"></i> Forma de Pagamento</h2>
                    <div class="payment-option selected js-payment" data-method="pix">
                        <div class="radio"></div>
                        <i class="payment-icon fas fa-qrcode"></i>
                        <div>
                            <div class="payment-label">PIX</div>
                            <div style="font-size: 0.8rem; color: var(--text-m);">Aprovação instantânea</div>
                        </div>
                        <span class="payment-badge">${config.desconto_pix}% OFF</span>
                    </div>
                    <div class="payment-option disabled" title="Em breve">
                        <div class="radio"></div>
                        <i class="payment-icon fas fa-credit-card"></i>
                        <div>
                            <div class="payment-label">Cartão de Crédito</div>
                            <div style="font-size: 0.8rem; color: var(--text-m);">Em breve</div>
                        </div>
                    </div>
                </div>

                <!-- NOTES -->
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-comment-alt"></i> Observações</h2>
                    <textarea class="notes-field" id="orderNotes" placeholder="Ex: Sem cebola, ponto da carne..."></textarea>
                </div>
            </div>

            <!-- SIDEBAR SUMMARY -->
            <div>
                <div class="section sidebar-summary">
                    <h2 class="section-title"><i class="fas fa-receipt"></i> Total</h2>
                    <div class="summary-row"><span>Subtotal</span><span>R$ ${subtotal.toLocaleString('pt-br', {minimumFractionDigits: 2})}</span></div>
                    <div class="summary-row"><span>Taxa de Entrega</span><span>R$ ${taxa.toLocaleString('pt-br', {minimumFractionDigits: 2})}</span></div>
                    ${desconto > 0 ? `<div class="summary-row discount"><span>Desconto PIX (${config.desconto_pix}%)</span><span>- R$ ${desconto.toLocaleString('pt-br', {minimumFractionDigits: 2})}</span></div>` : ''}
                    <hr class="summary-divider">
                    <div class="summary-total"><span>Total</span><span>R$ ${total.toLocaleString('pt-br', {minimumFractionDigits: 2})}</span></div>
                    <button class="btn-confirm" id="btnConfirm" ${!selectedEnderecoId ? 'disabled' : ''}>
                        <span class="label"><i class="fas fa-lock" style="margin-right: 8px;"></i> Confirmar Pedido</span>
                        <span class="spinner"><i class="fas fa-spinner fa-spin"></i> Processando...</span>
                    </button>
                    <div class="secure-badge"><i class="fas fa-shield-alt"></i> Ambiente seguro · Pagar.me</div>
                </div>
            </div>
        </div>`;

        bindEvents();
    }

    function bindEvents() {
        // Remove item
        document.querySelectorAll('.js-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                cart.splice(parseInt(this.dataset.index), 1);
                localStorage.setItem('bellacucina_cart', JSON.stringify(cart));
                if (cart.length === 0) { window.location.href = '{{ route("cardapio.index") }}'; return; }
                render();
            });
        });

        // Select address
        document.querySelectorAll('.js-addr').forEach(card => {
            card.addEventListener('click', function() {
                selectedEnderecoId = parseInt(this.dataset.id);
                render();
            });
        });

        // New address toggle
        document.getElementById('btnNewAddr')?.addEventListener('click', () => {
            document.getElementById('newAddrForm').classList.toggle('open');
        });

        // CEP auto-fill (ViaCEP)
        document.getElementById('addr-cep')?.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch('https://viacep.com.br/ws/' + cep + '/json/')
                    .then(r => r.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('addr-rua').value = data.logradouro || '';
                            document.getElementById('addr-bairro').value = data.bairro || '';
                            document.getElementById('addr-cidade').value = data.localidade || '';
                            document.getElementById('addr-estado').value = data.uf || '';
                        }
                    }).catch(() => {});
            }
        });

        // Save address
        document.getElementById('btnSaveAddr')?.addEventListener('click', function() {
            const data = {
                cep: document.getElementById('addr-cep').value,
                logradouro: document.getElementById('addr-rua').value,
                numero: document.getElementById('addr-num').value,
                complemento: document.getElementById('addr-comp').value,
                bairro: document.getElementById('addr-bairro').value,
                cidade: document.getElementById('addr-cidade').value,
                estado: document.getElementById('addr-estado').value,
            };
            if(!data.cep || !data.logradouro || !data.numero || !data.bairro || !data.cidade || !data.estado) {
                alert('Preencha todos os campos obrigatórios'); return;
            }
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
            fetch('/enderecos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify(data),
            })
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    enderecos.push(resp.endereco);
                    selectedEnderecoId = resp.endereco.id;
                    render();
                } else { alert('Erro ao salvar endereço'); this.disabled = false; this.innerHTML = '<i class="fas fa-check"></i> Salvar'; }
            })
            .catch(() => { alert('Erro de conexão'); this.disabled = false; this.innerHTML = '<i class="fas fa-check"></i> Salvar'; });
        });

        // Confirm order
        document.getElementById('btnConfirm')?.addEventListener('click', function() {
            if (isProcessing || !selectedEnderecoId) return;
            isProcessing = true;
            this.classList.add('loading');
            this.disabled = true;

            const payload = {
                itens: cart.map(item => ({ prato_id: item.id, qtd: item.qtd, preco: item.preco })),
                endereco_id: selectedEnderecoId,
                tipo_entrega: 'entrega',
                pagamento_metodo: selectedPayment,
                observacoes: document.getElementById('orderNotes')?.value || '',
            };

            fetch('/checkout/pagar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            })
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    // Store PIX data for the payment page
                    localStorage.setItem('bellacucina_pix', JSON.stringify(resp.pix));
                    // Clear cart
                    localStorage.removeItem('bellacucina_cart');
                    // Redirect to payment page
                    window.location.href = resp.redirect;
                } else {
                    alert(resp.message || 'Erro ao processar pedido');
                    isProcessing = false;
                    this.classList.remove('loading');
                    this.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erro de conexão. Tente novamente.');
                isProcessing = false;
                this.classList.remove('loading');
                this.disabled = false;
            });
        });
    }
});
</script>

</body>
</html>