<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio Digital — Mesa {{ $mesa->numero }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    @include('layouts.partials.restaurant-theme')

    <style>
        :root {
            --amber:   var(--color-secondary);
            --amber-d: var(--color-secondary-dark);
            --brand:   var(--color-secondary);
            --dark:    #FFFFFF;
            --dark2:   #F8FAFC;
            --surface: #FFFFFF;
            --surface2:#FFFFFF;
            --text:    var(--text-main);
            --text-m:  var(--text-muted);
            --text-s:  #94A3B8;
            --red:     #EF4444;
            --border:  var(--color-secondary-border);
            --radius:  14px;
            --font-h:  'Playfair Display', serif;
            --font-b:  'DM Sans', sans-serif;
        }


        *{box-sizing:border-box;margin:0;padding:0}
        body{background:var(--dark);color:var(--text);font-family:var(--font-b);min-height:100vh;overflow-x:hidden; scroll-behavior: smooth;}

        /* HEADER */
        header{
            position:sticky;top:0;z-index:100;
            background:rgba(255,255,255,0.9);
            backdrop-filter:blur(12px);
            border-bottom:1px solid var(--border);
            padding:0 2rem;
            display:flex;align-items:center;justify-content:space-between;
            height:72px;
            transition: all 0.3s ease;
        }
        @media(max-width: 640px) {
            header { padding: 0 1rem; height: 64px; }
            .mesa-badge { padding: 6px 12px; }
            .mesa-label { display: none; }
            .btn-sacola { padding: 8px 12px; font-size: 12px; }
        }

        .logo{
            display:flex; align-items:center; justify-content:center;
            min-width:96px; min-height:42px;
            text-decoration:none;
        }
        .logo img{
            max-height:44px; max-width:180px; object-fit:contain;
        }
        
        .mesa-badge {
            display: flex; align-items: center; gap: 10px;
            background: #F1F5F9; border: 1px solid #E2E8F0;
            padding: 8px 16px; border-radius: 50px;
        }
        .mesa-label { font-size: 10px; text-transform: uppercase; font-weight: 800; color: var(--text-s); letter-spacing: 0.05em; }
        .mesa-number { font-size: 14px; font-weight: 800; color: var(--brand); font-family: var(--font-h); }

        .header-actions {
            display: flex; gap: 12px; align-items: center;
        }

        .btn-comanda {
            display: flex; align-items: center; gap: 8px;
            background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
            padding: 10px 18px; border-radius: 50px; font-weight: 700; font-size: 13px;
            text-decoration: none; transition: all 0.3s ease;
        }
        .btn-comanda:hover { background: #e2e8f0; color: var(--brand); }

        .btn-sacola {
            display: flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--brand) 0%, var(--color-secondary-dark) 100%);
            color: #fff; border: none; padding: 10px 20px;
            border-radius: 50px; font-weight: 700; font-size: 13px;
            cursor: pointer; transition: all 0.3s ease; position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-sacola:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }


        @media(max-width: 640px) {
            .btn-comanda, .btn-sacola { padding: 8px 14px; font-size: 11px; gap: 6px; }
            .btn-text { display: none; }
        }

        .cart-count {
            position: absolute; top: -6px; right: -6px;
            background: var(--red); color: white; font-size: 10px;
            width: 18px; height: 18px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #FFFFFF; font-weight: 800;
        }


        /* HERO */
        .hero{ padding:4rem 2rem 2rem; background:linear-gradient(180deg, color-mix(in srgb, var(--color-secondary) 8%, white 92%) 0%, transparent 100%); border-bottom:1px solid var(--border); text-align: center;}
        .hero h1{font-family:var(--font-h);font-size:clamp(2rem,5vw,3.5rem);line-height:1.1;color:var(--text); margin-bottom: 1rem;}
        .hero h1 em{color:var(--amber);font-style:normal}
        
        /* MAIN LAYOUT */
        .main-wrap{max-width:1400px;margin:0 auto;padding:2.5rem 2rem 4rem;display:grid;grid-template-columns:220px 1fr;gap:2.5rem; transition: 0.3s;}

        .sidebar{position:sticky;top:100px;height:fit-content}
        .sidebar-title{font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-s);margin-bottom:1rem}
        .cat-item{
            display:flex;align-items:center;gap:.75rem; padding:.75rem 1rem;border-radius:12px;
            cursor:pointer;transition:.2s;margin-bottom:.4rem;
            font-size:.95rem;color:var(--text-m);font-weight:500; border:1px solid transparent;text-decoration:none;
        }
        .cat-item:hover, .cat-item.active{background:var(--surface);border-color:var(--border);color:var(--brand); box-shadow: 0 2px 8px rgba(30, 58, 138, 0.05);}

        /* GRID E CARDS */
        .destaques-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;margin-bottom:3rem;}
        .section-title{font-family:var(--font-h);font-size:1.5rem;color:var(--text);margin-bottom:1.5rem;display:flex;align-items:baseline;gap:.75rem;}
        .section-title::after{content:'';flex:1;height:1px;background:linear-gradient(to right, var(--border), transparent);}

        .card{background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden;transition:.3s;cursor:pointer;display:flex;flex-direction:column;}
        .card:hover{transform:translateY(-5px); border-color:var(--brand); box-shadow: 0 10px 20px rgba(30, 58, 138, 0.08);}
        .card-img{height:180px;overflow:hidden;position:relative;background:var(--dark2);}
        .card-img img{width:100%;height:100%;object-fit:cover;transition:.4s}
        .card:hover .card-img img { transform: scale(1.05); }
        .card-body{padding:1.25rem;flex:1;display:flex;flex-direction:column;gap:.5rem}
        .card-nome{font-family:var(--font-h);font-size:1.1rem;color:var(--text); font-weight: 600;}
        .card-desc{
            font-size:0.75rem; color:var(--text-s); line-height:1.4;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
        }
        .card-preco{font-size:1.2rem;font-weight:700;color:var(--brand)}
        .btn-add{background:var(--brand);color:#fff;border:none;border-radius:12px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.2rem; transition: 0.2s;}
        @keyframes fadeOut { to { opacity: 0; transform: translateY(-10px); } }

        /* PREMIUM SYSTEM MODAL */
        #premium-modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px); z-index: 2000;
            display: none; align-items: center; justify-content: center; padding: 20px;
            animation: fadeIn 0.3s ease;
        }
        #premium-modal-overlay.open { display: flex; }
        .p-modal {
            background: #fff; width: 100%; max-width: 400px;
            border-radius: 28px; overflow: hidden; border: 1px solid var(--border-soft);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .p-modal-header { padding: 2.5rem 2rem 1.5rem; text-align: center; }
        .p-modal-icon { 
            width: 72px; height: 72px; margin: 0 auto 1.5rem; border-radius: 22px;
            display: flex; align-items: center; justify-content: center; font-size: 1.8rem;
        }
        .p-modal-icon.error { background: #FEF2F2; color: #EF4444; }
        .p-modal-icon.warning { background: #FFF7ED; color: #F97316; }
        .p-modal-body { padding: 0 2rem 2rem; text-align: center; }
        .p-modal-title { font-family: var(--font-h); font-size: 1.5rem; color: #1e293b; margin-bottom: 0.75rem; font-weight: 700; }
        .p-modal-text { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
        .p-modal-footer { padding: 0 2rem 2rem; }
        .btn-p-modal {
            width: 100%; background: var(--color-secondary); color: #fff; border: none; padding: 1.1rem;
            border-radius: 16px; font-weight: 800; font-size: 1rem; cursor: pointer; transition: 0.3s;
        }
        .btn-p-modal:hover { filter: brightness(1.1); transform: translateY(-2px); }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* BARRA FLUTUANTE */




        .floating-cart {
            position: fixed; bottom: -100px; left: 50%; transform: translateX(-50%);
            width: 90%; max-width: 480px; height: 60px;
            background: linear-gradient(135deg, var(--brand) 0%, var(--color-secondary-dark) 100%);
            backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px; z-index: 250; 
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
            cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
        }

        .floating-cart.active { bottom: 30px; }
        .floating-cart:hover { transform: translateX(-50%) translateY(-3px); }
        .floating-cart-inner { display: flex; align-items: center; justify-content: space-between; height: 100%; padding: 0 1.2rem; color: #FFFFFF; width: 100%; }
        
        .float-qty-box { background: rgba(255,255,255,0.15); padding: 6px 14px; border-radius: 12px; font-weight: 800; font-size: 1.1rem; }
        .float-view-label { font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }

        /* MODAL */
        .overlay{position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:200;display:none;align-items:center;justify-content:center; padding: 20px; transition: 0.3s;}
        .overlay.open{display:flex;}
        .modal{background:var(--surface);border-radius:24px;width:100%;max-width:500px;overflow:hidden; border: 1px solid var(--border); animation: modalIn 0.3s ease-out;}
        @keyframes modalIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }

        /* CART DRAWER */
        .cart-overlay { display: none; }
        .cart-overlay.open { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 290; }
        .cart-drawer{position:fixed;right:0;top:0;bottom:0;width:400px;max-width: 100%; background:var(--surface); z-index:300; transform:translateX(100%); transition:.4s cubic-bezier(0.4, 0, 0.2, 1); display:flex; flex-direction:column; border-left: 1px solid var(--border);}
        .cart-drawer.open{transform:translateX(0); box-shadow: -10px 0 40px rgba(0,0,0,0.5);}

        .cart-item { display: flex; gap: 12px; margin-bottom: 12px; align-items: center; background: var(--surface); padding: 10px; border-radius: 14px; border: 1px solid var(--border); }
        .cart-item-img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; background: var(--dark2); }
        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-name { font-size: 0.85rem; font-weight: 600; }
        .cart-item-price { color: var(--brand); font-weight: 700; font-size: 0.9rem; }

        .qty-controls { display: flex; align-items: center; background: var(--dark2); border-radius: 10px; border: 1px solid var(--border); overflow: hidden; }
        .qty-btn { background: none; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.1rem; color: var(--brand); }
        .qty-num { min-width: 26px; text-align: center; font-weight: 800; font-size: 0.9rem; }

        @media(max-width:768px){ 
            .main-wrap{grid-template-columns:1fr; padding: 1.5rem 1rem 6rem;} 
            .sidebar{display:none;} 

            .mobile-cats {
                display: flex; overflow-x: auto; gap: 8px; padding: 10px 1rem;
                background: var(--surface); border-bottom: 1px solid var(--border);
                position: sticky; top: 64px; z-index: 90; scrollbar-width: none;
            }
            .mobile-cats::-webkit-scrollbar { display: none; }
            .mobile-cat-item {
                white-space: nowrap; padding: 8px 16px; border-radius: 50px;
                background: var(--dark2); border: 1px solid var(--border);
                font-size: 13px; font-weight: 600; color: var(--text-m);
            }
            .mobile-cat-item.active { background: var(--brand); color: #fff; border-color: var(--brand); }
        }
        @media(min-width: 769px) { .mobile-cats { display: none; } }

    </style>
</head>
<body>

<header>
    <div class="logo">
        <div class="bg-slate-100 p-1 rounded-xl mr-4">
            @if($restaurantConfig->logo_url)
                <img src="{{ $restaurantConfig->logo_url }}" alt="Logo" style="max-height: 48px; width: auto;">
            @else
                <span style="font-family:var(--font-h); font-weight:900; color:var(--brand); font-style: italic;">{{ config('app.name') }}</span>
            @endif

        </div>
        <div class="mesa-badge">
            <span class="mesa-label">Você está na</span>
            <span class="mesa-number">Mesa {{ $mesa->numero }}</span>
        </div>
    </div>
    
    <div class="header-actions">
        <a href="{{ route('mesa.comanda', ['hash' => $mesa->token_hash]) }}" class="btn-comanda">
            <i class="fas fa-file-invoice-dollar"></i>
            <span class="btn-text">Comanda</span>
        </a>
        <button class="btn-sacola" id="btnToggleCartTop">

            <i class="fas fa-shopping-bag"></i>
            <span class="btn-text">Sacola</span>
            <div id="cartBadge" class="cart-count">0</div>
        </button>
    </div>

</header>

<div class="mobile-cats">
    <a href="#" class="mobile-cat-item active js-cat-filter" data-id="todas">🍽️ Todas</a>
    @foreach($categorias as $cat)
        <a href="#" class="mobile-cat-item js-cat-filter" data-id="{{ $cat->id }}">
            {{ $cat->icone ?? '🍴' }} {{ $cat->nome }}
        </a>
    @endforeach
</div>


<x-restaurant-hero :config="$restaurantConfig" />

<div class="main-wrap">
    <aside class="sidebar">
        <p class="sidebar-title">Categorias</p>
        <a href="#" class="cat-item active js-cat-filter" data-id="todas">🍽️ Todas</a>
        @foreach($categorias as $cat)
            <a href="#" class="cat-item js-cat-filter" data-id="{{ $cat->id }}">
                {{ $cat->icone ?? '🍴' }} {{ $cat->nome }}
            </a>
        @endforeach
    </aside>

    <main>
        @foreach($categorias as $cat)
            <div class="categoria-section" id="secao-{{ $cat->id }}">
                <p class="section-title">{{ $cat->nome }}</p>
                <div class="destaques-grid">
                    @foreach($cat->pratos as $p)
                        <div class="card js-card-prato"
                             data-prato="{{ json_encode($p) }}"
                             data-imagem-url="{{ asset('storage/' . $p->imagem) }}"
                             data-ingredientes="{{ json_encode($p->ingredientes->map(fn($i) => ['nome' => $i->nome])) }}">
                            <div class="card-img">
                                <img src="{{ asset('storage/' . $p->imagem) }}" alt="{{ $p->nome }}" class="js-dish-img">
                            </div>
                            <div class="card-body">
                                <div class="card-nome">{{ $p->nome }}</div>
                                <div class="card-desc">{{ $p->descricao }}</div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-top: auto;">
                                    <div class="card-preco">R$ {{ number_format($p->preco, 2, ',', '.') }}</div>
                                    <button class="btn-add js-btn-add-prato"
                                            data-prato="{{ json_encode($p) }}"
                                            data-imagem-url="{{ asset('storage/' . $p->imagem) }}">+</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </main>
</div>

<div class="cart-overlay" id="cartOverlay"></div>

<div id="floatingCart" class="floating-cart">
    <div class="floating-cart-inner">
        <div style="display:flex; align-items:center; gap:20px; width:100%;">
            <span id="floatQty" class="float-qty-box">0</span>
            <span class="float-view-label">VER MEU PEDIDO</span>
            <div id="floatTotal" style="font-weight:900; font-size:1.2rem; margin-left:auto;">R$ 0,00</div>
        </div>
    </div>
</div>

<div class="cart-drawer" id="cartDrawer">
    <div style="padding:25px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items: center; background: var(--surface);">
        <h3 style="font-family: var(--font-h); font-size: 1.4rem;">Seu Pedido</h3>
        <button id="btnCloseCart" style="background:var(--dark2); border:none; color:var(--text-m); cursor:pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-times"></i></button>
    </div>
    
    <div id="cartItems" style="flex:1; overflow-y:auto; padding:20px;"></div>
    
    <div style="padding:25px; border-top:1px solid var(--border); background: var(--surface2);">
        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <span style="color: var(--text-m);">Subtotal</span>
            <span id="cartTotalFinal" style="font-weight:bold; color:var(--brand); font-size:1.4rem;">R$ 0,00</span>
        </div>
        <button id="btnCheckoutCart" style="width:100%; background:var(--brand); border:none; padding:20px; border-radius:16px; font-weight:800; cursor:pointer; color: #fff; font-size: 1rem; transition: 0.3s; text-transform:uppercase; letter-spacing:1px;">
            Finalizar Pedido
        </button>
    </div>
</div>

<div class="overlay" id="overlay">
    <div class="modal" style="max-height:90vh; overflow-y:auto;">
        <img id="m-img" src="" style="width:100%; height:220px; object-fit:cover;">
        <div style="padding:30px;">
            <h2 id="m-nome" style="font-family: var(--font-h); font-size: 1.8rem;"></h2>
            <p id="m-desc" style="color:var(--text-m); margin-top:8px; font-size: 0.95rem; line-height: 1.6;"></p>

            <div style="margin-top:30px; display:flex; justify-content:space-between; align-items:center; gap: 20px;">
                <div style="display:flex; gap:15px; align-items:center; background:var(--dark2); padding:10px 20px; border-radius:15px; border: 1px solid var(--border);">
                    <button id="m-btn-minus" style="background:none; border:none; color:var(--brand); font-size:24px; cursor:pointer;">-</button>
                    <span id="m-qtd" style="font-weight:bold; min-width: 20px; text-align: center;">1</span>
                    <button id="m-btn-plus" style="background:none; border:none; color:var(--brand); font-size:24px; cursor:pointer;">+</button>
                </div>
                <button id="m-btn-confirm" style="background:var(--brand); border:none; padding:18px; border-radius:15px; font-weight:bold; flex:1; cursor:pointer; color: #fff; font-size: 1rem;">
                    Adicionar • <span id="m-total"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let cart = JSON.parse(localStorage.getItem('mesa_cart_{{ $mesa->id }}') || '[]');
        let currentPrato = null;
        let currentQtd = 1;

        const els = {
            cartItems:      document.getElementById('cartItems'),
            cartBadge:      document.getElementById('cartBadge'),
            floatQty:       document.getElementById('floatQty'),
            floatTotal:     document.getElementById('floatTotal'),
            cartTotalFinal: document.getElementById('cartTotalFinal'),
            floatingCart:   document.getElementById('floatingCart'),
            cartDrawer:     document.getElementById('cartDrawer'),
            cartOverlay:    document.getElementById('cartOverlay'),
            overlay:        document.getElementById('overlay'),
            mImg:           document.getElementById('m-img'),
            mNome:          document.getElementById('m-nome'),
            mDesc:          document.getElementById('m-desc'),
            mQtd:           document.getElementById('m-qtd'),
            mTotal:         document.getElementById('m-total')
        };

        function renderCart() {
            els.cartItems.innerHTML = '';
            let total = 0, totalQtd = 0;

            if (cart.length === 0) {
                els.cartItems.innerHTML = '<div style="text-align:center; padding:60px; color:var(--text-s);">Sua sacola está vazia</div>';
                els.floatingCart?.classList.remove('active');
            } else {
                cart.forEach((item, i) => {
                    total += item.preco * item.qtd;
                    totalQtd += item.qtd;
                    els.cartItems.innerHTML += `
                        <div class="cart-item">
                            <img src="${item.imageUrl}" class="cart-item-img">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.nome}</div>
                                <div class="cart-item-price">R$ ${(item.preco * item.qtd).toFixed(2).replace('.', ',')}</div>
                            </div>
                            <div class="qty-controls">
                                <button onclick="changeQty(${i}, -1)" class="qty-btn">${item.qtd === 1 ? '<i class="fas fa-trash text-xs text-red-500"></i>' : '-'}</button>
                                <span class="qty-num">${item.qtd}</span>
                                <button onclick="changeQty(${i}, 1)" class="qty-btn">+</button>
                            </div>
                        </div>`;
                });
                els.floatingCart?.classList.add('active');
            }

            const totalStr = 'R$ ' + total.toFixed(2).replace('.', ',');
            els.cartBadge.innerText = totalQtd;
            els.floatQty.innerText = totalQtd;
            els.floatTotal.innerText = totalStr;
            els.cartTotalFinal.innerText = totalStr;
            localStorage.setItem('mesa_cart_{{ $mesa->id }}', JSON.stringify(cart));
        }

        window.changeQty = (idx, d) => {
            cart[idx].qtd += d;
            if (cart[idx].qtd <= 0) cart.splice(idx, 1);
            renderCart();
        };

        function addToCart(p, q, img) {
            const idx = cart.findIndex(it => it.id === p.id);
            if (idx > -1) cart[idx].qtd += q;
            else cart.push({ id: p.id, nome: p.nome, preco: parseFloat(p.preco), imageUrl: img || '/storage/'+p.imagem, qtd: q });
            renderCart();
        }

        // Toggles
        const toggleCart = () => { els.cartDrawer.classList.toggle('open'); els.cartOverlay.classList.toggle('open'); };
        document.getElementById('btnToggleCartTop').onclick = toggleCart;
        document.getElementById('btnCloseCart').onclick = toggleCart;
        els.cartOverlay.onclick = toggleCart;
        els.floatingCart.onclick = toggleCart;

        // Delegated
        document.addEventListener('click', e => {
            const btnAdd = e.target.closest('.js-btn-add-prato');
            if (btnAdd) {
                e.stopPropagation();
                addToCart(JSON.parse(btnAdd.dataset.prato), 1, btnAdd.dataset.imagemUrl);
                return;
            }
            const card = e.target.closest('.js-card-prato');
            if (card) {
                const p = JSON.parse(card.dataset.prato);
                currentPrato = p; currentQtd = 1;
                els.mImg.src = card.dataset.imagemUrl;
                els.mNome.innerText = p.nome;
                els.mDesc.innerText = p.descricao;
                els.mQtd.innerText = 1;
                els.mTotal.innerText = 'R$ ' + parseFloat(p.preco).toFixed(2).replace('.', ',');
                els.overlay.classList.add('open');
            }
        });

        document.getElementById('m-btn-minus').onclick = () => { if(currentQtd > 1) { currentQtd--; els.mQtd.innerText = currentQtd; updatePrice(); } };
        document.getElementById('m-btn-plus').onclick = () => { currentQtd++; els.mQtd.innerText = currentQtd; updatePrice(); };
        const updatePrice = () => { els.mTotal.innerText = 'R$ ' + (currentPrato.preco * currentQtd).toFixed(2).replace('.', ','); };
        document.getElementById('m-btn-confirm').onclick = () => { addToCart(currentPrato, currentQtd, els.mImg.src); els.overlay.classList.remove('open'); };
        els.overlay.onclick = e => { if(e.target === els.overlay) els.overlay.classList.remove('open'); };

        // Checkout Modal (White Label)
        document.getElementById('btnCheckoutCart').onclick = () => {
            if (cart.length === 0) return;
            const total = cart.reduce((acc, i) => acc + (i.preco * i.qtd), 0).toFixed(2).replace('.', ',');
            const itemsHtml = cart.map(i => `<div style="display:flex; justify-content:space-between; font-size:14px; margin-bottom:10px; color:#475569;"><span>${i.qtd}x ${i.nome}</span><strong>R$ ${(i.preco*i.qtd).toFixed(2).replace('.',',')}</strong></div>`).join('');
            
            const modal = `
            <div id="confirmModal" style="position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:500; display:flex; align-items:center; justify-content:center; padding:20px;">
                <div style="background:#fff; width:100%; max-width:400px; border-radius:32px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation:modalIn 0.3s ease-out;">
                    <div style="background:var(--brand); padding:30px; text-align:center; color:#fff;">
                        <div style="width:60px; height:60px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px;">
                            <i class="fas fa-utensils text-2xl"></i>
                        </div>
                        <h3 style="font-family:var(--font-h); font-size:1.6rem; font-weight:700;">Confirmar Pedido?</h3>
                        <p style="font-size:13px; opacity:0.8; margin-top:5px;">O pedido será enviado para a cozinha.</p>
                    </div>
                    <div style="padding:30px;">
                        <div style="max-height:150px; overflow-y:auto; margin-bottom:20px; border-bottom:1px solid #f1f5f9; padding-bottom:15px;">${itemsHtml}</div>
                        <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:15px; border-radius:16px; margin-bottom:25px;">
                            <span style="font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase;">Total</span>
                            <span style="font-size:1.5rem; font-weight:900; color:var(--brand);">R$ ${total}</span>
                        </div>
                        <div style="display:flex; gap:12px; flex-wrap: wrap;">
                            <button onclick="document.getElementById('confirmModal').remove()" style="flex:1; min-width: 100px; padding: 15px; border-radius: 15px; border:none; background:#f1f5f9; color:#64748b; font-weight:700; cursor:pointer;">Voltar</button>
                            <button id="btnSendReal" style="flex:1.5; min-width: 140px; padding: 15px; border-radius: 15px; border:none; background:var(--brand); color:#fff; font-weight:800; cursor:pointer; box-shadow:0 10px 15px -3px rgba(30,58,138,0.2);">Confirmar</button>
                        </div>

                    </div>
                </div>
            </div>`;
            document.body.insertAdjacentHTML('beforeend', modal);

            document.getElementById('btnSendReal').onclick = async function() {
                this.disabled = true; this.innerText = 'Enviando...';
                try {
                    const res = await fetch('{{ route("mesa.pedir") }}', { 
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ itens: cart.map(i => ({ prato_id: i.id, qtd: i.qtd })) })
                    });
                    const d = await res.json();
                    if (d.success) { 
                        document.getElementById('confirmModal').remove();
                        localStorage.removeItem('mesa_cart_{{ $mesa->id }}'); 
                        window.location.href = d.redirect; 
                    } else {
                        this.disabled = false; this.innerText = 'Confirmar';
                        showPremiumModal('Atenção', d.message, 'warning');
                    }
                } catch(e) { 
                    this.disabled = false; this.innerText = 'Confirmar';
                    showPremiumModal('Erro no Servidor', 'Ocorreu um problema ao processar seu pedido. Por favor, tente novamente ou chame o garçom.', 'error');
                }

            };
        };

        function showPremiumModal(title, message, type = 'warning') {
            const overlay = document.getElementById('premium-modal-overlay');
            const icon = document.getElementById('p-modal-icon');
            const titleEl = document.getElementById('p-modal-title');
            const textEl = document.getElementById('p-modal-text');

            icon.className = 'p-modal-icon ' + type;
            icon.innerHTML = type === 'error' ? '<i class="fas fa-exclamation-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
            titleEl.innerText = title;
            textEl.innerText = message;

            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closePremiumModal() {
            document.getElementById('premium-modal-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }


        // Categories (Desktop & Mobile)
        document.querySelectorAll('.js-cat-filter').forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                document.querySelectorAll('.js-cat-filter').forEach(b => b.classList.remove('active'));
                
                // Active on both desktop and mobile lists
                const id = btn.dataset.id;
                document.querySelectorAll(`.js-cat-filter[data-id="${id}"]`).forEach(b => b.classList.add('active'));
                
                document.querySelectorAll('.categoria-section').forEach(s => {
                    s.style.display = (id === 'todas' || s.id === 'secao-'+id) ? 'block' : 'none';
                });

                // Scroll to top of content
                const mainWrap = document.querySelector('.main-wrap');
                window.scrollTo({ top: mainWrap.offsetTop - 120, behavior: 'smooth' });
            };
        });


        renderCart();
    });
</script>
</body>
</html>
