<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação 2FA — {{ config('app.name', 'Restaurante') }}</title>
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
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--bg); font-family: 'DM Sans', sans-serif;
            min-height: 100vh; display: flex; align-items: center;
            justify-content: center; padding: 20px;
        }

        .verify-container { width: 100%; max-width: 440px; }

        .verify-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 28px; padding: 40px 32px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06); text-align: center;
        }

        .verify-icon {
            width: 80px; height: 80px; border-radius: 24px;
            background: linear-gradient(135deg, var(--brand), var(--brand-d));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; color: #fff; font-size: 2rem;
            box-shadow: 0 6px 24px rgba(30,58,138,0.25);
            animation: pulse-glow 2s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 6px 24px rgba(30,58,138,0.25); }
            50% { box-shadow: 0 6px 32px rgba(30,58,138,0.4); }
        }

        .verify-card h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; color: var(--text); margin-bottom: 8px;
        }
        .verify-card .subtitle {
            color: var(--text-m); font-size: 0.88rem; line-height: 1.6; margin-bottom: 28px;
        }

        /* Alert boxes */
        .alert-warning {
            background: #fffbeb; border: 1px solid rgba(217,119,6,0.2);
            border-radius: 14px; padding: 12px 16px; margin-bottom: 20px;
            color: #92400e; font-size: 0.82rem; font-weight: 600;
            text-align: left;
        }
        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 14px; padding: 12px 16px; margin-bottom: 20px;
            color: #dc2626; font-size: 0.82rem; font-weight: 600;
            text-align: left;
        }

        /* Code inputs */
        .code-input-wrapper { margin-bottom: 24px; }
        .form-label {
            font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--text-m); margin-bottom: 10px;
            display: block; text-align: left;
        }
        .code-input {
            width: 100%; background: #f1f5f9; border: 2px solid var(--border);
            border-radius: 18px; padding: 18px; font-size: 1.6rem;
            font-family: 'Courier New', monospace; text-align: center;
            letter-spacing: 12px; font-weight: 800; color: var(--text);
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .code-input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(30,58,138,0.1);
        }
        .code-input::placeholder {
            letter-spacing: 6px; font-size: 1.2rem; color: var(--text-s);
        }

        .submit-btn {
            width: 100%; padding: 16px;
            background: linear-gradient(135deg, var(--brand), var(--brand-d));
            color: #fff; border: none; border-radius: 16px; font-weight: 800;
            font-size: 0.95rem; cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 16px rgba(30,58,138,0.2);
        }
        .submit-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(30,58,138,0.3); }
        .submit-btn:active { transform: translateY(0); }

        /* Recovery toggle */
        .recovery-toggle {
            margin-top: 20px; padding-top: 20px;
            border-top: 1px solid var(--border);
        }
        .recovery-btn {
            background: none; border: none; color: var(--text-m);
            font-size: 0.82rem; cursor: pointer; font-weight: 600;
            transition: color 0.2s;
        }
        .recovery-btn:hover { color: var(--brand); }

        .recovery-section {
            display: none; margin-top: 16px; text-align: left;
        }
        .recovery-section.show { display: block; }

        .recovery-input {
            width: 100%; background: #f1f5f9; border: 1.5px solid var(--border);
            border-radius: 14px; padding: 14px; font-size: 0.95rem;
            font-family: 'Courier New', monospace; text-align: center;
            color: var(--text); outline: none; transition: border-color 0.2s;
        }
        .recovery-input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(30,58,138,0.1); }

        .recovery-submit {
            width: 100%; margin-top: 10px; padding: 14px;
            background: #f1f5f9; border: 1.5px solid var(--border);
            border-radius: 14px; font-weight: 700; font-size: 0.88rem;
            cursor: pointer; color: var(--text); transition: background 0.2s;
        }
        .recovery-submit:hover { background: #e2e8f0; }

        /* Logout */
        .logout-section { margin-top: 20px; }
        .logout-btn {
            background: none; border: none; color: var(--text-s);
            font-size: 0.82rem; cursor: pointer; text-decoration: underline;
            transition: color 0.2s;
        }
        .logout-btn:hover { color: #dc2626; }

        @media (max-width: 480px) {
            .verify-card { padding: 32px 22px; }
            .code-input { font-size: 1.3rem; letter-spacing: 8px; padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <!-- Icon -->
            <div class="verify-icon">
                <i class="fas fa-lock"></i>
            </div>

            <h1>Verificação em 2 Etapas</h1>
            <p class="subtitle">
                Abra o <strong>Google Authenticator</strong> no seu celular e digite o código de 6 dígitos.
            </p>

            @if (session('warning'))
                <div class="alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-times-circle"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <!-- Main code form -->
            <form method="POST" action="{{ route('2fa.verify') }}" id="verify-form">
                @csrf
                <div class="code-input-wrapper">
                    <label class="form-label" for="code">Código do Autenticador</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        class="code-input"
                        placeholder="000000"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        autocomplete="one-time-code"
                        autofocus
                        required
                    >
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-check-circle"></i> Verificar e Entrar
                </button>
            </form>

            <!-- Recovery code option -->
            <div class="recovery-toggle">
                <button type="button" class="recovery-btn" onclick="toggleRecovery()">
                    <i class="fas fa-key"></i> Usar código de recuperação
                </button>

                <div class="recovery-section" id="recovery-section">
                    <form method="POST" action="{{ route('2fa.verify') }}">
                        @csrf
                        <label class="form-label" for="recovery-code">Código de Recuperação</label>
                        <input
                            type="text"
                            id="recovery-code"
                            name="code"
                            class="recovery-input"
                            placeholder="xxxxxxxxxx-xxxxxxxxxx"
                            autocomplete="off"
                            required
                        >
                        <button type="submit" class="recovery-submit">
                            Verificar com código de recuperação
                        </button>
                    </form>
                </div>
            </div>

            <!-- Logout -->
            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Sair e usar outra conta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleRecovery() {
            const section = document.getElementById('recovery-section');
            section.classList.toggle('show');
            if (section.classList.contains('show')) {
                document.getElementById('recovery-code').focus();
            }
        }

        // Only allow digits in main code
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
