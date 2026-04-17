<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ativar 2FA — {{ config('app.name', 'Restaurante') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @include('layouts.partials.restaurant-theme')
    <style>
        :root {
            --brand: #1e3a8a;
            --brand-d: #1e40af;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text: #0f172a;
            --text-m: #64748b;
            --text-s: #94a3b8;
            --border: rgba(30, 58, 138, 0.10);
            --green: #059669;
            --yellow: #d97706;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); font-family: 'DM Sans', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }

        .setup-container { width: 100%; max-width: 520px; }

        .setup-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 36px 32px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
        }

        .setup-header { text-align: center; margin-bottom: 28px; }
        .setup-icon {
            width: 64px; height: 64px; border-radius: 20px;
            background: linear-gradient(135deg, var(--brand), var(--brand-d));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; color: #fff; font-size: 1.5rem;
            box-shadow: 0 4px 16px rgba(30,58,138,0.25);
        }
        .setup-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; color: var(--text); margin-bottom: 6px;
        }
        .setup-header p { color: var(--text-m); font-size: 0.88rem; line-height: 1.5; }

        /* Steps */
        .step { margin-bottom: 24px; }
        .step-label {
            font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1.2px; color: var(--text-s); margin-bottom: 10px;
            display: flex; align-items: center; gap: 8px;
        }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%;
            background: var(--brand); color: #fff; font-size: 0.65rem;
            display: flex; align-items: center; justify-content: center; font-weight: 800;
        }

        /* QR Code */
        .qr-container {
            background: #fff; border: 2px dashed rgba(30,58,138,0.15);
            border-radius: 20px; padding: 24px; text-align: center;
        }
        .qr-code-wrapper {
            display: inline-flex; background: #fff; padding: 12px;
            border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .qr-code-wrapper svg { width: 200px; height: 200px; }
        .qr-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 16px 0; color: var(--text-s); font-size: 0.78rem; font-weight: 600;
        }
        .qr-divider::before, .qr-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* Manual Key */
        .manual-key {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border: 1px solid var(--border); border-radius: 14px;
            padding: 14px 16px; display: flex; align-items: center;
            justify-content: space-between; gap: 10px;
        }
        .manual-key code {
            font-family: 'Courier New', monospace; font-size: 0.95rem;
            font-weight: 700; color: var(--text); letter-spacing: 2px;
            word-break: break-all;
        }
        .copy-btn {
            background: var(--brand); color: #fff; border: none;
            border-radius: 10px; padding: 8px 14px; cursor: pointer;
            font-size: 0.78rem; font-weight: 700; white-space: nowrap;
            transition: opacity 0.2s;
        }
        .copy-btn:hover { opacity: 0.85; }
        .copy-btn.copied { background: var(--green); }

        /* Recovery */
        .recovery-box {
            background: #fffbeb; border: 1px solid rgba(217,119,6,0.2);
            border-radius: 16px; padding: 16px;
        }
        .recovery-title {
            font-weight: 800; font-size: 0.85rem; color: #92400e;
            margin-bottom: 4px; display: flex; align-items: center; gap: 8px;
        }
        .recovery-subtitle { font-size: 0.78rem; color: #a16207; margin-bottom: 12px; }
        .recovery-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 6px;
            list-style: none; padding: 0;
        }
        .recovery-grid li {
            background: rgba(255,255,255,0.7); border: 1px solid rgba(217,119,6,0.15);
            border-radius: 8px; padding: 8px 10px; font-family: 'Courier New', monospace;
            font-size: 0.75rem; color: #92400e; font-weight: 600; text-align: center;
        }

        /* Confirm Form */
        .confirm-form { margin-top: 24px; }
        .form-label {
            font-size: 0.75rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.06em; color: var(--text-m); margin-bottom: 8px; display: block;
        }
        .form-input {
            width: 100%; background: #f1f5f9; border: 1.5px solid var(--border);
            border-radius: 16px; padding: 16px; font-size: 1.2rem;
            font-family: 'Courier New', monospace; text-align: center;
            letter-spacing: 8px; font-weight: 700; color: var(--text);
            outline: none; transition: border-color 0.2s;
        }
        .form-input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(30,58,138,0.1); }
        .form-input::placeholder { letter-spacing: 4px; font-size: 0.9rem; color: var(--text-s); }

        .form-error { color: #dc2626; font-size: 0.82rem; margin-top: 8px; font-weight: 600; }

        .submit-btn {
            width: 100%; margin-top: 16px; padding: 16px;
            background: linear-gradient(135deg, var(--brand), var(--brand-d));
            color: #fff; border: none; border-radius: 16px; font-weight: 800;
            font-size: 0.95rem; cursor: pointer; transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 16px rgba(30,58,138,0.2);
        }
        .submit-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(30,58,138,0.3); }
        .submit-btn:active { transform: translateY(0); }

        .cancel-link {
            display: block; text-align: center; margin-top: 14px;
            color: var(--text-m); font-size: 0.85rem; text-decoration: none; font-weight: 500;
        }
        .cancel-link:hover { color: var(--text); }

        @media (max-width: 480px) {
            .setup-card { padding: 28px 20px; border-radius: 22px; }
            .qr-code-wrapper svg { width: 160px; height: 160px; }
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-card">
            <!-- Header -->
            <div class="setup-header">
                <div class="setup-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1>Ativar Autenticação 2FA</h1>
                <p>Proteja sua conta com o Google Authenticator.<br>Escaneie o QR Code ou digite o código manual no app.</p>
            </div>

            @if (session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; color: #dc2626; font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Step 1: QR Code -->
            <div class="step">
                <div class="step-label">
                    <span class="step-num">1</span> Escaneie o QR Code no app
                </div>
                <div class="qr-container">
                    <div class="qr-code-wrapper">
                        {!! $qrCodeSvg !!}
                    </div>

                    <div class="qr-divider">ou digite o código manualmente</div>

                    <div class="manual-key">
                        <code id="secret-key">{{ $secret }}</code>
                        <button type="button" class="copy-btn" id="copy-key-btn" onclick="copyKey()">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Recovery Codes -->
            <div class="step">
                <div class="step-label">
                    <span class="step-num">2</span> Salve os códigos de recuperação
                </div>
                <div class="recovery-box">
                    <div class="recovery-title">
                        <i class="fas fa-exclamation-triangle"></i> Códigos de emergência
                    </div>
                    <div class="recovery-subtitle">
                        Guarde em local seguro. Use se perder acesso ao autenticador.
                    </div>
                    <ul class="recovery-grid">
                        @foreach($recovery as $code)
                            <li>{{ $code }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Step 3: Confirm -->
            <div class="step">
                <div class="step-label">
                    <span class="step-num">3</span> Confirme com o código do app
                </div>
                <form method="POST" action="{{ route('2fa.confirm') }}" class="confirm-form">
                    @csrf
                    <label class="form-label" for="code">Código de 6 dígitos</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        class="form-input"
                        placeholder="000000"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        autocomplete="one-time-code"
                        autofocus
                        required
                    >
                    @error('code')
                        <div class="form-error"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                    @enderror

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-check-circle"></i> Ativar Proteção 2FA
                    </button>
                </form>

                <a href="{{ route('cliente.configuracoes') }}" class="cancel-link">
                    <i class="fas fa-arrow-left"></i> Cancelar e voltar
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyKey() {
            const key = document.getElementById('secret-key').textContent;
            const btn = document.getElementById('copy-key-btn');
            navigator.clipboard.writeText(key).then(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    btn.classList.remove('copied');
                }, 2000);
            });
        }

        // Auto-submit when 6 digits are entered
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 6) {
                // Small delay for UX
                setTimeout(() => this.closest('form').submit(), 300);
            }
        });
    </script>
</body>
</html>
