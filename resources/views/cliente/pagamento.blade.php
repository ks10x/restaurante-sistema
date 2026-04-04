<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX — Bella Cucina</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>

    <style>
        :root {
            --navy: #1E3A8A; --navy-d: #1E40AF; --navy-l: #3B82F6;
            --bg: #F8FAFC; --surface: #FFFFFF; --surface2: #F1F5F9;
            --text: #0F172A; --text-m: #64748B; --text-s: #94A3B8;
            --border: rgba(30,58,138,0.12); --green: #10B981; --green-bg: #ECFDF5;
            --amber: #F59E0B; --red: #EF4444;
            --radius: 16px; --font-h: 'Playfair Display', serif; --font-b: 'DM Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: var(--font-b); min-height: 100vh; display: flex; flex-direction: column; align-items: center; }

        .pix-header {
            width: 100%; background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border); padding: 0 2rem;
            display: flex; align-items: center; justify-content: center; height: 72px;
        }
        .logo { font-family: var(--font-h); font-size: 1.5rem; color: var(--navy); }
        .logo span { color: var(--text-m); font-weight: 400; font-size: 1.1rem; }

        .pix-container { max-width: 480px; width: 100%; padding: 2rem; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px;
            border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;
        }
        .status-badge.pending { background: #FEF3C7; color: #92400E; }
        .status-badge.paid { background: var(--green-bg); color: var(--green); }
        .status-badge .dot { width: 8px; height: 8px; border-radius: 50%; animation: pulse 1.5s infinite; }
        .status-badge.pending .dot { background: var(--amber); }
        .status-badge.paid .dot { background: var(--green); animation: none; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        .pix-card {
            background: var(--surface); border: 1px solid var(--border); border-radius: 20px;
            padding: 2rem; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }

        .pix-title { font-family: var(--font-h); font-size: 1.5rem; margin-bottom: 0.5rem; }
        .pix-subtitle { color: var(--text-m); font-size: 0.9rem; margin-bottom: 1.5rem; }

        .qr-wrapper {
            background: #fff; border: 3px solid var(--border); border-radius: 16px;
            padding: 1.5rem; display: inline-block; margin-bottom: 1.5rem;
        }
        .qr-wrapper img { width: 220px; height: 220px; }
        .qr-wrapper canvas { border-radius: 4px; }

        .timer { font-size: 1.2rem; font-weight: 800; color: var(--navy); margin-bottom: 1rem; }
        .timer span { font-weight: 400; color: var(--text-m); font-size: 0.85rem; }
        .timer.expired { color: var(--red); }

        .copy-section { margin-top: 1rem; }
        .copy-code {
            background: var(--surface2); border: 1px solid var(--border); border-radius: 12px;
            padding: 12px 16px; font-size: 0.75rem; word-break: break-all; color: var(--text-m);
            line-height: 1.4; max-height: 60px; overflow: hidden; text-align: left;
        }
        .btn-copy {
            width: 100%; margin-top: 10px; background: var(--navy); color: #fff;
            border: none; padding: 14px; border-radius: 12px; font-weight: 700;
            font-size: 0.95rem; cursor: pointer; transition: 0.2s; display: flex;
            align-items: center; justify-content: center; gap: 8px;
        }
        .btn-copy:hover { background: var(--navy-d); }
        .btn-copy.copied { background: var(--green); }

        .order-info {
            margin-top: 1.5rem; background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.2rem;
        }
        .order-info-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 0.9rem; }
        .order-info-label { color: var(--text-m); }
        .order-info-value { font-weight: 700; color: var(--navy); }

        .paid-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6);
            z-index: 100; align-items: center; justify-content: center;
        }
        .paid-overlay.show { display: flex; }
        .paid-card {
            background: #fff; border-radius: 24px; padding: 2.5rem; text-align: center;
            max-width: 400px; width: 90%; animation: pop 0.4s ease;
        }
        @keyframes pop { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .paid-icon { width: 80px; height: 80px; background: var(--green-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .paid-icon i { font-size: 2rem; color: var(--green); }
        .btn-track {
            display: inline-block; margin-top: 1rem; background: var(--navy); color: #fff;
            padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: 0.2s;
        }
        .btn-track:hover { background: var(--navy-d); }
    </style>
</head>
<body>

<div class="pix-header">
    <div class="logo">Bella<span>Cucina</span></div>
</div>

<div class="pix-container">
    <div style="text-align: center;">
        <div id="statusBadge" class="status-badge pending">
            <span class="dot"></span> Aguardando Pagamento
        </div>
    </div>

    <div class="pix-card">
        <h1 class="pix-title">Pagamento via PIX</h1>
        <p class="pix-subtitle">Escaneie o QR Code ou copie o cÃ³digo abaixo</p>

        <div class="qr-wrapper" id="qrWrapper">
            <canvas id="qrCanvas"></canvas>
        </div>

        <div class="timer" id="timer">
            <span>Expira em</span><br>
            <span id="timerValue">59:59</span>
        </div>

        <div class="copy-section">
            <div class="copy-code" id="pixCode">Carregando...</div>
            <button class="btn-copy" id="btnCopy">
                <i class="fas fa-copy"></i> Copiar CÃ³digo PIX
            </button>
        </div>
    </div>

    <div class="order-info">
        <div class="order-info-row">
            <span class="order-info-label">Pedido</span>
            <span class="order-info-value">#{{ $pedido->codigo }}</span>
        </div>
        <div class="order-info-row">
            <span class="order-info-label">Valor</span>
            <span class="order-info-value">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
        </div>
        <div class="order-info-row">
            <span class="order-info-label">Status</span>
            <span class="order-info-value" id="statusText">Aguardando</span>
        </div>
    </div>
</div>

<!-- PAID OVERLAY -->
<div class="paid-overlay" id="paidOverlay">
    <div class="paid-card">
        <div class="paid-icon"><i class="fas fa-check"></i></div>
        <h2 style="font-family: var(--font-h); font-size: 1.5rem; margin-bottom: 0.5rem;">Pagamento Confirmado!</h2>
        <p style="color: var(--text-m);">Seu pedido jÃ¡ estÃ¡ sendo preparado.</p>
        <a href="{{ route('cliente.pedido.acompanhar', $pedido->codigo) }}" class="btn-track">
            <i class="fas fa-route" style="margin-right: 8px;"></i> Acompanhar Pedido
        </a>
    </div>
</div>

<!-- QRCode.js library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Try to get PIX data from localStorage (set by checkout) or use server data
    let pixData = JSON.parse(localStorage.getItem('bellacucina_pix') || 'null');
    const pixCode = pixData?.qr_code || '{{ $pixCode ?? "" }}';
    const expiresAt = pixData?.expires_at || '{{ $expiresAt ?? "" }}';
    const pedidoCodigo = '{{ $pedido->codigo }}';
    const statusApiUrl = '{{ route("cliente.pedido.status-api", $pedido->codigo) }}';
    const acompanharUrl = '{{ route("cliente.pedido.acompanhar", $pedido->codigo) }}';

    // Clean up localStorage pix data
    localStorage.removeItem('bellacucina_pix');

    // Render QR Code
    if (pixCode) {
        document.getElementById('pixCode').textContent = pixCode;
        const canvas = document.getElementById('qrCanvas');
        QRCode.toCanvas(canvas, pixCode, { width: 220, margin: 2, color: { dark: '#0F172A', light: '#FFFFFF' } }, function(err) {
            if (err) {
                console.error('QR Code error:', err);
                document.getElementById('qrWrapper').innerHTML = '<p style="padding: 2rem; color: var(--text-m);">Use o cÃ³digo copia e cola abaixo</p>';
            }
        });
    }

    // Copy button
    document.getElementById('btnCopy')?.addEventListener('click', function() {
        navigator.clipboard.writeText(pixCode).then(() => {
            this.classList.add('copied');
            this.innerHTML = '<i class="fas fa-check"></i> Copiado!';
            setTimeout(() => {
                this.classList.remove('copied');
                this.innerHTML = '<i class="fas fa-copy"></i> Copiar CÃ³digo PIX';
            }, 3000);
        });
    });

    // Timer
    let expiry = expiresAt ? new Date(expiresAt) : new Date(Date.now() + 3600000);
    const timerEl = document.getElementById('timerValue');
    const timerWrap = document.getElementById('timer');

    function updateTimer() {
        const now = Date.now();
        const diff = Math.max(0, expiry - now);
        if (diff <= 0) {
            timerWrap.classList.add('expired');
            timerEl.textContent = 'Expirado';
            return;
        }
        const mins = Math.floor(diff / 60000);
        const secs = Math.floor((diff % 60000) / 1000);
        timerEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }
    updateTimer();
    setInterval(updateTimer, 1000);

    // Polling for payment confirmation
    let pollInterval = setInterval(() => {
        fetch(statusApiUrl, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data.status !== 'aguardando_pagamento') {
                    clearInterval(pollInterval);
                    document.getElementById('statusBadge').className = 'status-badge paid';
                    document.getElementById('statusBadge').innerHTML = '<span class="dot"></span> Pagamento Confirmado';
                    document.getElementById('statusText').textContent = 'Confirmado';
                    document.getElementById('paidOverlay').classList.add('show');
                }
            })
            .catch(() => {});
    }, 5000);
});
</script>

</body>
</html>
