<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'Cozinha - '.config('app.name', 'Restaurante') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@include('layouts.partials.restaurant-theme')
<style>
:root{
  --bg:#eef4ff;
  --panel:#ffffff;
  --line:rgba(15,23,42,.08);
  --text:#0f172a;
  --muted:#64748b;
  --navy:var(--color-secondary);
  --navy-dark:var(--color-secondary-dark);
  --amber:#d97706;
  --green:#059669;
  --red:#dc2626;
  --mono:'IBM Plex Mono',monospace;
  --sans:'DM Sans',sans-serif;
  --display:'Space Grotesk',sans-serif;
  --shadow:0 20px 40px rgba(15,23,42,.08);
}
*{box-sizing:border-box;margin:0;padding:0}
body{background:linear-gradient(180deg,var(--bg) 0%,#f8fafc 26%,#f8fafc 100%);color:var(--text);font-family:var(--sans);min-height:100vh}
.shell{display:grid;grid-template-columns:280px minmax(0,1fr);min-height:100vh}
.sidebar{
  background:linear-gradient(180deg,var(--surface-main) 0%,var(--surface-soft) 100%);
  color:var(--text-main);
  padding:22px 18px;
  position:sticky;
  top:0;
  height:100vh;
  display:flex;
  flex-direction:column;
  border-right:1px solid var(--color-secondary-border);
  box-shadow:var(--shadow-soft);
}
.brand{display:flex;gap:14px;align-items:center;margin-bottom:24px}
.brand-mark{
  width:68px;
  height:68px;
  border-radius:20px;
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:10px;
  border:1px solid var(--color-secondary-border);
  box-shadow:0 12px 24px rgba(15,23,42,.08);
  flex-shrink:0;
  overflow:hidden;
}
.brand-mark img{width:100%;height:100%;object-fit:contain;display:block}
.brand-mark .brand-fallback{
  color:var(--color-secondary);
  font-size:1rem;
  font-weight:700;
  text-align:center;
  line-height:1.1;
}
.brand-copy{min-width:0}
.brand-title{font-family:var(--display);font-size:1.2rem;font-weight:700;color:var(--text-main)}
.brand-sub{font-size:.83rem;color:var(--muted);margin-top:2px}
.sidebar-clock{
  font-family:var(--mono);
  font-size:1.15rem;
  font-weight:600;
  padding:14px 16px;
  border-radius:18px;
  background:var(--surface-accent);
  border:1px solid var(--color-secondary-border);
  color:var(--color-secondary-dark);
  margin-bottom:20px
}
.sidebar-nav{display:flex;flex-direction:column;gap:10px}
.nav-link{
  display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:18px;
  color:var(--text-muted);text-decoration:none;font-weight:700;transition:.2s;border:1px solid transparent
}
.nav-link:hover{background:var(--surface-accent);border-color:var(--color-secondary-border);color:var(--color-secondary-dark)}
.nav-link.active{
  background:linear-gradient(135deg,color-mix(in srgb, var(--color-secondary) 18%, white 82%),color-mix(in srgb, var(--color-secondary) 10%, white 90%));
  border-color:var(--color-secondary-border);
  color:var(--color-secondary-dark)
}
.nav-link i{width:18px;text-align:center}
.logout-form{margin-top:10px}
.logout-btn{
  width:100%;display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:18px;
  background:#fff;border:1px solid rgba(220,38,38,.18);color:#b91c1c;font-weight:700;font-family:var(--sans);cursor:pointer
}
.logout-btn:hover{background:rgba(220,38,38,.06)}
.logout-btn i{width:18px;text-align:center}
.sidebar-note{
  margin-top:auto;padding:16px;border-radius:18px;background:var(--surface-accent);
  border:1px solid var(--color-secondary-border);font-size:.85rem;line-height:1.55;color:var(--muted)
}
.content{padding:22px}
@media(max-width:1024px){
  .shell{grid-template-columns:1fr}
  .sidebar{position:relative;height:auto}
  .sidebar-note{margin-top:18px}
}
</style>
</head>
<body>
<div class="shell">
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">
        @if(($restaurantConfig->logo_url ?? null))
          <img src="{{ $restaurantConfig->logo_url }}" alt="Logo do restaurante">
        @else
          <div class="brand-fallback">{{ config('app.name', 'Restaurante') }}</div>
        @endif
      </div>
      <div class="brand-copy">
        <div class="brand-title">{{ config('app.name', 'Restaurante') }}</div>
        <div class="brand-sub">Operacao da cozinha</div>
      </div>
    </div>

    <div class="sidebar-clock" id="sidebarClock">00:00:00</div>

    <nav class="sidebar-nav">
      <a href="{{ route('cozinha.fila') }}" class="nav-link {{ request()->routeIs('cozinha.fila') ? 'active' : '' }}">
        <i class="fas fa-list-check"></i>
        <span>Fila da Cozinha</span>
      </a>
      <a href="{{ route('cozinha.dashboard') }}" class="nav-link {{ request()->routeIs('cozinha.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
      </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="logout-form">
      @csrf
      <button type="submit" class="logout-btn">
        <i class="fas fa-right-from-bracket"></i>
        <span>Logout</span>
      </button>
    </form>

    <div class="sidebar-note">
      Esta area concentra a fila operacional e os indicadores do dia. Os pedidos entram aqui a partir do banco e do fluxo real do checkout.
    </div>
  </aside>

  <main class="content">
