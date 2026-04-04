<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacidade (LGPD) — Bella Cucina</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>
    <style>
        :root {
            --brand: #1e3a8a;
            --brand-d: #1e40af;
            --bg: #f8fafc;
            --bg2: #f1f5f9;
            --surface: #ffffff;
            --text: #0f172a;
            --text-m: #64748b;
            --text-s: #94a3b8;
            --border: rgba(30, 58, 138, 0.10);
            --red: #dc2626;
            --green: #059669;
            --amber: #d97706;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: 'DM Sans', sans-serif; min-height: 100vh; }
        .container { max-width: 760px; margin: 0 auto; padding: 40px 20px 80px; }
        .back-link { color: var(--brand); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 24px; font-weight: 600; font-size: 0.92rem; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 18px; margin-bottom: 16px; }
        h1 { font-family: 'Playfair Display', serif; font-size: 1.6rem; }
        .muted { color: var(--text-m); font-size: 0.9rem; margin-top: 6px; }

        .row { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 14px 0; border-top: 1px solid var(--border); }
        .row:first-child { border-top: none; }
        .row-title { font-weight: 700; }
        .row-sub { font-size: 0.82rem; color: var(--text-s); margin-top: 2px; }

        .switch { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .switch input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .track { width: 44px; height: 24px; border-radius: 12px; background: #cbd5e1; position: relative; transition: background .2s; flex-shrink: 0; }
        .thumb { width: 20px; height: 20px; background: #fff; border-radius: 50%; position: absolute; top: 2px; left: 2px; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.15); }
        .switch input:checked + .track { background: var(--brand); }
        .switch input:checked + .track .thumb { transform: translateX(20px); }
        .btn { border: 1px solid var(--border); background: var(--surface); border-radius: 12px; padding: 10px 14px; font-weight: 700; cursor: pointer; }
        .btn-primary { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-danger { background: rgba(220,38,38,.08); border-color: rgba(220,38,38,.25); color: var(--red); }
        .btn:hover { filter: brightness(.98); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }

        .field { display: flex; flex-direction: column; gap: 6px; margin-top: 10px; }
        label { font-size: 0.8rem; color: var(--text-m); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
        input[type="password"] { border: 1px solid var(--border); background: var(--bg); border-radius: 12px; padding: 12px 14px; }
        .help { font-size: 0.82rem; color: var(--text-s); margin-top: 8px; }
        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 999px; border: 1px solid rgba(0,0,0,0.08); }
        .badge-ok { color: var(--green); background: rgba(5,150,105,.08); border-color: rgba(5,150,105,.18); }
        .badge-warn { color: var(--amber); background: rgba(217,119,6,.08); border-color: rgba(217,119,6,.18); }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('cliente.configuracoes') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar para Minha Conta
    </a>

    <div class="card">
        <h1>Privacidade e LGPD</h1>
        <div class="muted">
            Aqui você gerencia consentimentos, exporta seus dados e pode solicitar a anonimização da conta, conforme a LGPD.
        </div>
        <div class="help">
            Controlador dos dados: <strong>Bella Cucina</strong>. Finalidades: processamento de pedidos, entregas, suporte e melhoria do serviço.
        </div>
    </div>

    <form method="POST" action="{{ route('lgpd.consent') }}" class="card">
        @csrf
        <div class="row">
            <div>
                <div class="row-title">Marketing e Comunicações</div>
                <div class="row-sub">Receber promoções, novidades e comunicados (e-mail/WhatsApp).</div>
            </div>
            <label class="switch" aria-label="Consentimento de marketing">
                <input type="checkbox" name="marketing" value="1" @checked((bool)($consentimentos->marketing ?? false))>
                <span class="track" aria-hidden="true"><span class="thumb"></span></span>
            </label>
        </div>

        <div class="row">
            <div>
                <div class="row-title">Compartilhamento com Terceiros</div>
                <div class="row-sub">Compartilhar dados com parceiros além do necessário para operar o pedido.</div>
            </div>
            <label class="switch" aria-label="Compartilhamento com terceiros">
                <input type="checkbox" name="terceiros" value="1" @checked((bool)($consentimentos->compartilhamento_terceiros ?? false))>
                <span class="track" aria-hidden="true"><span class="thumb"></span></span>
            </label>
        </div>

        <div class="row">
            <div>
                <div class="row-title">Cookies Analíticos</div>
                <div class="row-sub">Permitir coleta analítica para melhorar a experiência.</div>
            </div>
            <label class="switch" aria-label="Cookies analíticos">
                <input type="checkbox" name="analiticos" value="1" @checked((bool)($consentimentos->cookies_analiticos ?? false))>
                <span class="track" aria-hidden="true"><span class="thumb"></span></span>
            </label>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:10px">
            <button type="submit" class="btn btn-primary">Salvar preferências</button>
        </div>

        <div class="help">
            Você pode alterar seus consentimentos a qualquer momento. Ao salvar, registramos data/hora e IP para auditoria.
        </div>
    </form>

    <div class="grid">
        <div class="card">
            <div class="row" style="padding-top:0">
                <div>
                    <div class="row-title">Exportar meus dados</div>
                    <div class="row-sub">Baixar um arquivo JSON com dados do perfil e pedidos.</div>
                </div>
                <a class="btn" href="{{ route('lgpd.export') }}">Exportar</a>
            </div>
            <div class="help">
                Para segurança, exportamos apenas os seus próprios dados autenticados.
            </div>
        </div>

        <div class="card">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                <div>
                    <div class="row-title">Status do consentimento</div>
                    <div class="row-sub">Última atualização</div>
                </div>
                @if(isset($consentimentos) && $consentimentos?->accepted_at)
                    <span class="badge badge-ok">Atualizado</span>
                @else
                    <span class="badge badge-warn">Pendente</span>
                @endif
            </div>
            <div class="help">
                {{ $consentimentos?->accepted_at ? 'Atualizado em ' . $consentimentos->accepted_at->format('d/m/Y H:i') : 'Você ainda não definiu suas preferências de privacidade.' }}
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('lgpd.anonymize') }}" class="card" onsubmit="return confirm('Tem certeza? Essa ação é irreversível e anonimiza seus dados.')">
        @csrf
        @method('DELETE')
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px">
            <div>
                <div class="row-title" style="color:var(--red)">Anonimizar minha conta</div>
                <div class="row-sub">Remove/anonimiza dados pessoais, mantendo consistência de métricas.</div>
            </div>
            <button type="submit" class="btn btn-danger">Anonimizar</button>
        </div>
        <div class="field">
            <label for="lgpd_password">Confirme sua senha</label>
            <input id="lgpd_password" type="password" name="password" required autocomplete="current-password" placeholder="Digite sua senha para confirmar">
        </div>
        <div class="help">
            Base legal: cumprimento de obrigação legal e exercício regular de direitos (quando aplicável), e execução de contrato para pedidos.
        </div>
    </form>

</div>

</body>
</html>
