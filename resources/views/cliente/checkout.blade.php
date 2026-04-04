<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Bella Cucina</title>
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
        body.modal-open { overflow: hidden; }
        .checkout-header { background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 72px; position: sticky; top: 0; z-index: 50; }
        .checkout-header a { text-decoration: none; color: var(--text-m); display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .checkout-header a:hover { color: var(--navy); }
        .logo { font-family: var(--font-h); font-size: 1.5rem; color: var(--navy); letter-spacing: -0.5px; }
        .logo span { color: var(--text-m); font-weight: 400; font-size: 1.1rem; }
        .checkout-wrap { max-width: 1100px; margin: 0 auto; padding: 2rem; display: grid; grid-template-columns: 1fr 380px; gap: 2rem; }
        .section { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; margin-bottom: 1.5rem; }
        .section-title { font-family: var(--font-h); font-size: 1.3rem; color: var(--text); margin-bottom: 1rem; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: var(--navy); font-size: 1.1rem; }
        .cart-item { display: flex; gap: 12px; align-items: center; padding: 12px; border-radius: 14px; background: var(--surface2); margin-bottom: 10px; border: 1px solid transparent; transition: 0.2s; }
        .cart-item:hover { border-color: var(--border); }
        .cart-item img { width: 56px; height: 56px; border-radius: 12px; object-fit: cover; }
        .cart-item-info { flex: 1; }
        .cart-item-name { font-weight: 600; font-size: 0.95rem; }
        .cart-item-price { color: var(--navy); font-weight: 700; font-size: 0.9rem; margin-top: 2px; }
        .cart-item-qty { color: var(--text-m); font-size: 0.85rem; }
        .cart-item-remove { background: none; border: none; color: var(--red); cursor: pointer; padding: 8px; border-radius: 8px; transition: 0.2s; }
        .cart-item-remove:hover { background: rgba(239,68,68,0.1); }
        .address-card { border: 2px solid var(--border); border-radius: 14px; padding: 14px 16px; cursor: pointer; transition: 0.2s; margin-bottom: 10px; position: relative; }
        .address-card:hover { border-color: var(--navy-l); }
        .address-card.selected { border-color: var(--navy); background: rgba(30,58,138,0.04); }
        .address-card .check { position: absolute; top: 14px; right: 14px; width: 22px; height: 22px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .address-card.selected .check { background: var(--navy); border-color: var(--navy); color: #fff; }
        .address-label { font-weight: 700; font-size: 0.85rem; color: var(--navy); margin-bottom: 4px; }
        .address-text { font-size: 0.9rem; color: var(--text-m); line-height: 1.4; }
        .btn-add-address { background: none; border: 2px dashed var(--border); border-radius: 14px; padding: 14px; width: 100%; cursor: pointer; color: var(--navy); font-weight: 600; font-size: 0.9rem; transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-add-address:hover { border-color: var(--navy); background: rgba(30,58,138,0.03); }
        .new-address-form { display: none; background: var(--surface2); border-radius: 14px; padding: 1.2rem; margin-top: 10px; }
        .new-address-form.open { display: block; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
        .form-row.full { grid-template-columns: 1fr; }
        .form-field label { font-size: 0.75rem; font-weight: 700; color: var(--text-s); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block; }
        .form-field input, .form-field select, .notes-field { width: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; font-size: 0.9rem; font-family: var(--font-b); outline: none; transition: 0.2s; background: var(--surface); }
        .form-field input:focus, .form-field select:focus, .notes-field:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(30,58,138,0.1); }
        .btn-save-address { background: var(--navy); color: #fff; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 700; cursor: pointer; font-size: 0.9rem; transition: 0.2s; width: 100%; margin-top: 5px; }
        .btn-save-address:hover { background: var(--navy-d); }
        .payment-option { border: 2px solid var(--border); border-radius: 14px; padding: 16px; cursor: pointer; transition: 0.2s; margin-bottom: 10px; display: flex; align-items: center; gap: 14px; }
        .payment-option:hover { border-color: var(--navy-l); }
        .payment-option.selected { border-color: var(--navy); background: rgba(30,58,138,0.04); }
        .payment-option .radio { width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .payment-option.selected .radio { border-color: var(--navy); }
        .payment-option.selected .radio::after { content: ''; width: 10px; height: 10px; border-radius: 50%; background: var(--navy); }
        .payment-icon { font-size: 1.3rem; color: var(--navy); }
        .payment-label { font-weight: 600; font-size: 0.95rem; }
        .payment-badge { font-size: 0.7rem; background: var(--green-bg); color: var(--green); padding: 3px 8px; border-radius: 6px; font-weight: 700; margin-left: auto; }
        .payment-details { display: none; margin-top: 12px; padding: 16px; border: 1px solid var(--border); border-radius: 14px; background: linear-gradient(180deg, #fff 0%, #f8fbff 100%); }
        .payment-details.open { display: block; }
        .card-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .card-grid.three { grid-template-columns: 2fr 1fr 1fr; }
        .card-help { color: var(--text-m); font-size: 0.8rem; margin-top: 10px; }
        .sidebar-summary { position: sticky; top: 90px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 0.95rem; }
        .summary-row.discount { color: var(--green); }
        .summary-divider { border: none; border-top: 1px solid var(--border); margin: 10px 0; }
        .summary-total { display: flex; justify-content: space-between; font-size: 1.3rem; font-weight: 800; color: var(--navy); padding: 8px 0; }
        .btn-confirm { width: 100%; background: linear-gradient(135deg, var(--navy) 0%, var(--navy-d) 100%); color: #fff; border: none; padding: 18px; border-radius: 14px; font-weight: 800; font-size: 1.05rem; cursor: pointer; transition: all 0.3s; margin-top: 1rem; box-shadow: 0 4px 15px rgba(30,58,138,0.25); }
        .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30,58,138,0.35); }
        .btn-confirm:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }
        .btn-confirm .spinner { display: none; }
        .btn-confirm.loading .spinner { display: inline-block; }
        .btn-confirm.loading .label { display: none; }
        .empty-cart { text-align: center; padding: 4rem 2rem; }
        .empty-cart i { font-size: 3rem; color: var(--border); margin-bottom: 15px; }
        .empty-cart p { color: var(--text-m); }
        .empty-cart a { display: inline-block; margin-top: 15px; background: var(--navy); color: #fff; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: 0.2s; }
        .empty-cart a:hover { background: var(--navy-d); }
        .notes-field { resize: vertical; min-height: 60px; }
        .secure-badge { display: flex; align-items: center; justify-content: center; gap: 8px; color: var(--text-s); font-size: 0.8rem; margin-top: 12px; }
        .secure-badge i { color: var(--green); }
        .app-modal { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; padding: 20px; z-index: 999; }
        .app-modal.open { display: flex; }
        .app-modal-card { width: min(100%, 440px); background: #fff; border-radius: 24px; box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2); padding: 24px; }
        .app-modal-icon { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 14px; }
        .app-modal.error .app-modal-icon { background: #FEF2F2; color: #DC2626; }
        .app-modal.success .app-modal-icon { background: #ECFDF5; color: #059669; }
        .app-modal-title { font-family: var(--font-h); font-size: 1.4rem; margin-bottom: 8px; }
        .app-modal-text { color: var(--text-m); line-height: 1.5; margin-bottom: 18px; }
        .app-modal-actions { display: flex; justify-content: flex-end; }
        .app-modal-btn { min-width: 110px; border: none; border-radius: 12px; padding: 12px 18px; background: var(--navy); color: #fff; font-weight: 700; cursor: pointer; }
        @media(max-width: 900px) { .checkout-wrap { grid-template-columns: 1fr; } }
        @media(max-width: 640px) { .form-row, .card-grid, .card-grid.three { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="checkout-header">
    <a href="{{ route('cardapio.index') }}">
        <i class="fas fa-arrow-left"></i> Voltar ao Cardapio
    </a>
    <div class="logo">Bella<span>Cucina</span></div>
    <div style="width: 140px;"></div>
</div>

<div id="checkoutContent"
     data-enderecos='@json($enderecos ?? [])'
     data-config='@json($config ?? ["taxa_entrega" => 5, "pedido_minimo" => 30, "desconto_pix" => 5])'
     data-routes='@json(["cardapio" => route("cardapio.index"), "enderecos" => route("cliente.enderecos.store"), "checkoutPagar" => route("cliente.checkout.pagar")])'></div>

<div id="appModal" class="app-modal error" aria-hidden="true">
    <div class="app-modal-card" role="dialog" aria-modal="true" aria-labelledby="appModalTitle">
        <div class="app-modal-icon"><i id="appModalIcon" class="fas fa-circle-exclamation"></i></div>
        <div id="appModalTitle" class="app-modal-title">Algo deu errado</div>
        <div id="appModalText" class="app-modal-text">Nao foi possivel concluir a operacao.</div>
        <div class="app-modal-actions"><button id="appModalButton" class="app-modal-btn" type="button">OK</button></div>
    </div>
</div>

@if(!empty(($config ?? [])['pagarme_public_key']))
<script src="https://checkout.pagar.me/v1/tokenizecard.js" data-pagarmecheckout-app-id="{{ $config['pagarme_public_key'] }}"></script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cart = JSON.parse(localStorage.getItem('bellacucina_cart') || '[]');
    const container = document.getElementById('checkoutContent');
    const config = JSON.parse(container.dataset.config || '{}');
    const routes = JSON.parse(container.dataset.routes || '{}');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const modal = document.getElementById('appModal');
    const modalTitle = document.getElementById('appModalTitle');
    const modalText = document.getElementById('appModalText');
    const modalIcon = document.getElementById('appModalIcon');
    const modalButton = document.getElementById('appModalButton');

    let enderecos = JSON.parse(container.dataset.enderecos || '[]').map(normalizeEndereco);
    let selectedEnderecoId = enderecos.length > 0 ? (enderecos.find(e => e.principal)?.id || enderecos[0].id) : null;
    let selectedPayment = 'pix';
    let isProcessing = false;
    const pagarmeReady = typeof window.PagarmeCheckout !== 'undefined';

    if (cart.length === 0) {
        container.innerHTML = '<div class="empty-cart"><i class="fas fa-shopping-basket"></i><p style="font-size: 1.2rem; font-weight: 600; margin-bottom: 5px;">Seu carrinho esta vazio</p><p>Adicione pratos deliciosos do nosso cardapio.</p><a href="' + routes.cardapio + '">Ver Cardapio</a></div>';
        return;
    }

    modalButton?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (event) { if (event.target === modal) closeModal(); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && modal.classList.contains('open')) closeModal(); });

    render();

    function normalizeEndereco(endereco) {
        return { ...endereco, apelido: endereco.apelido || endereco.tipo || 'Casa', estado: String(endereco.estado || endereco.uf || '').toUpperCase() };
    }

    function showModal(type, title, message) {
        modal.classList.remove('error', 'success');
        modal.classList.add(type);
        modalTitle.textContent = title;
        modalText.textContent = message;
        modalIcon.className = type === 'success' ? 'fas fa-circle-check' : 'fas fa-circle-exclamation';
        modal.classList.add('open');
        document.body.classList.add('modal-open');
    }

    function closeModal() {
        modal.classList.remove('open');
        document.body.classList.remove('modal-open');
    }

    function money(value) {
        return Number(value || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function onlyDigits(value) {
        return String(value || '').replace(/\D/g, '');
    }

    function formatGatewayError(error) {
        if (!error) return '';
        if (typeof error === 'string') return error;
        if (Array.isArray(error.errors) && error.errors.length) return error.errors[0].message || 'Erro ao gerar token do cartao.';
        return error.message || '';
    }

    function render() {
        const subtotal = cart.reduce((sum, item) => sum + (Number(item.preco) * Number(item.qtd)), 0);
        const taxa = Number(config.taxa_entrega || 0);
        const desconto = selectedPayment === 'pix' ? Math.round(subtotal * (Number(config.desconto_pix || 0) / 100) * 100) / 100 : 0;
        const total = Math.max(0, subtotal + taxa - desconto);

        container.innerHTML = `
        <div class="checkout-wrap">
            <div>
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-shopping-bag"></i> Resumo do Pedido</h2>
                    ${cart.map((item, i) => `
                        <div class="cart-item">
                            <img src="/storage/${item.imagem}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100&auto=format&fit=crop'">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.nome}</div>
                                <div class="cart-item-price">R$ ${money(item.preco * item.qtd)}</div>
                                <div class="cart-item-qty">${item.qtd}x R$ ${money(item.preco)}</div>
                            </div>
                            <button class="cart-item-remove js-remove" data-index="${i}" type="button"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    `).join('')}
                </div>

                <div class="section">
                    <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Endereco de Entrega</h2>
                    ${enderecos.map(e => `
                        <div class="address-card js-addr ${Number(e.id) === Number(selectedEnderecoId) ? 'selected' : ''}" data-id="${e.id}">
                            <div class="address-label">${e.apelido}</div>
                            <div class="address-text">${e.logradouro}, ${e.numero}${e.complemento ? ' - ' + e.complemento : ''}<br>${e.bairro} - ${e.cidade}/${e.estado}</div>
                            <div class="check"><i class="fas fa-check" style="font-size: 10px;"></i></div>
                        </div>
                    `).join('')}
                    <button class="btn-add-address" id="btnNewAddr" type="button"><i class="fas fa-plus"></i> Adicionar Novo Endereco</button>
                    <div class="new-address-form" id="newAddrForm">
                        <div class="form-row full"><div class="form-field"><label>CEP</label><input type="text" id="addr-cep" placeholder="00000-000" maxlength="9"></div></div>
                        <div class="form-row"><div class="form-field"><label>Rua</label><input type="text" id="addr-rua" placeholder="Rua / Av."></div><div class="form-field"><label>Numero</label><input type="text" id="addr-num" placeholder="123"></div></div>
                        <div class="form-row"><div class="form-field"><label>Complemento</label><input type="text" id="addr-comp" placeholder="Apto, Bloco..."></div><div class="form-field"><label>Bairro</label><input type="text" id="addr-bairro" placeholder="Bairro"></div></div>
                        <div class="form-row"><div class="form-field"><label>Cidade</label><input type="text" id="addr-cidade" placeholder="Cidade"></div><div class="form-field"><label>Estado</label><input type="text" id="addr-estado" placeholder="SP" maxlength="2"></div></div>
                        <button class="btn-save-address" id="btnSaveAddr" type="button"><i class="fas fa-check"></i> Salvar Endereco</button>
                    </div>
                </div>

                <div class="section">
                    <h2 class="section-title"><i class="fas fa-credit-card"></i> Forma de Pagamento</h2>
                    <div class="payment-option ${selectedPayment === 'pix' ? 'selected' : ''} js-payment" data-method="pix">
                        <div class="radio"></div><i class="payment-icon fas fa-qrcode"></i><div><div class="payment-label">PIX</div><div style="font-size: 0.8rem; color: var(--text-m);">Aprovacao instantanea</div></div><span class="payment-badge">${config.desconto_pix}% OFF</span>
                    </div>
                    <div class="payment-option ${selectedPayment === 'cartao_credito' ? 'selected' : ''} js-payment" data-method="cartao_credito">
                        <div class="radio"></div><i class="payment-icon fas fa-credit-card"></i><div><div class="payment-label">Cartao de Credito</div><div style="font-size: 0.8rem; color: var(--text-m);">${pagarmeReady ? 'Pagamento integrado ao gateway' : 'Configure a chave publica do Pagar.me'}</div></div>
                    </div>
                    <form id="cardTokenForm" data-pagarmecheckout-form>
                        <div class="payment-details ${selectedPayment === 'cartao_credito' ? 'open' : ''}" id="creditCardFields">
                            <div class="card-grid"><div class="form-field"><label>Nome no cartao</label><input type="text" id="card-holder" data-pagarmecheckout-element="holder_name" autocomplete="cc-name" placeholder="Como esta no cartao"></div><div class="form-field"><label>Parcelas</label><select id="card-installments">${Array.from({ length: 12 }, (_, i) => `<option value="${i + 1}">${i + 1}x</option>`).join('')}</select></div></div>
                            <div class="form-row full"><div class="form-field"><label>Numero do cartao</label><input type="text" id="card-number" data-pagarmecheckout-element="number" autocomplete="cc-number" placeholder="0000 0000 0000 0000" inputmode="numeric"></div></div>
                            <div class="card-grid three"><div class="form-field"><label>Mes</label><input type="text" id="card-exp-month" data-pagarmecheckout-element="exp_month" placeholder="MM" maxlength="2" inputmode="numeric"></div><div class="form-field"><label>Ano</label><input type="text" id="card-exp-year" data-pagarmecheckout-element="exp_year" placeholder="AA" maxlength="4" inputmode="numeric"></div><div class="form-field"><label>CVV</label><input type="text" id="card-cvv" data-pagarmecheckout-element="cvv" autocomplete="cc-csc" placeholder="123" maxlength="4" inputmode="numeric"></div></div>
                            <div class="card-help">Os dados do cartao sao tokenizados pelo Pagar.me e nao passam abertos pelo servidor.</div>
                        </div>
                    </form>
                </div>

                <div class="section"><h2 class="section-title"><i class="fas fa-comment-alt"></i> Observacoes</h2><textarea class="notes-field" id="orderNotes" placeholder="Ex: sem cebola, ponto da carne..."></textarea></div>
            </div>
            <div><div class="section sidebar-summary"><h2 class="section-title"><i class="fas fa-receipt"></i> Total</h2><div class="summary-row"><span>Subtotal</span><span>R$ ${money(subtotal)}</span></div><div class="summary-row"><span>Taxa de Entrega</span><span>R$ ${money(taxa)}</span></div>${desconto > 0 ? `<div class="summary-row discount"><span>Desconto PIX (${config.desconto_pix}%)</span><span>- R$ ${money(desconto)}</span></div>` : ''}<hr class="summary-divider"><div class="summary-total"><span>Total</span><span>R$ ${money(total)}</span></div><button class="btn-confirm" id="btnConfirm" type="button" ${!selectedEnderecoId ? 'disabled' : ''}><span class="label"><i class="fas fa-lock" style="margin-right: 8px;"></i> Confirmar Pedido</span><span class="spinner"><i class="fas fa-spinner fa-spin"></i> Processando...</span></button><div class="secure-badge"><i class="fas fa-shield-alt"></i> Ambiente seguro · Pagar.me</div></div></div>
        </div>`;

        bindEvents();
    }

    function bindEvents() {
        document.querySelectorAll('.js-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                cart.splice(Number(this.dataset.index), 1);
                localStorage.setItem('bellacucina_cart', JSON.stringify(cart));
                if (cart.length === 0) { window.location.href = routes.cardapio; return; }
                render();
            });
        });

        document.querySelectorAll('.js-addr').forEach(card => {
            card.addEventListener('click', function () {
                selectedEnderecoId = Number(this.dataset.id);
                render();
            });
        });

        document.querySelectorAll('.js-payment').forEach(option => {
            option.addEventListener('click', function () {
                selectedPayment = this.dataset.method;
                render();
            });
        });

        document.getElementById('btnNewAddr')?.addEventListener('click', function () {
            document.getElementById('newAddrForm').classList.toggle('open');
        });

        document.getElementById('addr-cep')?.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').replace(/^(\d{5})(\d)/, '$1-$2').slice(0, 9);
        });

        document.getElementById('addr-cep')?.addEventListener('blur', function () {
            const cep = onlyDigits(this.value);
            if (cep.length !== 8) return;
            fetch('https://viacep.com.br/ws/' + cep + '/json/').then(r => r.json()).then(data => {
                if (!data.erro) {
                    document.getElementById('addr-rua').value = data.logradouro || '';
                    document.getElementById('addr-bairro').value = data.bairro || '';
                    document.getElementById('addr-cidade').value = data.localidade || '';
                    document.getElementById('addr-estado').value = data.uf || '';
                }
            }).catch(() => {});
        });

        document.getElementById('btnSaveAddr')?.addEventListener('click', async function () {
            const data = {
                cep: document.getElementById('addr-cep').value,
                logradouro: document.getElementById('addr-rua').value.trim(),
                numero: document.getElementById('addr-num').value.trim(),
                complemento: document.getElementById('addr-comp').value.trim(),
                bairro: document.getElementById('addr-bairro').value.trim(),
                cidade: document.getElementById('addr-cidade').value.trim(),
                estado: document.getElementById('addr-estado').value.trim().toUpperCase(),
            };

            if (!data.cep || !data.logradouro || !data.numero || !data.bairro || !data.cidade || !data.estado) {
                showModal('error', 'Campos obrigatorios', 'Preencha CEP, rua, numero, bairro, cidade e estado antes de salvar o endereco.');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

            try {
                const response = await fetch(routes.enderecos, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify(data) });
                const resp = await response.json();
                if (!response.ok || !resp.success) throw new Error(resp.message || 'Nao foi possivel salvar o endereco.');
                enderecos.push(normalizeEndereco(resp.endereco));
                selectedEnderecoId = Number(resp.endereco.id);
                showModal('success', 'Endereco salvo', resp.message || 'Endereco cadastrado com sucesso.');
                render();
            } catch (error) {
                showModal('error', 'Falha ao salvar', error.message || 'Erro de conexao ao salvar o endereco.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-check"></i> Salvar Endereco';
            }
        });

        setupCardMasks();

        document.getElementById('btnConfirm')?.addEventListener('click', async function () {
            if (isProcessing || !selectedEnderecoId) return;
            isProcessing = true;
            this.classList.add('loading');
            this.disabled = true;

            try {
                const payload = { itens: cart.map(item => ({ prato_id: item.id, qtd: item.qtd, preco: item.preco })), endereco_id: selectedEnderecoId, tipo_entrega: 'entrega', pagamento_metodo: selectedPayment, observacoes: document.getElementById('orderNotes')?.value || '' };
                if (selectedPayment === 'cartao_credito') {
                    const cardData = await createCardToken();
                    payload.card_token = cardData.token;
                    payload.card_last_four = cardData.lastFour;
                    payload.card_brand = cardData.brand;
                    payload.parcelas = cardData.installments;
                }

                const response = await fetch(routes.checkoutPagar, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify(payload) });
                const resp = await response.json();
                if (!response.ok || !resp.success) throw new Error(resp.message || 'Erro ao processar pedido.');
                if (selectedPayment === 'pix' && resp.pix) localStorage.setItem('bellacucina_pix', JSON.stringify(resp.pix));
                localStorage.removeItem('bellacucina_cart');
                window.location.href = resp.redirect;
            } catch (error) {
                showModal('error', 'Pagamento nao concluido', error.message || 'Erro de conexao. Tente novamente.');
                isProcessing = false;
                this.classList.remove('loading');
                this.disabled = false;
            }
        });
    }

    function setupCardMasks() {
        document.getElementById('card-number')?.addEventListener('input', function () { this.value = onlyDigits(this.value).slice(0, 16).replace(/(\d{4})(?=\d)/g, '$1 ').trim(); });
        document.getElementById('card-exp-month')?.addEventListener('input', function () { this.value = onlyDigits(this.value).slice(0, 2); });
        document.getElementById('card-exp-year')?.addEventListener('input', function () { this.value = onlyDigits(this.value).slice(0, 4); });
        document.getElementById('card-cvv')?.addEventListener('input', function () { this.value = onlyDigits(this.value).slice(0, 4); });
    }

    function validateCardFields() {
        if (!pagarmeReady) throw new Error('PAGARME_PUBLIC_KEY nao configurada. Adicione a chave publica no .env e libere o dominio no dashboard do Pagar.me.');
        const holder = document.getElementById('card-holder')?.value.trim();
        const number = onlyDigits(document.getElementById('card-number')?.value);
        const expMonth = onlyDigits(document.getElementById('card-exp-month')?.value);
        const expYear = onlyDigits(document.getElementById('card-exp-year')?.value);
        const cvv = onlyDigits(document.getElementById('card-cvv')?.value);
        if (!holder || number.length < 13 || expMonth.length < 2 || expYear.length < 2 || cvv.length < 3) throw new Error('Preencha corretamente os dados do cartao para continuar.');
    }

    async function createCardToken() {
        validateCardFields();
        return new Promise((resolve, reject) => {
            const form = document.getElementById('cardTokenForm');
            const hidden = form.querySelector('input[name="pagarmetoken"]');
            if (hidden) hidden.remove();

            window.PagarmeCheckout.init(function (data) {
                const tokenField = form.querySelector('input[name="pagarmetoken"]');
                const token = data?.pagarmetoken || data?.token || tokenField?.value;
                if (!token) { reject(new Error('O gateway nao retornou um token para o cartao.')); return false; }
                resolve({ token, brand: null, lastFour: onlyDigits(document.getElementById('card-number')?.value).slice(-4), installments: Number(document.getElementById('card-installments')?.value || 1) });
                return false;
            }, function (error) {
                reject(new Error(formatGatewayError(error) || 'Nao foi possivel tokenizar o cartao.'));
            });

            if (form.requestSubmit) form.requestSubmit(); else form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        });
    }
});
</script>
</body>
</html>
