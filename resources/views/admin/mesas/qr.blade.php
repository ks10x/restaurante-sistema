<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code — Mesa {{ $mesa->numero }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #0f172a;
            --accent: #3b82f6;
            --bg: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .no-print-zone {
            margin-bottom: 40px;
            text-align: center;
        }
        .btn-print {
            background: var(--brand);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); background: #000; }
        
        .ticket {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 50px 40px;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
            text-align: center;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }
        .logo-placeholder {
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--brand);
            margin-bottom: 30px;
            font-size: 1.2rem;
            text-transform: uppercase;
        }
        h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 10px;
            color: var(--brand);
        }
        .mesa-label {
            display: inline-block;
            background: var(--bg);
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .instruction {
            font-size: 16px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 35px;
            font-weight: 500;
        }
        .qr-wrapper {
            background: white;
            padding: 20px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            display: inline-block;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .qr-wrapper svg {
            width: 100%;
            max-width: 220px;
            height: auto;
        }
        .footer-text {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media print {
            body { background: white; min-height: auto; padding: 0; }
            .no-print-zone { display: none; }
            .ticket { box-shadow: none; border: 2px solid #000; max-width: 100%; border-radius: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print-zone">
        <button class="btn-print" onclick="window.print()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            IMPRIMIR CARDÁPIO
        </button>
        <p style="margin-top: 15px; font-size: 13px; color: #64748b; font-weight: 500;">Dica: Use papel fotográfico ou gramatura maior para melhor qualidade.</p>
    </div>

    <div class="ticket">
        <div class="logo-placeholder">{{ config('app.name') }}</div>
        
        <span class="mesa-label">Espaço Reservado</span>
        <h1>MESA {{ $mesa->numero }}</h1>
        
        <p class="instruction">Aponte a câmera para pedir <br>e aproveitar sua refeição.</p>
        
        <div class="qr-wrapper">
            {!! $svg !!}
        </div>

        <div class="footer-text">
            Digital Menu • {{ date('Y') }}
        </div>
    </div>

</body>
</html>
