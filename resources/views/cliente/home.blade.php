<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cardápio — Bella Cucina</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --amber:   #E8A045;
  --amber-d: #C47A1A;
  --dark:    #1A1612;
  --dark2:   #24201A;
  --surface: #2E2820;
  --surface2:#3A3228;
  --text:    #F5EDD8;
  --text-m:  #C4B89A;
  --text-s:  #8A7D68;
  --red:     #D85A30;
  --green:   #4CA86B;
  --border:  rgba(232,160,69,0.15);
  --radius:  14px;
  --font-h:  'Playfair Display', serif;
  --font-b:  'DM Sans', sans-serif;
}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--dark);color:var(--text);font-family:var(--font-b);min-height:100vh;overflow-x:hidden}

/* HEADER */
header{
  position:sticky;top:0;z-index:100;
  background:rgba(26,22,18,0.95);
  backdrop-filter:blur(12px);
  border-bottom:1px solid var(--border);
  padding:0 2rem;
  display:flex;align-items:center;justify-content:space-between;
  height:64px;
}
.logo{font-family:var(--font-h);font-size:1.5rem;color:var(--amber);letter-spacing:-0.5px}
.logo span{color:var(--text-m);font-weight:400;font-size:1.1rem}
.header-actions{display:flex;gap:1rem;align-items:center}
.btn-header{
  display:flex;align-items:center;gap:.5rem;
  padding:.5rem 1.2rem;border-radius:8px;
  font-family:var(--font-b);font-size:.875rem;font-weight:500;
  cursor:pointer;border:none;transition:.2s;text-decoration:none;
}
.btn-login{background:var(--surface);color:var(--text-m);border:1px solid var(--border)}
.btn-login:hover{border-color:var(--amber);color:var(--amber)}
.btn-cart{background:var(--amber);color:var(--dark);position:relative}
.btn-cart:hover{background:var(--amber-d)}
.cart-badge{
  position:absolute;top:-6px;right:-6px;
  background:var(--red);color:#fff;
  font-size:.65rem;font-weight:600;
  width:18px;height:18px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
}

/* HERO */
.hero{
  padding:4rem 2rem 2rem;
  background:linear-gradient(180deg, rgba(232,160,69,0.06) 0%, transparent 100%);
  border-bottom:1px solid var(--border);
}
.hero-inner{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr auto;align-items:end;gap:2rem}
.hero h1{font-family:var(--font-h);font-size:clamp(2rem,5vw,3.5rem);line-height:1.1;color:var(--text)}
.hero h1 em{color:var(--amber);font-style:normal}
.hero p{margin-top:.75rem;color:var(--text-m);font-size:1rem;line-height:1.6}
.hero-meta{display:flex;gap:1.5rem;margin-top:1.5rem}
.meta-chip{
  display:flex;align-items:center;gap:.4rem;
  font-size:.8rem;color:var(--text-m);
  background:var(--surface);border:1px solid var(--border);
  padding:.4rem .8rem;border-radius:20px;
}
.meta-chip svg{width:14px;height:14px;color:var(--amber)}

/* BUSCA */
.search-bar{
  display:flex;align-items:center;gap:.75rem;
  background:var(--surface);border:1px solid var(--border);
  border-radius:12px;padding:.75rem 1rem;
  max-width:320px;
}
.search-bar input{
  background:none;border:none;outline:none;
  color:var(--text);font-family:var(--font-b);font-size:.9rem;
  width:220px;
}
.search-bar input::placeholder{color:var(--text-s)}

/* MAIN LAYOUT */
.main-wrap{max-width:1100px;margin:0 auto;padding:0 2rem 4rem;display:grid;grid-template-columns:220px 1fr;gap:2.5rem;padding-top:2.5rem}

/* SIDEBAR CATEGORIAS */
.sidebar{position:sticky;top:80px;height:fit-content}
.sidebar-title{font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-s);margin-bottom:1rem}
.cat-item{
  display:flex;align-items:center;gap:.75rem;
  padding:.65rem 1rem;border-radius:10px;
  cursor:pointer;transition:.2s;margin-bottom:.25rem;
  font-size:.9rem;color:var(--text-m);font-weight:400;
  border:1px solid transparent;text-decoration:none;
}
.cat-item:hover{background:var(--surface);color:var(--text)}
.cat-item.active{background:rgba(232,160,69,0.1);border-color:rgba(232,160,69,0.3);color:var(--amber)}
.cat-icon{font-size:1.1rem}
.cat-count{margin-left:auto;font-size:.75rem;color:var(--text-s)}

