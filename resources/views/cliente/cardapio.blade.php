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
            --amber:   #1E3A8A;
            --amber-d: #1E40AF;
            --dark:    #F8FAFC;
            --dark2:   #F1F5F9;
            --surface: #FFFFFF;
            --surface2:#F8FAFC;
            --text:    #0F172A;
            --text-m:  #64748B;
            --text-s:  #94A3B8;
            --red:     #EF4444;
            --border:  rgba(30, 58, 138, 0.15);
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

        .btn-sacola {
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, var(--amber) 0%, var(--amber-d) 100%);
            color: #fff; border: none; padding: 10px 20px;
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

        /* HERO */
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
        .card-desc{
            font-size:0.78rem; color:var(--text-s); line-height:1.4;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
            cursor:pointer; transition:color 0.2s;
        }
        .card-desc:hover{ color:var(--amber); text-decoration:underline; }
        .card-preco{font-size:1.2rem;font-weight:700;color:var(--amber)}
        .btn-add{background:var(--amber);color:#fff;border:none;border-radius:12px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.2rem; transition: 0.2s;}
        .btn-add:hover{background:var(--amber-d); transform: scale(1.05); box-shadow: 0 4px 10px rgba(30,58,138,0.2);}

        /* BARRA FLUTUANTE */
        .floating-cart {
            position: fixed; bottom: -100px; left: 50%; transform: translateX(-50%);
            width: 90%; max-width: 480px; height: 60px;
            background: rgba(30, 58, 138, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px; z-index: 250; 
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
        }
        .floating-cart.active { bottom: 30px; }
        .floating-cart:hover { transform: translateX(-50%) translateY(-3px); box-shadow: 0 15px 40px rgba(30, 58, 138, 0.4); }
        .floating-cart-inner { display: flex; align-items: center; justify-content: space-between; height: 100%; padding: 0 1.2rem; color: #FFFFFF; width: 100%; }
        
        .float-qty-box { background: rgba(255,255,255,0.15); padding: 6px 14px; border-radius: 12px; font-weight: 800; font-size: 1.1rem; border: 1px solid rgba(255,255,255,0.1); }
        .float-view-label { font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; }
        .float-total-val { font-weight: 800; font-size: 1.1rem; color: #fff; margin-left: auto;}

        /* MODAL */
        .overlay{position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:200;display:none;align-items:center;justify-content:center; padding: 20px;}
        .overlay.open{display:flex;}
        .modal{background:var(--surface);border-radius:24px;width:100%;max-width:500px;overflow:hidden; border: 1px solid var(--border); animation: modalIn 0.3s ease-out;}
        @keyframes modalIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }

        /* CART DRAWER */
        .cart-overlay { display: none; }
        .cart-overlay.open { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 290; }
        .cart-drawer{position:fixed;right:0;top:0;bottom:0;width:400px;max-width: 100%; background:var(--surface); z-index:300; transform:translateX(100%); transition:.4s cubic-bezier(0.4, 0, 0.2, 1); display:flex; flex-direction:column; border-left: 1px solid var(--border);}
        .cart-drawer.open{transform:translateX(0); box-shadow: -10px 0 40px rgba(0,0,0,0.5);}

        /* ===== CART ITEM WITH QTY CONTROLS ===== */
        .cart-item {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            align-items: center;
            background: var(--surface);
            padding: 10px;
            border-radius: 14px;
            border: 1px solid var(--border);
            transition: 0.2s;
        }
        .cart-item-img {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            background: var(--dark2);
        }
        .cart-item-info {
            flex: 1;
            min-width: 0;
        }
        .cart-item-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart-item-price {
            color: var(--amber);
            font-weight: 700;
            font-size: 0.9rem;
            margin-top: 3px;
        }
        /* Qty controls — estilo 99/iFood */
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 0;
            background: var(--dark2);
            border-radius: 10px;
            border: 1px solid var(--border);
            overflow: hidden;
            flex-shrink: 0;
        }
        .qty-btn {
            background: none;
            border: none;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--amber);
            transition: background 0.15s;
        }
        .qty-btn:hover {
            background: var(--border);
        }
        .qty-btn.minus:hover {
            color: var(--red);
        }
        .qty-num {
            min-width: 26px;
            text-align: center;
            font-weight: 800;
            font-size: 0.9rem;
            color: var(--text);
        }

        @media(max-width:1024px){ 
            .main-wrap{grid-template-columns:220px 1fr; padding: 2rem 1.5rem 6rem; gap: 1.5rem;} 
        }
        @media(max-width:768px){ 
            .main-wrap{grid-template-columns:1fr;} 
            .sidebar{display:none;} 
            .btn-sacola { display: none; }
            .cart-drawer{width: 100%;}
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
                        {{-- CORREÇÃO: data-imagem-url usa asset() para URL absoluta correta --}}
                        <div class="card js-card-prato"
                             data-prato="{{ json_encode($p) }}"
                             data-imagem-url="{{ asset('storage/' . $p->imagem) }}"
                             data-ingredientes="{{ json_encode($p->ingredientes->map(fn($i) => ['nome' => $i->nome, 'quantidade' => $i->quantidade, 'alergenico' => $i->alergenico, 'tipo_alergenico' => $i->tipo_alergenico])) }}"
                             data-insumos="{{ json_encode($p->insumos->map(fn($i) => ['nome' => $i->nome, 'quantidade' => $i->pivot->quantidade, 'unidade' => $i->unidade])) }}">
                            <div class="card-img">
                                <img src="{{ asset('storage/' . $p->imagem) }}" alt="{{ $p->nome }}" class="js-dish-img">
                            </div>
                            <div class="card-body">
                                <div class="card-nome">{{ $p->nome }}</div>
                                <div class="card-desc js-card-prato"
                                     data-prato="{{ json_encode($p) }}"
                                     data-imagem-url="{{ asset('storage/' . $p->imagem) }}"
                                     data-ingredientes="{{ json_encode($p->ingredientes->map(fn($i) => ['nome' => $i->nome, 'quantidade' => $i->quantidade, 'alergenico' => $i->alergenico, 'tipo_alergenico' => $i->tipo_alergenico])) }}"
                                     data-insumos="{{ json_encode($p->insumos->map(fn($i) => ['nome' => $i->nome, 'quantidade' => $i->pivot->quantidade, 'unidade' => $i->unidade])) }}">
                                    🍴 Toque para ver ingredientes e composição
                                </div>
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
        <div style="display:flex; align-items:center; gap:15px; width:100%;">
            <span id="floatQty" class="float-qty-box">0</span>
            <span class="float-view-label">Ver Pedido</span>
            <div id="floatTotal" class="float-total-val">R$ 0,00</div>
        </div>
    </div>
</div>

<div class="cart-drawer" id="cartDrawer">
    <div style="padding:25px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items: center; background: var(--surface);">
        <h3 style="font-family: var(--font-h); font-size: 1.4rem;">Seu Pedido</h3>
        <button id="btnCloseCart" style="background:var(--dark2); border:none; color:var(--text-m); cursor:pointer; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-times"></i></button>
    </div>
    
    <div id="cartItems" style="flex:1; overflow-y:auto; padding:20px; background: var(--surface);"></div>
    
    <div style="padding:25px; border-top:1px solid var(--border); background: var(--surface2);">
        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <span style="color: var(--text-m);">Subtotal</span>
            <span id="cartTotalFinal" style="font-weight:bold; color:var(--amber); font-size:1.4rem;">R$ 0,00</span>
        </div>
        <button id="btnCheckoutCart" style="width:100%; background:var(--amber); border:none; padding:20px; border-radius:16px; font-weight:800; cursor:pointer; color: #fff; font-size: 1.1rem; transition: 0.3s;">
            Finalizar Pedido
        </button>
    </div>
</div>

<div class="overlay" id="overlay">
    <div class="modal" style="max-height:90vh; overflow-y:auto;">
        <img id="m-img" src="" style="width:100%; height:220px; object-fit:cover; flex-shrink:0;">
        <div style="padding:25px;">
            <h2 id="m-nome" style="font-family: var(--font-h); font-size: 1.8rem;"></h2>
            <p id="m-desc" style="color:var(--text-m); margin-top:8px; font-size: 0.95rem; line-height: 1.6;"></p>

            {{-- INGREDIENTES --}}
            <div id="m-ingredientes-wrap" style="margin-top:20px; display:none;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                    <span style="font-size:1rem;">🥗</span>
                    <span style="font-weight:700; font-size:0.85rem; text-transform:uppercase; letter-spacing:.06em; color:var(--text);">Ingredientes</span>
                </div>
                <div id="m-ingredientes-list" style="display:flex; flex-wrap:wrap; gap:8px;"></div>
            </div>

            {{-- ALERGÊNICOS --}}
            <div id="m-alergenicos-wrap" style="margin-top:16px; display:none;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <span style="font-size:1rem;">⚠️</span>
                    <span style="font-weight:700; font-size:0.85rem; text-transform:uppercase; letter-spacing:.06em; color:#B45309;">Contém alergênicos</span>
                </div>
                <div id="m-alergenicos-list" style="display:flex; flex-wrap:wrap; gap:6px;"></div>
            </div>

            {{-- INSUMOS DO ESTOQUE --}}
            <div id="m-insumos-wrap" style="margin-top:16px; display:none;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                    <span style="font-size:1rem;">🧂</span>
                    <span style="font-weight:700; font-size:0.85rem; text-transform:uppercase; letter-spacing:.06em; color:var(--text);">Composição / Insumos</span>
                </div>
                <div id="m-insumos-list" style="display:flex; flex-wrap:wrap; gap:8px;"></div>
            </div>

            <div style="margin-top:24px; display:flex; justify-content:space-between; align-items:center; gap: 20px;">
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

<script nonce="{{ $csp_nonce }}">
    document.addEventListener('DOMContentLoaded', function() {
        // --- STATE ---
        let cart = JSON.parse(localStorage.getItem('bellacucina_cart') || '[]');
        let currentPrato = null;
        let currentQtd = 1;
        let currentImageUrl = '';

        // --- ELEMENTS ---
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

        // --- BUILD IMAGE MAP from DOM (source of truth) ---
        // Mapeia id do prato → URL absoluta da imagem já renderizada no cardápio
        const imgMap = {};
        document.querySelectorAll('.js-btn-add-prato[data-imagem-url]').forEach(btn => {
            try {
                const prato = JSON.parse(btn.dataset.prato);
                if (prato?.id && btn.dataset.imagemUrl) {
                    imgMap[prato.id] = btn.dataset.imagemUrl;
                }
            } catch(e) {}
        });

        // Atualiza itens já no localStorage que não têm imageUrl (cache antigo)
        let cartUpdated = false;
        cart.forEach(item => {
            if (!item.imageUrl && imgMap[item.id]) {
                item.imageUrl = imgMap[item.id];
                cartUpdated = true;
            }
        });
        if (cartUpdated) {
            localStorage.setItem('bellacucina_cart', JSON.stringify(cart));
        }

        // --- INITIALIZE ---
        renderCart();

        // --- EVENTS ---

        // Sidebar Categories
        document.querySelectorAll('.js-cat-filter').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                filtrar(btn.dataset.id, btn);
            });
        });

        // Cart toggles
        document.getElementById('btnToggleCartTop')?.addEventListener('click', toggleCart);
        document.getElementById('btnCloseCart')?.addEventListener('click', toggleCart);
        els.cartOverlay?.addEventListener('click', toggleCart);
        els.floatingCart?.addEventListener('click', toggleCart);

        // Modal qty
        document.getElementById('m-btn-minus')?.addEventListener('click', () => updateModalQtd(-1));
        document.getElementById('m-btn-plus')?.addEventListener('click',  () => updateModalQtd(1));
        document.getElementById('m-btn-confirm')?.addEventListener('click', () => {
            if (currentPrato) {
                addToCart(currentPrato, currentQtd, currentImageUrl);
                closeModal();
            }
        });
        els.overlay?.addEventListener('click', (e) => {
            if (e.target === els.overlay) closeModal();
        });

        // Delegated clicks
        document.addEventListener('click', (e) => {
            // Quick Add
            const btnAdd = e.target.closest('.js-btn-add-prato');
            if (btnAdd) {
                e.stopPropagation();
                try {
                    const prato    = JSON.parse(btnAdd.dataset.prato);
                    const imageUrl = btnAdd.dataset.imagemUrl || '';
                    addToCart(prato, 1, imageUrl);
                    animateFloatingCart();
                } catch(err) { console.error(err); }
                return;
            }

            // Open Modal
            const card = e.target.closest('.js-card-prato');
            if (card) {
                try {
                    const prato       = JSON.parse(card.dataset.prato);
                    const imageUrl    = card.dataset.imagemUrl || '';
                    const ingredientes = JSON.parse(card.dataset.ingredientes || '[]');
                    const insumos      = JSON.parse(card.dataset.insumos || '[]');
                    openModal(prato, imageUrl, ingredientes, insumos);
                } catch(err) { console.error(err); }
                return;
            }

            // ===== QTY CONTROLS IN CART =====
            const btnQtyMinus = e.target.closest('.js-qty-minus');
            if (btnQtyMinus) {
                const idx = parseInt(btnQtyMinus.dataset.index);
                changeQty(idx, -1);
                return;
            }

            const btnQtyPlus = e.target.closest('.js-qty-plus');
            if (btnQtyPlus) {
                const idx = parseInt(btnQtyPlus.dataset.index);
                changeQty(idx, 1);
                return;
            }
        });

        // Checkout
        document.getElementById('btnCheckoutCart')?.addEventListener('click', () => {
            if (cart.length > 0) window.location.href = '/checkout';
        });

        // --- FUNCTIONS ---

        function addToCart(prato, qtd, imageUrl) {
            const index = cart.findIndex(it => it.id === prato.id);
            // Resolve a melhor URL disponível: parâmetro → imgMap → fallback relativo
            const resolvedUrl = imageUrl || imgMap[prato.id] || ('/storage/' + prato.imagem);
            if (index > -1) {
                cart[index].qtd += qtd;
                // Atualiza imageUrl se estava faltando
                if (!cart[index].imageUrl) cart[index].imageUrl = resolvedUrl;
            } else {
                cart.push({
                    id:       prato.id,
                    nome:     prato.nome,
                    preco:    parseFloat(prato.preco),
                    imagem:   prato.imagem,
                    imageUrl: resolvedUrl,
                    qtd:      qtd
                });
            }
            renderCart();
        }

        /**
         * Altera quantidade; remove se chegar a 0
         */
        function changeQty(index, delta) {
            if (!cart[index]) return;
            cart[index].qtd += delta;
            if (cart[index].qtd <= 0) {
                cart.splice(index, 1);
            }
            renderCart();
        }

        function renderCart() {
            if (!els.cartItems) return;

            els.cartItems.innerHTML = '';
            let total    = 0;
            let totalQtd = 0;

            if (cart.length === 0) {
                els.cartItems.innerHTML = `
                    <div style="text-align:center; padding:60px 20px; color:var(--text-m);
                                display:flex; flex-direction:column; align-items:center;
                                justify-content:center; height:100%;">
                        <i class="fas fa-shopping-basket"
                           style="font-size:3.5rem; color:var(--border); margin-bottom:20px;"></i>
                        <p style="font-weight:600; font-size:1.1rem;">Sua sacola está vazia</p>
                        <p style="font-size:0.9rem; color:var(--text-s); margin-top:8px;">
                            Que tal adicionar alguns pratos deliciosos?
                        </p>
                    </div>`;
                els.floatingCart?.classList.remove('active');
            } else {
                cart.forEach((item, i) => {
                    total    += item.preco * item.qtd;
                    totalQtd += item.qtd;

                    // Resolve URL: salva no item → imgMap do DOM → fallback /storage/
                    const imgSrc = item.imageUrl || imgMap[item.id] || ('/storage/' + item.imagem);
                    const itemTotal = (item.preco * item.qtd)
                        .toLocaleString('pt-br', { minimumFractionDigits: 2 });

                    els.cartItems.innerHTML += `
                        <div class="cart-item">
                            <img
                                src="${imgSrc}"
                                alt="${item.nome}"
                                class="cart-item-img"
                                onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100&auto=format&fit=crop'">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.nome}</div>
                                <div class="cart-item-price">R$ ${itemTotal}</div>
                            </div>
                            <div class="qty-controls">
                                <button class="qty-btn minus js-qty-minus" data-index="${i}"
                                        title="${item.qtd === 1 ? 'Remover' : 'Diminuir'}">
                                    ${item.qtd === 1
                                        ? '<i class="fas fa-trash-alt" style="font-size:0.7rem;color:var(--red)"></i>'
                                        : '−'}
                                </button>
                                <span class="qty-num">${item.qtd}</span>
                                <button class="qty-btn plus js-qty-plus" data-index="${i}">+</button>
                            </div>
                        </div>`;
                });
                els.floatingCart?.classList.add('active');
            }

            const totalStr = 'R$ ' + total.toLocaleString('pt-br', { minimumFractionDigits: 2 });
            if (els.cartBadge)      els.cartBadge.innerText      = totalQtd;
            if (els.floatQty)       els.floatQty.innerText        = totalQtd;
            if (els.floatTotal)     els.floatTotal.innerText      = totalStr;
            if (els.cartTotalFinal) els.cartTotalFinal.innerText  = totalStr;

            localStorage.setItem('bellacucina_cart', JSON.stringify(cart));
        }

        function openModal(prato, imageUrl, ingredientes, insumos) {
            currentPrato    = prato;
            currentQtd      = 1;
            currentImageUrl = imageUrl;

            els.mImg.src        = imageUrl || ('/storage/' + prato.imagem);
            els.mNome.innerText = prato.nome;
            els.mDesc.innerText = prato.descricao || 'Preparado com ingredientes frescos e selecionados, seguindo a tradição da Bella Cucina.';
            els.mQtd.innerText  = currentQtd;

            // --- INGREDIENTES ---
            const ingWrap = document.getElementById('m-ingredientes-wrap');
            const ingList = document.getElementById('m-ingredientes-list');
            if (ingredientes && ingredientes.length > 0) {
                ingList.innerHTML = ingredientes.map(i => {
                    const qty = i.quantidade ? `<span style="opacity:.7;font-size:.75rem;"> · ${i.quantidade}</span>` : '';
                    return `<span style="background:var(--dark2); border:1px solid var(--border); border-radius:20px;
                                padding:5px 12px; font-size:0.82rem; color:var(--text); display:inline-flex; align-items:center; gap:4px;">
                                🌿 ${i.nome}${qty}
                            </span>`;
                }).join('');
                ingWrap.style.display = 'block';

                // Alergênicos
                const alergs = ingredientes.filter(i => i.alergenico);
                const alergWrap = document.getElementById('m-alergenicos-wrap');
                const alergList = document.getElementById('m-alergenicos-list');
                if (alergs.length > 0) {
                    const labelMap = { gluten:'Glúten', lactose:'Lactose', amendoim:'Amendoim',
                        frutos_do_mar:'Frutos do Mar', ovo:'Ovo', soja:'Soja', nozes:'Nozes', outro:'Outro' };
                    alergList.innerHTML = alergs.map(i =>
                        `<span style="background:#FEF3C7; border:1px solid #FCD34D; color:#92400E;
                                      border-radius:20px; padding:4px 12px; font-size:0.8rem; font-weight:600;">
                            ⚠️ ${labelMap[i.tipo_alergenico] || i.tipo_alergenico || i.nome}
                         </span>`
                    ).join('');
                    alergWrap.style.display = 'block';
                } else {
                    alergWrap.style.display = 'none';
                }
            } else {
                ingWrap.style.display  = 'none';
                document.getElementById('m-alergenicos-wrap').style.display = 'none';
            }

            // --- INSUMOS DO ESTOQUE ---
            const insWrap = document.getElementById('m-insumos-wrap');
            const insList = document.getElementById('m-insumos-list');
            if (insumos && insumos.length > 0) {
                insList.innerHTML = insumos.map(i => {
                    const qty = (i.quantidade && i.unidade) ? `<span style="opacity:.7;font-size:.75rem;"> · ${i.quantidade} ${i.unidade}</span>` : '';
                    return `<span style="background:#EFF6FF; border:1px solid #BFDBFE; border-radius:20px;
                                padding:5px 12px; font-size:0.82rem; color:#1E40AF; display:inline-flex; align-items:center; gap:4px;">
                                🧂 ${i.nome}${qty}
                            </span>`;
                }).join('');
                insWrap.style.display = 'block';
            } else {
                insWrap.style.display = 'none';
            }

            updateModalPrice();
            els.overlay?.classList.add('open');
        }

        function closeModal() {
            els.overlay?.classList.remove('open');
            currentPrato    = null;
            currentImageUrl = '';
        }

        function updateModalQtd(v) {
            currentQtd = Math.max(1, currentQtd + v);
            els.mQtd.innerText = currentQtd;
            updateModalPrice();
        }

        function updateModalPrice() {
            if (!currentPrato) return;
            const total = parseFloat(currentPrato.preco) * currentQtd;
            els.mTotal.innerText = 'R$ ' + total.toLocaleString('pt-br', { minimumFractionDigits: 2 });
        }

        function toggleCart() {
            els.cartDrawer?.classList.toggle('open');
            els.cartOverlay?.classList.toggle('open');
        }

        function filtrar(id, el) {
            document.querySelectorAll('.cat-item').forEach(b => b.classList.remove('active'));
            if (el) el.classList.add('active');

            const sections = document.querySelectorAll('.categoria-section');
            if (id === 'todas') {
                sections.forEach(s => s.style.display = 'block');
            } else {
                sections.forEach(s => {
                    s.style.display = (s.id === 'secao-' + id) ? 'block' : 'none';
                });
            }
            window.scrollTo({ top: document.querySelector('.main-wrap').offsetTop - 100, behavior: 'smooth' });
        }

        function animateFloatingCart() {
            els.floatingCart?.animate([
                { transform: 'translateX(-50%) scale(1)' },
                { transform: 'translateX(-50%) scale(1.05)' },
                { transform: 'translateX(-50%) scale(1)' }
            ], { duration: 300, easing: 'ease-out' });
        }

        // Fallback imagens com erro
        document.addEventListener('error', (e) => {
            if (e.target.tagName === 'IMG' &&
                (e.target.classList.contains('js-dish-img') || e.target.id === 'm-img')) {
                e.target.src = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';
            }
        }, true);
    });
</script>

</body>
</html>