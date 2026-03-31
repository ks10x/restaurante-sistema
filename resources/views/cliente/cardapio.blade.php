<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio — Bella Cucina</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --amber:   #1E3A8A; /* Navy Blue replacing amber primary */
            --amber-d: #1E40AF; /* Darker Navy */
            --dark:    #F8FAFC; /* Light Slate mapping from dark */
            --dark2:   #F1F5F9; /* Muted Slate */
            --surface: #FFFFFF; /* White */
            --surface2:#F8FAFC; 
            --text:    #0F172A; /* Slate 900 */
            --text-m:  #64748B; /* Slate 500 */
            --text-s:  #94A3B8; /* Slate 400 */
            --red:     #EF4444;
            --border:  rgba(30, 58, 138, 0.15); /* Navy border */
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
        }
        .logo{font-family:var(--font-h);font-size:1.5rem;color:var(--amber);letter-spacing:-0.5px; text-decoration: none;}
        .logo span{color:var(--text-m);font-weight:400;font-size:1.1rem}

        /* HEADER ACTIONS & PROFILE */
        .header-actions{display:flex;gap:1.5rem;align-items:center}
        
        .user-profile-wrapper { position: relative; }
        .user-profile-btn {
            display: flex; align-items: center; gap: 12px;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 6px 16px 6px 8px;
            border-radius: 50px;
            cursor: pointer; transition: all 0.3s ease;
        }
        .user-profile-btn:hover { border-color: var(--amber); background: rgba(212, 163, 115, 0.05); }
        
        .user-initial {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--amber) 0%, var(--amber-d) 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #FFFFFF; font-weight: 700; font-size: 16px;
            box-shadow: 0 2px 6px rgba(30, 58, 138, 0.2);
        }

        .user-dropdown {
            position: absolute; top: 100%; right: 0; margin-top: 12px;
            width: 220px; background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            opacity: 0; visibility: hidden; transform: translateY(10px);
            transition: all 0.3s ease; z-index: 100; overflow: hidden;
        }
        .user-profile-wrapper:hover .user-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }

        .dropdown-link {
            display: flex; align-items: center; gap: 12px; padding: 12px 20px;
            color: var(--text-m); text-decoration: none; font-size: 13px; transition: 0.2s;
        }
        .dropdown-link:hover { background: var(--dark); color: var(--amber); }

        /* BUTTON SACOLA */
        .btn-sacola {
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, var(--amber) 0%, var(--amber-d) 100%);
            color: var(--dark); border: none; padding: 10px 20px;
            border-radius: 50px; font-weight: 700; font-size: 14px;
            cursor: pointer; transition: all 0.3s ease; position: relative;
            box-shadow: 0 4px 10px rgba(212, 163, 115, 0.2);
        }
        .btn-sacola:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(212, 163, 115, 0.3); }

        .cart-count {
            position: absolute; top: -6px; right: -6px;
            background: var(--red); color: white; font-size: 10px;
            width: 18px; height: 18px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #FFFFFF; font-weight: 800;
        }

        /* HERO & BADGES */
        .hero{ padding:4rem 2rem 2rem; background:linear-gradient(180deg, rgba(212,163,115,0.06) 0%, transparent 100%); border-bottom:1px solid var(--border); text-align: center;}
        .hero h1{font-family:var(--font-h);font-size:clamp(2rem,5vw,3.5rem);line-height:1.1;color:var(--text); margin-bottom: 1rem;}
        .hero h1 em{color:var(--amber);font-style:normal}
        
        .info-badges { display: flex; gap: 1rem; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap; }
        .badge-item { background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border); padding: 0.6rem 1.2rem; border-radius: 50px; color: var(--amber); font-size: 0.8rem; display: flex; align-items: center; gap: 8px; }

        /* MAIN LAYOUT */
        .main-wrap{max-width:1400px;margin:0 auto;padding:2.5rem 2rem 4rem;display:grid;grid-template-columns:220px 1fr;gap:2.5rem; transition: 0.3s;}

        .sidebar{position:sticky;top:100px;height:fit-content}
        .sidebar-title{font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-s);margin-bottom:1rem}
        .cat-item{
            display:flex;align-items:center;gap:.75rem; padding:.75rem 1rem;border-radius:12px;
            cursor:pointer;transition:.2s;margin-bottom:.4rem;
            font-size:.95rem;color:var(--text-m);font-weight:500; border:1px solid transparent;text-decoration:none;
        }
        .cat-item:hover, .cat-item.active{background:var(--surface);border-color:var(--border);color:var(--amber); box-shadow: 0 2px 8px rgba(30, 58, 138, 0.05);}

        /* GRID E CARDS */
        .destaques-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;margin-bottom:3rem;}
        .section-title{font-family:var(--font-h);font-size:1.5rem;color:var(--text);margin-bottom:1.5rem;display:flex;align-items:baseline;gap:.75rem;}
        .section-title::after{content:'';flex:1;height:1px;background:linear-gradient(to right, var(--border), transparent);}

        .card{background:var(--surface);border:1px solid var(--border);border-radius:20px;overflow:hidden;transition:.3s;cursor:pointer;display:flex;flex-direction:column;}
        .card:hover{transform:translateY(-5px); border-color:var(--amber); box-shadow: 0 10px 20px rgba(30, 58, 138, 0.08);}
        .card-img{height:180px;overflow:hidden;position:relative;background:var(--dark2);}
        .card-img img{width:100%;height:100%;object-fit:cover;transition:.4s}
        .card:hover .card-img img { transform: scale(1.05); }
        .card-body{padding:1.25rem;flex:1;display:flex;flex-direction:column;gap:.5rem}
        .card-nome{font-family:var(--font-h);font-size:1.1rem;color:var(--text); font-weight: 600;}
        .card-preco{font-size:1.2rem;font-weight:700;color:var(--amber)}
        .btn-add{background:var(--amber);color:#fff;border:none;border-radius:12px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.2rem; transition: 0.2s;}
        .btn-add:hover{background:var(--amber-d); transform: scale(1.05); box-shadow: 0 4px 10px rgba(30,58,138,0.2);}

        /* BARRA FLUTUANTE (GLASSMORPHISM) */
        .floating-cart {
            position: fixed; bottom: -100px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 500px; height: 64px;
            background: rgba(30, 58, 138, 0.9); /* Navy with opacity */
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; z-index: 250; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            cursor: pointer; transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: none; /* Mobile Only */
        }
        .floating-cart.active { bottom: 25px; }
        .floating-cart-inner { display: flex; align-items: center; justify-content: space-between; height: 100%; padding: 0 1.2rem; color: #FFFFFF; }
        
        .float-qty-box { background: rgba(255,255,255,0.15); padding: 6px 14px; border-radius: 12px; font-weight: 800; font-size: 1.1rem; border: 1px solid rgba(255,255,255,0.1); }
        .float-view-label { font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; }
        .float-total-val { font-weight: 800; font-size: 1.1rem; color: #fff; }



        /* MODAL */
        .overlay{position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:200;display:none;align-items:center;justify-content:center; padding: 20px;}
        .overlay.open{display:flex;}
        .modal{background:var(--surface);border-radius:24px;width:100%;max-width:500px;overflow:hidden; border: 1px solid var(--border); animation: modalIn 0.3s ease-out;}
        @keyframes modalIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }

        /* CART DRAWER (Universal Fixed) */
        .cart-overlay { display: none; }
        .cart-overlay.open { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 290; }
        .cart-drawer{position:fixed;right:0;top:0;bottom:0;width:400px;max-width: 100%; background:var(--surface); z-index:300; transform:translateX(100%); transition:.4s cubic-bezier(0.4, 0, 0.2, 1); display:flex; flex-direction:column; border-left: 1px solid var(--border);}
        .cart-drawer.open{transform:translateX(0); box-shadow: -10px 0 40px rgba(0,0,0,0.5);}

        @media(max-width:1024px){ 
            .main-wrap{grid-template-columns:220px 1fr; padding: 2rem 1.5rem 6rem; gap: 1.5rem;} 
            .floating-cart { display: flex; }
        }
        @media(max-width:768px){ 
            .main-wrap{grid-template-columns:1fr;} 
            .sidebar{display:none;} 
            .btn-sacola { display: none; } /* Ocultar sacola do topo no mobile */
            .cart-drawer{width: 100%;}
        }
        @media(max-width:768px){ 
            .main-wrap{grid-template-columns:1fr;} 
            .sidebar{display:none;} 
            .btn-sacola { display: none; } /* Ocultar sacola do topo no mobile */
        }
    </style>
</head>
<body>

<header>
    <a href="/" class="logo">Bella<span>Cucina</span></a>
    
    <div class="header-actions">
        <button class="btn-sacola" id="btnToggleCartTop">
            <i class="fas fa-shopping-bag"></i>
            Pedido
            <div id="cartBadge" class="cart-count">0</div>
        </button>

        @auth
            <div class="user-profile-wrapper">
                <button class="user-profile-btn">
                    <div class="user-initial">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-text" style="display: flex; flex-direction: column; align-items: flex-start;">
                        <span style="color: var(--text-s); font-size: 10px; text-transform: uppercase; font-weight: 600;">Olá,</span>
                        <span style="color: var(--amber); font-size: 14px; font-weight: 600; font-family: var(--font-h);">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    </div>
                    <i class="fas fa-chevron-down" style="color: var(--text-s); font-size: 10px; margin-left: 4px;"></i>
                </button>

                <div class="user-dropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-link">
                        <i class="fas fa-user-circle"></i> Minha Conta
                    </a>
                    
                    <a href="{{ route('cliente.pedidos') }}" class="dropdown-link">
                        <i class="fas fa-utensils"></i> Meus Pedidos
                    </a>
                    
                    <a href="{{ route('cliente.configuracoes') }}" class="dropdown-link">
                        <i class="fas fa-cog"></i> Configurações
                    </a>
                    
                    <div style="border-top: 1px solid var(--border); margin: 4px 0;"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-link" style="width: 100%; background: transparent; border: none; cursor: pointer; color: var(--red);">
                            <i class="fas fa-sign-out-alt"></i> Sair da Conta
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-login" style="text-decoration: none; color: var(--text-m); font-weight: 600; font-size: 14px; padding: 10px 20px; border: 1px solid var(--border); border-radius: 50px;">Entrar</a>
        @endauth
    </div>
</header>

<div class="hero">
    <div class="hero-inner">
        <h1>Sabor que aquece a <em>alma</em></h1>
        <p style="color: var(--text-m); font-size: 1.1rem;">A verdadeira essência da gastronomia italiana na sua mesa.</p>
    </div>
    <div class="info-badges">
        <div class="badge-item">🕒 ~45 min</div>
        <div class="badge-item">📍 Guaianases e região</div>
        <div class="badge-item">⭐ 4.9 (High Tech)</div>
    </div>
</div>

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
                        <div class="card js-card-prato" data-prato="{{ json_encode($p) }}">
                            <div class="card-img">
                                <img src="{{ asset('storage/' . $p->imagem) }}" alt="{{ $p->nome }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=300&auto=format&fit=crop'">
                            </div>
                            <div class="card-body">
                                <div class="card-nome">{{ $p->nome }}</div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-top: auto;">
                                    <div class="card-preco">R$ {{ number_format($p->preco, 2, ',', '.') }}</div>
                                    <button class="btn-add js-btn-add-prato" data-prato="{{ json_encode($p) }}">+</button>
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
        <div style="display:flex; align-items:center; gap:15px;">
            <span id="floatQty" class="float-qty-box">0</span>
            <span class="float-view-label">Ver Pedido</span>
        </div>
        <div id="floatTotal" class="float-total-val">R$ 0,00</div>
    </div>
</div>

<div class="cart-drawer" id="cartDrawer">
    <div style="padding:25px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items: center; background: var(--surface);">
        <h3 style="font-family: var(--font-h); font-size: 1.4rem;">Seu Pedido</h3>
        <button id="btnCloseCart" style="background:var(--dark2); border:none; color:var(--text-m); cursor:pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-times"></i></button>
    </div>
    
    <div id="cartItems" style="flex:1; overflow-y:auto; padding:20px; background: var(--surface);">
        <div id="cartEmpty" style="text-align: center; padding: 40px 20px; color: var(--text-m); display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
            <i class="fas fa-shopping-basket" style="font-size: 3rem; color: var(--border); margin-bottom: 15px;"></i>
            <p>Sua sacola está vazia</p>
            <p style="font-size: 0.85rem; color: var(--text-s); margin-top: 5px;">Adicione pratos deliciosos!</p>
        </div>
    </div>
    
    <div style="padding:25px; border-top:1px solid var(--border); background: var(--surface2);">
        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <span style="color: var(--text-m);">Subtotal</span>
            <span id="cartTotalFinal" style="font-weight:bold; color:var(--amber); font-size:1.4rem;">R$ 0,00</span>
        </div>
        <button id="btnCheckoutCart" style="width:100%; background:var(--amber); border:none; padding:20px; border-radius:16px; font-weight:800; cursor:pointer; color: #fff; font-size: 1.1rem; transition: 0.3s;" onmouseover="this.style.background='var(--amber-d)'" onmouseout="this.style.background='var(--amber)'">
            Finalizar Pedido
        </button>
    </div>
</div>

<div class="overlay" id="overlay">
    <div class="modal">
        <img id="m-img" src="" style="width:100%; height:250px; object-fit:cover;">
        <div style="padding:25px;">
            <h2 id="m-nome" style="font-family: var(--font-h); font-size: 1.8rem;"></h2>
            <p id="m-desc" style="color:var(--text-m); margin-top:10px; font-size: 0.95rem; line-height: 1.6;"></p>
            
            <div style="margin-top:30px; display:flex; justify-content:space-between; align-items:center; gap: 20px;">
                <div style="display:flex; gap:15px; align-items:center; background:var(--dark); padding:10px 20px; border-radius:15px; border: 1px solid var(--border);">
                    <button id="m-btn-minus" style="background:none; border:none; color:var(--amber); font-size:24px; cursor:pointer;">-</button>
                    <span id="m-qtd" style="font-weight:bold; min-width: 20px; text-align: center;">1</span>
                    <button id="m-btn-plus" style="background:none; border:none; color:var(--amber); font-size:24px; cursor:pointer;">+</button>
                </div>
                <button id="m-btn-confirm" style="background:var(--amber); border:none; padding:18px; border-radius:15px; font-weight:bold; flex:1; cursor:pointer; color: #fff; font-size: 1rem;">
                    Adicionar • <span id="m-total"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load cart from localStorage or start empty
        let cart = JSON.parse(localStorage.getItem('bellacucina_cart') || '[]');
        let currentPrato = null;
        let currentQtd = 1;

        // Render existing cart on load
        renderCart();

        // BIND EVENT LISTENERS (For CSP Compliance with Safety operators)
        document.getElementById('btnToggleCartTop')?.addEventListener('click', toggleCart);
        document.getElementById('btnCloseCart')?.addEventListener('click', toggleCart);
        document.getElementById('cartOverlay')?.addEventListener('click', toggleCart);
        document.getElementById('floatingCart')?.addEventListener('click', toggleCart);
        
        document.getElementById('btnCheckoutCart')?.addEventListener('click', () => {
            if(cart.length === 0) return;
            localStorage.setItem('bellacucina_cart', JSON.stringify(cart));
            window.location.href = '/checkout';
        });
        
        document.getElementById('m-btn-minus')?.addEventListener('click', () => mudarQtd(-1));
        document.getElementById('m-btn-plus')?.addEventListener('click', () => mudarQtd(1));
        document.getElementById('m-btn-confirm')?.addEventListener('click', confirmarAdd);
        
        document.getElementById('overlay')?.addEventListener('click', (e) => {
            if(e.target.id === 'overlay') document.getElementById('overlay').classList.remove('open');
        });

        // Category Filter Bindings
        document.querySelectorAll('.js-cat-filter').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let catId = this.dataset.id;
                filtrar(catId, this);
            });
        });

        // Product Cards Bindings
        document.querySelectorAll('.js-card-prato').forEach(card => {
            card.addEventListener('click', function() {
                try {
                    let prato = JSON.parse(this.dataset.prato);
                    abrirModal(prato);
                } catch(e) { console.error('Erro ao abrir prato:', e); }
            });
        });

        document.querySelectorAll('.js-btn-add-prato').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                try {
                    let prato = JSON.parse(this.dataset.prato);
                    addToCart(prato, 1);
                } catch(e) { console.error('Erro ao add prato:', e); }
            });
        });

        // DELEGATED REMOVE BINDING (since Cart is re-rendered dynamically)
        document.getElementById('cartItems')?.addEventListener('click', function(e) {
            let target = e.target.closest('.js-btn-remove');
            if(target) {
                let index = target.dataset.index;
                remover(index);
            }
        });

        // LOGIC FUNCTIONS
        function addToCart(prato, qtd = 1) {
            const itemIndex = cart.findIndex(i => i.id === prato.id);
            if (itemIndex > -1) {
                cart[itemIndex].qtd += qtd;
            } else {
                cart.push({
                    id: prato.id,
                    nome: prato.nome,
                    preco: parseFloat(prato.preco),
                    imagem: prato.imagem,
                    qtd: qtd
                });
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartItems');
            const badge = document.getElementById('cartBadge');
            const floatQty = document.getElementById('floatQty');
            const floatTotal = document.getElementById('floatTotal');
            const cartTotalFinal = document.getElementById('cartTotalFinal');
            
            if (!container) return;
            
            container.innerHTML = '';
            let total = 0;
            let totalQtd = 0;

            cart.forEach((item, index) => {
                const subtotal = item.preco * item.qtd;
                total += subtotal;
                totalQtd += item.qtd;
                
                container.innerHTML += `
                    <div style="display:flex; gap:12px; margin-bottom:15px; align-items:center; background:var(--dark2); padding:12px; border-radius:16px; border: 1px solid var(--border);">
                        <img src="/storage/${item.imagem}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100&auto=format&fit=crop'" style="width:55px; height:55px; border-radius:10px; object-fit:cover;">
                        <div style="flex:1">
                            <div style="font-size:0.9rem; font-weight:600;">${item.nome}</div>
                            <div style="color:var(--amber); font-weight:bold;">${item.qtd}x R$ ${item.preco.toLocaleString('pt-br', {minimumFractionDigits: 2})}</div>
                        </div>
                        <button class="js-btn-remove" data-index="${index}" style="background:none; border:none; color:var(--red); cursor:pointer; padding: 5px;"><i class="fas fa-trash-alt"></i></button>
                    </div>
                `;
            });

            const totalStr = 'R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2});

            if(badge) badge.innerText = totalQtd;
            if(floatQty) floatQty.innerText = totalQtd;
            if(floatTotal) floatTotal.innerText = totalStr;
            if(cartTotalFinal) cartTotalFinal.innerText = totalStr;

            const floatingCart = document.getElementById('floatingCart');
            if(totalQtd > 0) {
                floatingCart.classList.add('active');
            } else {
                floatingCart.classList.remove('active');
                container.innerHTML = `
                    <div id="cartEmpty" style="text-align: center; padding: 40px 20px; color: var(--text-m); display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                        <i class="fas fa-shopping-basket" style="font-size: 3rem; color: var(--border); margin-bottom: 15px;"></i>
                        <p>Sua sacola está vazia</p>
                        <p style="font-size: 0.85rem; color: var(--text-s); margin-top: 5px;">Adicione pratos deliciosos!</p>
                    </div>
                `;
                document.getElementById('cartDrawer').classList.remove('open');
                document.getElementById('cartOverlay').classList.remove('open');
            }

            // Persist to localStorage
            localStorage.setItem('bellacucina_cart', JSON.stringify(cart));
        }

        function abrirModal(prato) {
            currentPrato = prato;
            currentQtd = 1;
            document.getElementById('m-img').src = '/storage/' + prato.imagem;
            document.getElementById('m-img').onerror = function() { this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop'; };
            document.getElementById('m-nome').innerText = prato.nome;
            document.getElementById('m-desc').innerText = prato.descricao || 'Prato artesanal preparado com o rigor da culinária clássica Bella Cucina.';
            document.getElementById('m-qtd').innerText = currentQtd;
            atualizarPrecoModal();
            document.getElementById('overlay').classList.add('open');
        }

        function mudarQtd(v) {
            currentQtd = Math.max(1, currentQtd + v);
            document.getElementById('m-qtd').innerText = currentQtd;
            atualizarPrecoModal();
        }

        function atualizarPrecoModal() {
            let total = parseFloat(currentPrato.preco) * currentQtd;
            document.getElementById('m-total').innerText = 'R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2});
        }

        function confirmarAdd() {
            addToCart(currentPrato, currentQtd);
            document.getElementById('overlay').classList.remove('open');
        }

        function remover(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function toggleCart() { 
            let drawer = document.getElementById('cartDrawer');
            let overlay = document.getElementById('cartOverlay');
            if (drawer && overlay) {
                drawer.classList.toggle('open'); 
                overlay.classList.toggle('open');
            }
        }

        function filtrar(id, el) {
            document.querySelectorAll('.cat-item').forEach(btn => btn.classList.remove('active'));
            if(el) el.classList.add('active');
            if(id === 'todas') {
                document.querySelectorAll('.categoria-section').forEach(s => s.style.display = 'block');
            } else {
                document.querySelectorAll('.categoria-section').forEach(s => {
                    s.style.display = (s.id === 'secao-'+id) ? 'block' : 'none';
                });
            }
        }
    });
</script>

</body>
</html>