/* SEÇÃO DE PRATOS */
.section-title{
  font-family:var(--font-h);font-size:1.5rem;
  color:var(--text);margin-bottom:1.5rem;
  display:flex;align-items:baseline;gap:.75rem;
}
.section-title::after{
  content:'';flex:1;height:1px;
  background:linear-gradient(to right, var(--border), transparent);
}

/* DESTAQUES */
.destaques-grid{
  display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:1.25rem;margin-bottom:3rem;
}

/* CARD DE PRATO */
.card{
  background:var(--dark2);border:1px solid var(--border);
  border-radius:var(--radius);overflow:hidden;
  transition:.25s;cursor:pointer;
  display:flex;flex-direction:column;
}
.card:hover{transform:translateY(-3px);border-color:rgba(232,160,69,0.4);box-shadow:0 8px 32px rgba(0,0,0,0.4)}
.card-img{
  height:180px;overflow:hidden;position:relative;
  background:var(--surface);
}
.card-img img{width:100%;height:100%;object-fit:cover;transition:.4s}
.card:hover .card-img img{transform:scale(1.06)}
.card-badge{
  position:absolute;top:.75rem;left:.75rem;
  background:var(--amber);color:var(--dark);
  font-size:.7rem;font-weight:600;padding:.2rem .6rem;border-radius:6px;
}
.card-badge.promo{background:var(--red);color:#fff}
.card-body{padding:1.1rem 1.25rem 1.25rem;flex:1;display:flex;flex-direction:column;gap:.5rem}
.card-cat{font-size:.75rem;color:var(--amber);font-weight:500;letter-spacing:.05em}
.card-nome{font-family:var(--font-h);font-size:1.05rem;line-height:1.3;color:var(--text)}
.card-desc{font-size:.82rem;color:var(--text-m);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.card-info{display:flex;gap:.5rem;margin-top:.25rem;flex-wrap:wrap}
.info-chip{font-size:.72rem;color:var(--text-s);display:flex;align-items:center;gap:.25rem}
.card-footer{display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:.75rem;border-top:1px solid var(--border)}
.card-preco{display:flex;flex-direction:column}
.preco-de{font-size:.75rem;color:var(--text-s);text-decoration:line-through}
.preco-por{font-size:1.2rem;font-weight:600;color:var(--amber)}
.btn-add{
  background:var(--amber);color:var(--dark);
  border:none;border-radius:8px;
  width:36px;height:36px;display:flex;align-items:center;justify-content:center;
  cursor:pointer;transition:.2s;font-size:1.2rem;
}
.btn-add:hover{background:var(--amber-d);transform:scale(1.1)}

/* ALERGÊNICOS */
.alerg-icons{display:flex;gap:.3rem;margin-top:.25rem}
.alerg{font-size:.65rem;background:rgba(216,90,48,0.15);color:#E07050;padding:.1rem .4rem;border-radius:4px;border:1px solid rgba(216,90,48,0.3)}

/* LISTA HORIZONTAL (destaques no topo) */
.destaques-row{
  display:flex;gap:1rem;overflow-x:auto;padding-bottom:1rem;margin-bottom:2.5rem;
  scrollbar-width:thin;scrollbar-color:var(--surface) transparent;
}
.card-h{
  min-width:260px;background:var(--dark2);border:1px solid var(--border);
  border-radius:var(--radius);display:flex;align-items:center;gap:1rem;
  padding:.85rem;cursor:pointer;transition:.2s;
}
.card-h:hover{border-color:rgba(232,160,69,0.4)}
.card-h-img{width:72px;height:72px;border-radius:10px;object-fit:cover;flex-shrink:0;background:var(--surface)}
.card-h-body{flex:1;min-width:0}
.card-h-nome{font-family:var(--font-h);font-size:.95rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.card-h-preco{font-size:1rem;font-weight:600;color:var(--amber);margin-top:.2rem}
.card-h-badge{font-size:.65rem;background:rgba(232,160,69,0.15);color:var(--amber);padding:.1rem .5rem;border-radius:4px;display:inline-block;margin-bottom:.25rem}

/* MODAL PRATO */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:200;display:flex;align-items:flex-end;justify-content:center;opacity:0;pointer-events:none;transition:.3s}
.overlay.open{opacity:1;pointer-events:all}
.modal{
  background:var(--dark2);border:1px solid var(--border);
  border-radius:20px 20px 0 0;width:100%;max-width:540px;
  max-height:92vh;overflow-y:auto;
  transform:translateY(100%);transition:.35s cubic-bezier(.22,.61,.36,1);
}
.overlay.open .modal{transform:translateY(0)}
.modal-img{height:240px;width:100%;object-fit:cover}
.modal-body{padding:1.5rem}
.modal-cat{font-size:.75rem;color:var(--amber);font-weight:600;letter-spacing:.08em;text-transform:uppercase}
.modal-nome{font-family:var(--font-h);font-size:1.6rem;margin:.4rem 0 .75rem;line-height:1.2}
.modal-desc{color:var(--text-m);font-size:.9rem;line-height:1.65}
.modal-section{margin-top:1.25rem}
.modal-section-title{font-size:.75rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--text-s);margin-bottom:.75rem}
.ingrediente-row{display:flex;align-items:center;gap:.5rem;padding:.4rem 0;border-bottom:1px solid rgba(255,255,255,0.04);font-size:.85rem;color:var(--text-m)}
.ingrediente-row:last-child{border-bottom:none}
.modal-footer{
  position:sticky;bottom:0;
  background:var(--dark2);border-top:1px solid var(--border);
  padding:1rem 1.5rem;display:flex;align-items:center;gap:1rem;
}
.qty-ctrl{display:flex;align-items:center;gap:.75rem;background:var(--surface);border-radius:10px;padding:.25rem .5rem}
.qty-btn{background:none;border:none;color:var(--amber);font-size:1.3rem;cursor:pointer;width:28px;height:28px;display:flex;align-items:center;justify-content:center}
.qty-num{font-size:1rem;font-weight:600;min-width:20px;text-align:center}
.btn-adicionar{
  flex:1;background:var(--amber);color:var(--dark);
  border:none;border-radius:10px;padding:1rem;
  font-family:var(--font-b);font-size:.95rem;font-weight:600;
  cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:space-between;
}
.btn-adicionar:hover{background:var(--amber-d)}

/* CARRINHO LATERAL */
.cart-drawer{
  position:fixed;right:0;top:0;bottom:0;width:380px;
  background:var(--dark2);border-left:1px solid var(--border);
  z-index:300;transform:translateX(100%);transition:.35s cubic-bezier(.22,.61,.36,1);
  display:flex;flex-direction:column;
}
.cart-drawer.open{transform:translateX(0)}
.cart-header{padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.cart-header h2{font-family:var(--font-h);font-size:1.2rem}
.cart-items{flex:1;overflow-y:auto;padding:1rem 1.5rem}
.cart-item{display:flex;gap:1rem;align-items:center;padding:1rem 0;border-bottom:1px solid rgba(255,255,255,0.05)}
.cart-item img{width:56px;height:56px;border-radius:8px;object-fit:cover;background:var(--surface)}
.cart-item-nome{font-size:.875rem;font-weight:500;line-height:1.3}
.cart-item-preco{font-size:.875rem;color:var(--amber);font-weight:600;margin-top:.25rem}
.cart-item-obs{font-size:.75rem;color:var(--text-s);margin-top:.2rem}
.cart-footer{padding:1.25rem 1.5rem;border-top:1px solid var(--border)}
.cart-linha{display:flex;justify-content:space-between;font-size:.875rem;color:var(--text-m);margin-bottom:.5rem}
.cart-total{display:flex;justify-content:space-between;font-size:1.05rem;font-weight:600;color:var(--text);padding-top:.75rem;border-top:1px solid var(--border);margin-top:.25rem;margin-bottom:1.25rem}
.btn-checkout{
  width:100%;background:var(--amber);color:var(--dark);
  border:none;border-radius:12px;padding:1rem;
  font-family:var(--font-b);font-size:1rem;font-weight:600;cursor:pointer;
  transition:.2s;
}
.btn-checkout:hover{background:var(--amber-d)}
.btn-close{background:none;border:none;color:var(--text-m);cursor:pointer;font-size:1.4rem;padding:.25rem}

/* STATUS TRACKER */
.status-bar{
  display:flex;align-items:center;gap:.5rem;
  background:rgba(76,168,107,0.1);border:1px solid rgba(76,168,107,0.3);
  border-radius:10px;padding:.75rem 1rem;margin-bottom:1.5rem;
}
.status-dot{width:8px;height:8px;border-radius:50%;background:var(--green);animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}

/* RESPONSIVE */
@media(max-width:768px){
  .main-wrap{grid-template-columns:1fr;padding:0 1rem 4rem}
  .sidebar{display:none}
  .hero-inner{grid-template-columns:1fr}
  .cart-drawer{width:100%}
}
</style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo">Bella<span>Cucina</span></div>
  <div class="header-actions">
    <div class="search-bar">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-s)"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" placeholder="Buscar no cardápio..." id="searchInput" oninput="buscarPratos(this.value)">
    </div>
    <a href="/login" class="btn-header btn-login">Entrar</a>
    <button class="btn-header btn-cart" onclick="toggleCart()">
      🛒 Carrinho
      <span class="cart-badge" id="cartBadge">0</span>
    </button>
  </div>
</header>

<!-- HERO -->
<div class="hero">
  <div class="hero-inner">
    <div>
      <h1>Sabor que aquece<br>a <em>alma</em> e encanta<br>o paladar</h1>
      <p>Ingredientes frescos selecionados, receitas autênticas e muito amor em cada prato.</p>
      <div class="hero-meta">
        <div class="meta-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
          Entrega em ~45min
        </div>
        <div class="meta-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
          Raio de 10km
        </div>
        <div class="meta-chip">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
          4.8 (2.3k avaliações)
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN -->
<div class="main-wrap">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <p class="sidebar-title">Categorias</p>
    <a href="#todos" class="cat-item active" onclick="filtrarCategoria('todos', this)">
      <span class="cat-icon">🍽️</span> Todos os pratos <span class="cat-count">24</span>
    </a>
    <a href="#entradas" class="cat-item" onclick="filtrarCategoria('1', this)">
      <span class="cat-icon">🥗</span> Entradas <span class="cat-count">5</span>
    </a>
    <a href="#principais" class="cat-item" onclick="filtrarCategoria('2', this)">
      <span class="cat-icon">🍽️</span> Pratos Principais <span class="cat-count">8</span>
    </a>
    <a href="#massas" class="cat-item" onclick="filtrarCategoria('3', this)">
      <span class="cat-icon">🍝</span> Massas <span class="cat-count">4</span>
    </a>
    <a href="#grelhados" class="cat-item" onclick="filtrarCategoria('4', this)">
      <span class="cat-icon">🥩</span> Grelhados <span class="cat-count">3</span>
    </a>
    <a href="#sobremesas" class="cat-item" onclick="filtrarCategoria('5', this)">
      <span class="cat-icon">🍮</span> Sobremesas <span class="cat-count">2</span>
    </a>
    <a href="#bebidas" class="cat-item" onclick="filtrarCategoria('6', this)">
      <span class="cat-icon">🥤</span> Bebidas <span class="cat-count">4</span>
    </a>
  </aside>

  <!-- CONTEÚDO -->
  <main>
    <!-- DESTAQUES -->
    <p class="section-title">⭐ Destaques do Chef</p>
    <div class="destaques-row" id="destaques">
      <!-- Gerado dinamicamente via Blade/JS -->
      <div class="card-h" onclick="abrirModal({id:1,nome:'Filé ao Molho Madeira',preco:89.90,img:'https://images.unsplash.com/photo-1544025162-d76694265947?w=200&q=80',categoria:'Pratos Principais'})">
        <img class="card-h-img" src="https://images.unsplash.com/photo-1544025162-d76694265947?w=200&q=80" alt="">
        <div class="card-h-body">
          <div class="card-h-badge">⭐ Destaque</div>
          <div class="card-h-nome">Filé ao Molho Madeira</div>
          <div class="card-h-preco">R$ 89,90</div>
        </div>
      </div>
      <div class="card-h" onclick="abrirModal({id:2,nome:'Risoto de Camarão',preco:72.00,img:'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=200&q=80',categoria:'Pratos Principais'})">
        <img class="card-h-img" src="https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=200&q=80" alt="">
        <div class="card-h-body">
          <div class="card-h-badge">🔥 Mais Pedido</div>
          <div class="card-h-nome">Risoto de Camarão</div>
          <div class="card-h-preco">R$ 72,00</div>
        </div>
      </div>
      <div class="card-h" onclick="abrirModal({id:3,nome:'Tiramisù Artesanal',preco:38.00,img:'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=200&q=80',categoria:'Sobremesas'})">
        <img class="card-h-img" src="https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=200&q=80" alt="">
        <div class="card-h-body">
          <div class="card-h-badge">🍮 Sobremesa</div>
          <div class="card-h-nome">Tiramisù Artesanal</div>
          <div class="card-h-preco">R$ 38,00</div>
        </div>
      </div>
    </div>

    <!-- GRID DE PRATOS -->
    <p class="section-title">Todos os Pratos</p>
    <div class="destaques-grid" id="pratosGrid">

      <div class="card" onclick="abrirModal({id:4,nome:'Bruschetta Clássica',preco:32.00,img:'https://images.unsplash.com/photo-1572695157366-5e585ab2b69f?w=400&q=80',categoria:'Entradas',desc:'Pão artesanal grelhado com tomate, manjericão fresco e azeite extra-virgem.',calorias:280,tempo:10})">
        <div class="card-img">
          <img src="https://images.unsplash.com/photo-1572695157366-5e585ab2b69f?w=400&q=80" alt="Bruschetta Clássica">
        </div>
        <div class="card-body">
          <div class="card-cat">Entradas</div>
          <div class="card-nome">Bruschetta Clássica</div>
          <div class="card-desc">Pão artesanal grelhado com tomate, manjericão fresco e azeite extra-virgem.</div>
          <div class="card-info">
            <span class="info-chip">🕐 10 min</span>
            <span class="info-chip">🌿 280 kcal</span>
          </div>
          <div class="card-footer">
            <div class="card-preco"><span class="preco-por">R$ 32,00</span></div>
            <button class="btn-add" onclick="event.stopPropagation();addToCart({id:4,nome:'Bruschetta Clássica',preco:32})">+</button>
          </div>
        </div>
      </div>

      <div class="card" onclick="abrirModal({id:5,nome:'Tagliatelle Bolognese',preco:54.90,img:'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400&q=80',categoria:'Massas',desc:'Massa artesanal com ragú de carne bovina cozido por 6 horas.',calorias:580,tempo:20,promo:44.90})">
        <div class="card-img">
          <img src="https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400&q=80" alt="">
          <div class="card-badge promo">PROMOÇÃO</div>
        </div>
        <div class="card-body">
          <div class="card-cat">Massas</div>
          <div class="card-nome">Tagliatelle Bolognese</div>
          <div class="card-desc">Massa artesanal com ragú de carne bovina cozido lentamente por 6 horas.</div>
          <div class="card-info">
            <span class="info-chip">🕐 20 min</span>
            <span class="info-chip">🌿 580 kcal</span>
          </div>
          <div class="alerg-icons"><span class="alerg">Glúten</span><span class="alerg">Lactose</span></div>
          <div class="card-footer">
            <div class="card-preco">
              <span class="preco-de">R$ 54,90</span>
              <span class="preco-por">R$ 44,90</span>
            </div>
            <button class="btn-add" onclick="event.stopPropagation();addToCart({id:5,nome:'Tagliatelle Bolognese',preco:44.90})">+</button>
          </div>
        </div>
      </div>

      <div class="card" onclick="abrirModal({id:6,nome:'Filé Mignon Grelhado',preco:94.00,img:'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400&q=80',categoria:'Grelhados',desc:'300g de filé mignon grelhado com manteiga de ervas, acompanha batatas rústicas e legumes grelhados.',calorias:720,tempo:25})">
        <div class="card-img">
          <img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400&q=80" alt="">
          <div class="card-badge">🥩 Premium</div>
        </div>
        <div class="card-body">
          <div class="card-cat">Grelhados</div>
          <div class="card-nome">Filé Mignon Grelhado</div>
          <div class="card-desc">300g de filé mignon grelhado com manteiga de ervas, batatas rústicas e legumes.</div>
          <div class="card-info">
            <span class="info-chip">🕐 25 min</span>
            <span class="info-chip">🌿 720 kcal</span>
            <span class="info-chip">🍽️ Serve 1</span>
          </div>
          <div class="card-footer">
            <div class="card-preco"><span class="preco-por">R$ 94,00</span></div>
            <button class="btn-add" onclick="event.stopPropagation();addToCart({id:6,nome:'Filé Mignon Grelhado',preco:94})">+</button>
          </div>
        </div>
      </div>

      <div class="card" onclick="abrirModal({id:7,nome:'Risoto de Cogumelos',preco:62.00,img:'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=400&q=80',categoria:'Pratos Principais',desc:'Arroz arbóreo cremoso com mix de cogumelos frescos, parmesão e azeite trufado.',calorias:510,tempo:30})">
        <div class="card-img"><img src="https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=400&q=80" alt=""></div>
        <div class="card-body">
          <div class="card-cat">Pratos Principais</div>
          <div class="card-nome">Risoto de Cogumelos</div>
          <div class="card-desc">Arroz arbóreo cremoso com mix de cogumelos frescos, parmesão e azeite trufado.</div>
          <div class="card-info"><span class="info-chip">🕐 30 min</span><span class="info-chip">🌿 510 kcal</span></div>
          <div class="alerg-icons"><span class="alerg">Lactose</span></div>
          <div class="card-footer">
            <div class="card-preco"><span class="preco-por">R$ 62,00</span></div>
            <button class="btn-add" onclick="event.stopPropagation();addToCart({id:7,nome:'Risoto de Cogumelos',preco:62})">+</button>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

<!-- MODAL PRATO -->
<div class="overlay" id="overlay" onclick="fecharModal(event)">
  <div class="modal" id="modal">
    <img class="modal-img" id="modalImg" src="" alt="">
    <div class="modal-body">
      <div class="modal-cat" id="modalCat"></div>
      <div class="modal-nome" id="modalNome"></div>
      <div class="modal-desc" id="modalDesc"></div>
      <div class="modal-section" id="modalInfo"></div>
      <div class="modal-section">
        <div class="modal-section-title">Ingredientes</div>
        <div id="modalIngredientes"></div>
      </div>
    </div>
    <div class="modal-footer">
      <div class="qty-ctrl">
        <button class="qty-btn" onclick="mudarQty(-1)">−</button>
        <span class="qty-num" id="modalQty">1</span>
        <button class="qty-btn" onclick="mudarQty(1)">+</button>
      </div>
      <button class="btn-adicionar" id="btnAdicionar" onclick="confirmarAdd()">
        <span>Adicionar ao pedido</span>
        <span id="btnPreco">R$ 0,00</span>
      </button>
    </div>
  </div>
</div>

<!-- CARRINHO LATERAL -->
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-header">
    <h2>🛒 Meu Pedido</h2>
    <button class="btn-close" onclick="toggleCart()">✕</button>
  </div>
  <div class="cart-items" id="cartItems">
    <p style="color:var(--text-s);text-align:center;padding:3rem 1rem">Seu carrinho está vazio.<br>Adicione itens do cardápio!</p>
  </div>
  <div class="cart-footer">
    <div class="cart-linha"><span>Subtotal</span><span id="cartSubtotal">R$ 0,00</span></div>
    <div class="cart-linha"><span>Taxa de entrega</span><span>R$ 8,00</span></div>
    <div class="cart-total"><span>Total</span><span id="cartTotal">R$ 0,00</span></div>
    <button class="btn-checkout" onclick="irCheckout()">Finalizar Pedido →</button>
  </div>
</div>

<script>
// ---- ESTADO ----
let cart = [];
let modalPrato = null;
let modalQtd = 1;

// ---- MODAL ----
function abrirModal(prato) {
  modalPrato = prato;
  modalQtd = 1;
  document.getElementById('modalImg').src = prato.img || '';
  document.getElementById('modalCat').textContent = prato.categoria || '';
  document.getElementById('modalNome').textContent = prato.nome;
  document.getElementById('modalDesc').textContent = prato.desc || '';
  document.getElementById('modalQty').textContent = 1;
  document.getElementById('modalInfo').innerHTML = `
    ${prato.tempo ? `<span style="font-size:.8rem;color:var(--text-s)">🕐 Tempo de preparo: ${prato.tempo} minutos</span><br>` : ''}
    ${prato.calorias ? `<span style="font-size:.8rem;color:var(--text-s)">🌿 ${prato.calorias} kcal</span>` : ''}
  `;
  atualizarBtnPreco();
  document.getElementById('overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function fecharModal(e) {
  if (e.target === document.getElementById('overlay')) {
    document.getElementById('overlay').classList.remove('open');
    document.body.style.overflow = '';
  }
}

function mudarQty(d) {
  modalQtd = Math.max(1, Math.min(20, modalQtd + d));
  document.getElementById('modalQty').textContent = modalQtd;
  atualizarBtnPreco();
}

function atualizarBtnPreco() {
  if (!modalPrato) return;
  const preco = modalPrato.promo || modalPrato.preco;
  document.getElementById('btnPreco').textContent = `R$ ${(preco * modalQtd).toFixed(2).replace('.',',')}`;
}

function confirmarAdd() {
  if (!modalPrato) return;
  addToCart(modalPrato, modalQtd);
  document.getElementById('overlay').classList.remove('open');
  document.body.style.overflow = '';
}

// ---- CARRINHO ----
function addToCart(prato, qty = 1) {
  const preco = prato.promo || prato.preco;
  const idx = cart.findIndex(i => i.id === prato.id);
  if (idx >= 0) cart[idx].qty += qty;
  else cart.push({...prato, preco, qty});
  renderCart();
  showToast(`${prato.nome} adicionado!`);
}

function renderCart() {
  const badge = document.getElementById('cartBadge');
  const total = cart.reduce((s, i) => s + i.qty, 0);
  badge.textContent = total;

  const container = document.getElementById('cartItems');
  if (!cart.length) {
    container.innerHTML = '<p style="color:var(--text-s);text-align:center;padding:3rem 1rem">Seu carrinho está vazio.<br>Adicione itens do cardápio!</p>';
  } else {
    container.innerHTML = cart.map((item, i) => `
      <div class="cart-item">
        <img src="${item.img || 'https://via.placeholder.com/56'}" alt="">
        <div style="flex:1">
          <div class="cart-item-nome">${item.nome}</div>
          <div class="cart-item-preco">R$ ${(item.preco * item.qty).toFixed(2).replace('.',',')}</div>
        </div>
        <div class="qty-ctrl" style="gap:.5rem;padding:.15rem .4rem">
          <button class="qty-btn" style="font-size:1rem" onclick="mudarCartQty(${i},-1)">−</button>
          <span class="qty-num" style="font-size:.875rem">${item.qty}</span>
          <button class="qty-btn" style="font-size:1rem" onclick="mudarCartQty(${i},1)">+</button>
        </div>
      </div>
    `).join('');
  }

  const subtotal = cart.reduce((s, i) => s + i.preco * i.qty, 0);
  const entrega = cart.length ? 8 : 0;
  document.getElementById('cartSubtotal').textContent = `R$ ${subtotal.toFixed(2).replace('.',',')}`;
  document.getElementById('cartTotal').textContent = `R$ ${(subtotal + entrega).toFixed(2).replace('.',',')}`;
}

function mudarCartQty(idx, d) {
  cart[idx].qty += d;
  if (cart[idx].qty <= 0) cart.splice(idx, 1);
  renderCart();
}

function toggleCart() {
  document.getElementById('cartDrawer').classList.toggle('open');
}

function irCheckout() {
  if (!cart.length) { showToast('Adicione itens antes!'); return; }
  localStorage.setItem('cart', JSON.stringify(cart));
  window.location.href = '/checkout';
}

// ---- FILTRO ----
function filtrarCategoria(id, el) {
  document.querySelectorAll('.cat-item').forEach(e => e.classList.remove('active'));
  el.classList.add('active');
  // Em produção, re-filtrar com AJAX ou mostrar/ocultar cards
}

function buscarPratos(termo) {
  if (termo.length < 2) return;
  // Em produção: fetch('/cardapio/buscar?q=' + termo)
}

// ---- TOAST ----
function showToast(msg) {
  const t = document.createElement('div');
  t.style.cssText = `
    position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);
    background:var(--surface);color:var(--text);
    padding:.75rem 1.5rem;border-radius:10px;
    border:1px solid var(--border);font-size:.875rem;z-index:999;
    animation:fadeup .3s ease;
  `;
  t.textContent = '✓ ' + msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 2500);
}

const style = document.createElement('style');
style.textContent = '@keyframes fadeup{from{opacity:0;transform:translateX(-50%) translateY(10px)}to{opacity:1;transform:translateX(-50%) translateY(0)}}';
document.head.appendChild(style);

// Init
renderCart();
</script>
</body>
</html>