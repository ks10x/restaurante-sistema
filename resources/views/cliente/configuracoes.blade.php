<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações — Bella Cucina</title>
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
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: 'DM Sans', sans-serif; min-height: 100vh; }

        .container { max-width: 760px; margin: 0 auto; padding: 40px 20px 80px; }
        .back-link { color: var(--brand); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 30px; font-weight: 500; font-size: 0.9rem; }
        .skip-link { position: absolute; left: -9999px; top: 8px; background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 10px 14px; z-index: 999; }
        .skip-link:focus { left: 12px; }

        /* Profile Header */
        .profile-header { text-align: center; margin-bottom: 35px; }
        .profile-avatar { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--brand), var(--brand-d)); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: #fff; font-size: 2rem; font-weight: 700; overflow: hidden; box-shadow: 0 4px 12px rgba(30,58,138,0.2); }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-name { font-weight: 700; font-size: 1.2rem; color: var(--text); }
        .profile-email { color: var(--text-m); font-size: 0.85rem; margin-top: 2px; }

        /* Section */
        .section { margin-bottom: 24px; }
        .section-title { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-s); padding: 0 4px; margin-bottom: 8px; }
        .section-card { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; overflow: hidden; }

        /* Menu Item */
        .menu-item { display: flex; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border); text-decoration: none; color: var(--text); transition: background 0.2s; cursor: pointer; }
        .menu-item:last-child { border-bottom: none; }
        .menu-item:hover { background: var(--bg); }
        .menu-item:focus-visible { outline: 3px solid rgba(30,58,138,.25); outline-offset: 2px; }

        .menu-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 14px; flex-shrink: 0; font-size: 1rem; }
        .menu-icon.blue { background: rgba(30,58,138,0.08); color: var(--brand); }
        .menu-icon.green { background: rgba(5,150,105,0.08); color: #059669; }
        .menu-icon.orange { background: rgba(234,88,12,0.08); color: #ea580c; }
        .menu-icon.purple { background: rgba(124,58,237,0.08); color: #7c3aed; }
        .menu-icon.slate { background: rgba(100,116,139,0.08); color: #64748b; }
        .menu-icon.red { background: rgba(220,38,38,0.08); color: #dc2626; }

        .menu-text { flex: 1; }
        .menu-text .title { font-weight: 600; font-size: 0.95rem; }
        .menu-text .subtitle { font-size: 0.78rem; color: var(--text-s); margin-top: 1px; }

        .menu-chevron { color: var(--text-s); font-size: 0.7rem; }

        /* Toggle Switch */
        .toggle-switch { position: relative; width: 44px; height: 24px; background: #cbd5e1; border-radius: 12px; cursor: pointer; transition: background 0.3s; flex-shrink: 0; }
        .toggle-switch.active { background: var(--brand); }
        .toggle-switch::after { content: ''; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: #fff; border-radius: 50%; transition: transform 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.15); }
        .toggle-switch.active::after { transform: translateX(20px); }

        /* Switch (acessível com input) */
        .switch { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .switch input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .switch-track { width: 44px; height: 24px; border-radius: 12px; background: #cbd5e1; position: relative; transition: background .2s; flex-shrink: 0; }
        .switch-thumb { width: 20px; height: 20px; background: #fff; border-radius: 50%; position: absolute; top: 2px; left: 2px; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.15); }
        .switch input:checked + .switch-track { background: var(--brand); }
        .switch input:checked + .switch-track .switch-thumb { transform: translateX(20px); }
        .switch input:focus-visible + .switch-track { outline: 3px solid rgba(30,58,138,.25); outline-offset: 3px; }

        .pill { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 999px; border: 1px solid rgba(0,0,0,0.08); }
        .pill-ok { color: #059669; background: rgba(5,150,105,.08); border-color: rgba(5,150,105,.18); }
        .pill-warn { color: #d97706; background: rgba(217,119,6,.08); border-color: rgba(217,119,6,.18); }

        /* Danger Zone */
        .danger-item { color: var(--red); }
        .danger-item .title { color: var(--red); }

        /* Acessibilidade prefs */
        body.pref-contrast { --bg: #0b1220; --bg2:#111b2e; --surface:#0f172a; --text:#f8fafc; --text-m:#cbd5e1; --text-s:#94a3b8; --border: rgba(148,163,184,.22); }
        body.pref-contrast .menu-item:hover { background: rgba(255,255,255,0.04); }
        body.pref-reduce-motion *, body.pref-reduce-motion *::before, body.pref-reduce-motion *::after { transition: none !important; animation: none !important; scroll-behavior: auto !important; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; justify-content: center; align-items: center; padding: 20px; }
        .modal-overlay.show { display: flex; }
        .modal-content { background: var(--surface); border-radius: 24px; width: 100%; max-width: 500px; max-height: 80vh; overflow-y: auto; padding: 30px; }
        .modal-content h2 { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 20px; color: var(--text); }
        .modal-content p, .modal-content li { font-size: 0.88rem; color: var(--text-m); line-height: 1.7; margin-bottom: 10px; }
        .modal-content h3 { font-size: 1rem; font-weight: 700; color: var(--text); margin: 18px 0 8px; }
        .modal-close { display: block; width: 100%; margin-top: 20px; padding: 14px; background: var(--brand); color: #fff; border: none; border-radius: 14px; font-weight: 700; cursor: pointer; font-size: 0.95rem; }

        .version-text { text-align: center; color: var(--text-s); font-size: 0.75rem; margin-top: 30px; }
    </style>
</head>
<body>

<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>
<div class="container">
    <a href="{{ route('cardapio.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar ao Menu
    </a>

    @php($u = $user ?? Auth::user())
    @php($cons = $consentimentos ?? ($u?->consentimentoLgpd ?? null))
    @php($sessoes = $sessoesAtivas ?? collect())
    @php($tentativas = $loginAttempts ?? collect())

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            @if($u->avatar)
                <img src="{{ asset('storage/' . $u->avatar) }}" alt="Foto de perfil">
            @else
                {{ strtoupper(substr($u->name, 0, 1)) }}
            @endif
        </div>
        <div class="profile-name">{{ $u->name }} {{ $u->last_name }}</div>
        <div class="profile-email">{{ $u->email }}</div>
        <div style="margin-top:10px;display:flex;justify-content:center;gap:8px;flex-wrap:wrap">
            <span class="pill {{ ($u->two_factor_confirmed_at ?? null) ? 'pill-ok' : 'pill-warn' }}">
                <i class="fas fa-shield-alt"></i>
                {{ ($u->two_factor_confirmed_at ?? null) ? '2FA ativo' : '2FA recomendado' }}
            </span>
            @if(($u->last_login_at ?? null))
                <span class="pill pill-ok"><i class="fas fa-clock"></i> Último acesso: {{ \Carbon\Carbon::parse($u->last_login_at)->format('d/m/Y H:i') }}</span>
            @endif
        </div>
    </div>

    <!-- Conta -->
    <main id="conteudo" tabindex="-1">
    <div class="section">
        <div class="section-title">Conta</div>
        <div class="section-card">
            <a href="{{ route('profile.edit') }}" class="menu-item">
                <div class="menu-icon blue"><i class="fas fa-user"></i></div>
                <div class="menu-text">
                    <div class="title">Dados Pessoais</div>
                    <div class="subtitle">Nome, telefone, e-mail, CPF e foto</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </a>
            <a href="{{ route('profile.edit') }}" class="menu-item">
                <div class="menu-icon green"><i class="fas fa-map-marker-alt"></i></div>
                <div class="menu-text">
                    <div class="title">Endereços</div>
                    <div class="subtitle">Gerencie seus endereços para entrega</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </a>
            <a href="{{ route('cliente.pedidos') }}" class="menu-item">
                <div class="menu-icon orange"><i class="fas fa-receipt"></i></div>
                <div class="menu-text">
                    <div class="title">Histórico de Pedidos</div>
                    <div class="subtitle">Veja todos os seus pedidos anteriores</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </a>
        </div>
    </div>

    <!-- Segurança -->
    <div class="section">
        <div class="section-title">Segurança</div>
        <div class="section-card">
            <a href="{{ route('2fa.index') }}" class="menu-item">
                <div class="menu-icon green"><i class="fas fa-user-shield"></i></div>
                <div class="menu-text">
                    <div class="title">Autenticação em 2 etapas (2FA)</div>
                    <div class="subtitle">
                        {{ ($u->two_factor_confirmed_at ?? null) ? 'Ativo no momento' : 'Ative para proteger sua conta' }}
                    </div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </a>

            <div class="menu-item" role="button" tabindex="0" onclick="showModal('sessoes')" onkeypress="if(event.key==='Enter'||event.key===' ') showModal('sessoes')">
                <div class="menu-icon slate"><i class="fas fa-laptop"></i></div>
                <div class="menu-text">
                    <div class="title">Sessões ativas</div>
                    <div class="subtitle">Dispositivos conectados ({{ $sessoes->count() }}) • Encerrar sessões</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </div>

            <div class="menu-item" role="button" tabindex="0" onclick="showModal('acessos')" onkeypress="if(event.key==='Enter'||event.key===' ') showModal('acessos')">
                <div class="menu-icon slate"><i class="fas fa-key"></i></div>
                <div class="menu-text">
                    <div class="title">Atividade de login</div>
                    <div class="subtitle">Últimas tentativas de acesso ({{ $tentativas->count() }})</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </div>
        </div>
    </div>

    <!-- Preferências -->
    <div class="section">
        <div class="section-title">Preferências</div>
        <form method="POST" action="{{ route('lgpd.consent') }}" class="section-card" style="overflow:hidden">
            @csrf
            <div class="menu-item">
                <div class="menu-icon purple"><i class="fas fa-bell"></i></div>
                <div class="menu-text">
                    <div class="title">Marketing e Comunicações</div>
                    <div class="subtitle">Promoções e novidades (consentimento LGPD)</div>
                </div>
                <label class="switch" aria-label="Consentimento de marketing">
                    <input type="checkbox" name="marketing" value="1" @checked((bool)($cons->marketing ?? false))>
                    <span class="switch-track" aria-hidden="true"><span class="switch-thumb"></span></span>
                </label>
            </div>

            <div class="menu-item">
                <div class="menu-icon purple"><i class="fas fa-cookie-bite"></i></div>
                <div class="menu-text">
                    <div class="title">Cookies Analíticos</div>
                    <div class="subtitle">Ajuda a melhorar a experiência</div>
                </div>
                <label class="switch" aria-label="Cookies analíticos">
                    <input type="checkbox" name="analiticos" value="1" @checked((bool)($cons->cookies_analiticos ?? false))>
                    <span class="switch-track" aria-hidden="true"><span class="switch-thumb"></span></span>
                </label>
            </div>

            <div class="menu-item">
                <div class="menu-icon purple"><i class="fas fa-handshake"></i></div>
                <div class="menu-text">
                    <div class="title">Compartilhamento com Terceiros</div>
                    <div class="subtitle">Além do necessário para operar seu pedido</div>
                </div>
                <label class="switch" aria-label="Compartilhamento com terceiros">
                    <input type="checkbox" name="terceiros" value="1" @checked((bool)($cons->compartilhamento_terceiros ?? false))>
                    <span class="switch-track" aria-hidden="true"><span class="switch-thumb"></span></span>
                </label>
            </div>

            <div class="menu-item" style="justify-content:flex-end;gap:10px">
                <button type="submit" style="padding:10px 14px;border-radius:12px;border:1px solid var(--border);background:var(--brand);color:#fff;font-weight:800;cursor:pointer">Salvar preferências</button>
            </div>
        </form>

        <div class="section-card" style="margin-top:12px">
            <div class="menu-item" onclick="toggleA11yPref('reduce_motion')">
                <div class="menu-icon slate"><i class="fas fa-running"></i></div>
                <div class="menu-text">
                    <div class="title">Reduzir animações</div>
                    <div class="subtitle">Menos movimentos e transições</div>
                </div>
                <div class="toggle-switch" data-a11y-toggle="reduce_motion"></div>
            </div>
        </div>
    </div>

    <!-- Sobre -->
    <div class="section">
        <div class="section-title">Sobre</div>
        <div class="section-card">
            <div class="menu-item" onclick="showModal('termos')">
                <div class="menu-icon slate"><i class="fas fa-file-contract"></i></div>
                <div class="menu-text">
                    <div class="title">Termos de Uso</div>
                    <div class="subtitle">Regras e condições do serviço</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </div>
            <div class="menu-item" onclick="showModal('privacidade')">
                <div class="menu-icon slate"><i class="fas fa-shield-alt"></i></div>
                <div class="menu-text">
                    <div class="title">Política de Privacidade</div>
                    <div class="subtitle">Como tratamos seus dados pessoais</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </div>
            <div class="menu-item" onclick="showModal('aviso')">
                <div class="menu-icon slate"><i class="fas fa-balance-scale"></i></div>
                <div class="menu-text">
                    <div class="title">Aviso Legal</div>
                    <div class="subtitle">Informações legais e diretrizes</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </div>
        </div>
    </div>

    <!-- Privacidade & LGPD -->
    <div class="section">
        <div class="section-title">Privacidade (LGPD)</div>
        <div class="section-card">
            <a href="{{ route('lgpd.index') }}" class="menu-item">
                <div class="menu-icon blue"><i class="fas fa-user-lock"></i></div>
                <div class="menu-text">
                    <div class="title">Central de Privacidade</div>
                    <div class="subtitle">Consentimentos, exportação e anonimização</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </a>
            <a href="{{ route('lgpd.export') }}" class="menu-item">
                <div class="menu-icon green"><i class="fas fa-file-download"></i></div>
                <div class="menu-text">
                    <div class="title">Exportar meus dados</div>
                    <div class="subtitle">Baixar arquivo JSON com seus dados</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </a>
        </div>
    </div>

    <!-- Suporte -->
    <div class="section">
        <div class="section-title">Suporte</div>
        <div class="section-card">
            <a href="https://wa.me/5511999999999" target="_blank" class="menu-item">
                <div class="menu-icon green"><i class="fab fa-whatsapp"></i></div>
                <div class="menu-text">
                    <div class="title">Fale Conosco</div>
                    <div class="subtitle">Converse com a gente pelo WhatsApp</div>
                </div>
                <i class="fas fa-external-link-alt menu-chevron"></i>
            </a>
            <a href="mailto:contato@bellacucina.com" class="menu-item">
                <div class="menu-icon blue"><i class="fas fa-envelope"></i></div>
                <div class="menu-text">
                    <div class="title">E-mail de Suporte</div>
                    <div class="subtitle">contato@bellacucina.com</div>
                </div>
                <i class="fas fa-external-link-alt menu-chevron"></i>
            </a>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="section">
        <div class="section-title">Zona de Risco</div>
        <div class="section-card">
            <div class="menu-item">
                <div class="menu-icon red"><i class="fas fa-sign-out-alt"></i></div>
                <div class="menu-text">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">
                            <div class="title" style="color:#dc2626;text-align:left;">Sair da Conta</div>
                            <div class="subtitle" style="text-align:left;">Encerrar sua sessão atual</div>
                        </button>
                    </form>
                </div>
            </div>
            <div class="menu-item danger-item" onclick="showModal('excluir')" role="button" tabindex="0" onkeypress="if(event.key==='Enter'||event.key===' ') showModal('excluir')">
                <div class="menu-icon red"><i class="fas fa-trash-alt"></i></div>
                <div class="menu-text">
                    <div class="title">Excluir Conta</div>
                    <div class="subtitle">Anonimizar e desativar acesso (LGPD)</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </div>
        </div>
    </div>

    <div class="version-text">Bella Cucina v2.0 • Feito com ❤️</div>
</main>
</div>

<!-- Modais -->
<div class="modal-overlay" id="modal-sessoes" role="dialog" aria-modal="true" aria-label="Sessões ativas">
    <div class="modal-content">
        <h2>💻 Sessões ativas</h2>
        <p>Veja onde sua conta está conectada e encerre sessões que você não reconhece.</p>

        <h3>Dispositivos</h3>
        @if($sessoes->isEmpty())
            <p>Nenhuma sessão registrada.</p>
        @else
            <ul style="list-style:none; padding-left:0; display:flex; flex-direction:column; gap:10px;">
                @foreach($sessoes as $sessao)
                    @php($isCurrent = ($sessao->id ?? null) === session()->getId())
                    <li style="border:1px solid var(--border); border-radius:16px; padding:14px 14px;">
                        <div style="display:flex; justify-content:space-between; gap:10px; align-items:flex-start;">
                            <div style="min-width:0">
                                <div style="font-weight:800; font-size:0.92rem; margin-bottom:4px;">
                                    {{ $isCurrent ? 'Este dispositivo' : 'Outro dispositivo' }}
                                </div>
                                <div style="font-size:0.82rem; color:var(--text-s); word-break:break-word;">
                                    IP: {{ $sessao->ip_address ?? '—' }}<br>
                                    Agente: {{ \Illuminate\Support\Str::limit((string)($sessao->user_agent ?? '—'), 110) }}<br>
                                    Última atividade: {{ isset($sessao->last_activity) ? \Carbon\Carbon::createFromTimestamp((int)$sessao->last_activity)->format('d/m/Y H:i') : '—' }}
                                </div>
                            </div>
                            <form method="POST" action="{{ route('cliente.seguranca.sessoes.revoke', $sessao->id) }}" onsubmit="return confirm('Encerrar esta sessão?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border:1px solid rgba(220,38,38,.25); background: rgba(220,38,38,.08); color: var(--red); padding:8px 10px; border-radius:12px; font-weight:800; cursor:pointer;">
                                    Encerrar
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('cliente.seguranca.sessoes.outras') }}" style="margin-top:14px" onsubmit="return confirm('Encerrar todas as outras sessões?')">
                @csrf
                @method('DELETE')
                <button type="submit" style="width:100%; border:1px solid var(--border); background: var(--bg); padding:12px 14px; border-radius:14px; font-weight:800; cursor:pointer;">
                    Encerrar outras sessões
                </button>
            </form>
        @endif

        <button class="modal-close" onclick="closeModal('sessoes')">Fechar</button>
    </div>
</div>

<div class="modal-overlay" id="modal-acessos" role="dialog" aria-modal="true" aria-label="Atividade de login">
    <div class="modal-content">
        <h2>🔑 Atividade de login</h2>
        <p>Transparência e segurança: exibimos tentativas recentes de login na sua conta.</p>

        <h3>Últimas tentativas</h3>
        @if($tentativas->isEmpty())
            <p>Nenhum registro disponível.</p>
        @else
            <ul style="list-style:none; padding-left:0; display:flex; flex-direction:column; gap:10px;">
                @foreach($tentativas as $t)
                    <li style="border:1px solid var(--border); border-radius:16px; padding:14px 14px;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px">
                            <div style="min-width:0">
                                <div style="font-weight:800; font-size:0.92rem; margin-bottom:4px;">
                                    {{ $t->success ? 'Sucesso' : 'Falha' }}
                                </div>
                                <div style="font-size:0.82rem; color:var(--text-s); word-break:break-word;">
                                    IP: {{ $t->ip_address ?? '—' }}<br>
                                    Data: {{ $t->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </div>
                            </div>
                            <span class="pill {{ $t->success ? 'pill-ok' : 'pill-warn' }}">
                                <i class="fas {{ $t->success ? 'fa-check' : 'fa-exclamation-triangle' }}"></i>
                                {{ $t->success ? 'OK' : 'Atenção' }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <button class="modal-close" onclick="closeModal('acessos')">Fechar</button>
    </div>
</div>

<div class="modal-overlay" id="modal-excluir" role="dialog" aria-modal="true" aria-label="Excluir conta">
    <div class="modal-content">
        <h2>⚠️ Excluir conta (LGPD)</h2>
        <p>Esta ação <strong>anonimiza</strong> seus dados pessoais e bloqueia o acesso à conta. Alguns registros podem ser mantidos de forma anonimizada para fins legais/contábeis e consistência de pedidos.</p>

        <h3>Confirmação</h3>
        <form method="POST" action="{{ route('lgpd.anonymize') }}" onsubmit="return confirm('Tem certeza? Essa ação é irreversível.')">
            @csrf
            @method('DELETE')
            <label style="display:block;font-size:0.8rem;color:var(--text-m);font-weight:800;text-transform:uppercase;letter-spacing:.06em;margin:10px 0 6px">Digite sua senha</label>
            <div style="position:relative">
                <input id="delete_password" type="password" name="password" required autocomplete="current-password" style="width:100%; background: var(--bg); border: 1px solid var(--border); border-radius: 14px; padding: 14px 46px 14px 14px;">
                <button type="button" data-toggle-password="delete_password" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color: var(--text-m);">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <button class="modal-close" type="submit" style="background: var(--red);">Anonimizar conta</button>
            <button class="modal-close" type="button" onclick="closeModal('excluir')" style="background: var(--brand); margin-top:10px;">Cancelar</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modal-termos">
    <div class="modal-content">
        <h2>📋 Termos de Uso</h2>
        <p>Última atualização: Março de 2026</p>
        <h3>1. Aceitação dos Termos</h3>
        <p>Ao utilizar os serviços da Bella Cucina, incluindo nosso site e aplicativo, você concorda com estes Termos de Uso. Se você não concordar com qualquer parte destes termos, não utilize nossos serviços.</p>
        <h3>2. Funcionamento do Serviço</h3>
        <p>A Bella Cucina é uma plataforma de pedidos online de alimentos. Oferecemos cardápio digital, pedidos para entrega (delivery) e retirada no local. Reservamo-nos o direito de recusar ou cancelar pedidos em casos de indisponibilidade de produtos.</p>
        <h3>3. Cadastro</h3>
        <p>Para realizar pedidos, é necessário criar uma conta com informações verdadeiras e atualizadas. Você é responsável por manter a confidencialidade da sua senha.</p>
        <h3>4. Pagamentos</h3>
        <p>Os preços exibidos incluem os valores dos itens, taxa de entrega (quando aplicável) e eventuais descontos. Aceitamos PIX, cartões de crédito/débito, dinheiro e vale-alimentação.</p>
        <h3>5. Cancelamentos</h3>
        <p>Pedidos podem ser cancelados antes de entrarem em produção. Após o início do preparo, não é possível cancelar. Reembolsos serão processados conforme o método de pagamento original.</p>
        <h3>6. Responsabilidade</h3>
        <p>A Bella Cucina se compromete com a qualidade e segurança alimentar. Em caso de problemas com seu pedido, entre em contato conosco em até 24 horas para resolvermos juntos.</p>
        <button class="modal-close" onclick="closeModal('termos')">Entendi</button>
    </div>
</div>

<div class="modal-overlay" id="modal-privacidade">
    <div class="modal-content">
        <h2>🔒 Política de Privacidade</h2>
        <p>Última atualização: Março de 2026</p>
        <h3>1. Dados Coletados</h3>
        <p>Coletamos apenas os dados necessários para prestar nossos serviços: nome, e-mail, telefone, CPF (para notas fiscais), endereços de entrega e histórico de pedidos.</p>
        <h3>2. Uso dos Dados</h3>
        <p>Seus dados são utilizados para processar pedidos, realizar entregas, enviar atualizações sobre seus pedidos e, com seu consentimento, enviar promoções e novidades.</p>
        <h3>3. Proteção dos Dados</h3>
        <p>Utilizamos criptografia para proteger informações sensíveis como CPF e dados de pagamento. Nosso sistema segue as diretrizes da Lei Geral de Proteção de Dados (LGPD).</p>
        <h3>3.1 Medidas de Segurança</h3>
        <p>Adotamos registro de tentativas de login, proteção contra abuso (rate-limit) e acompanhamento de sessões ativas. Recomendamos ativar a autenticação em 2 etapas (2FA) e usar senhas fortes.</p>
        <h3>4. Compartilhamento</h3>
        <p>Não vendemos, alugamos ou compartilhamos seus dados pessoais com terceiros, exceto quando necessário para completar seu pedido (parceiros de entrega e processamento de pagamento).</p>
        <h3>5. Seus Direitos</h3>
        <p>Você pode solicitar acesso, correção, exportação ou exclusão dos seus dados pessoais a qualquer momento pela seção "Minha Conta" ou entrando em contato conosco.</p>
        <h3>5.1 Retenção</h3>
        <p>Alguns registros podem ser retidos por períodos exigidos por lei (ex.: obrigações fiscais/contábeis), preferencialmente de forma minimizada/anonimizada quando possível.</p>
        <h3>5.2 Canal de Privacidade</h3>
        <p>Se precisar de ajuda para exercer seus direitos, entre em contato pelo e-mail de suporte informado na seção "Suporte".</p>
        <h3>6. Cookies</h3>
        <p>Utilizamos cookies essenciais para funcionamento do site e cookies analíticos para melhorar a experiência do usuário.</p>
        <button class="modal-close" onclick="closeModal('privacidade')">Entendi</button>
    </div>
</div>

<div class="modal-overlay" id="modal-aviso">
    <div class="modal-content">
        <h2>⚖️ Aviso Legal</h2>
        <p>Última atualização: Março de 2026</p>
        <h3>Informações Gerais</h3>
        <p>A Bella Cucina é um estabelecimento de alimentação registrado e em conformidade com as normas da ANVISA e legislação municipal de segurança alimentar.</p>
        <h3>Alérgenos</h3>
        <p>Alguns dos nossos pratos podem conter substâncias alergênicas como: glúten, lactose, nozes, amendoim, ovos, frutos do mar e soja. Consulte a descrição de cada prato ou entre em contato antes de realizar seu pedido.</p>
        <h3>Horário de Funcionamento</h3>
        <p>Os horários de funcionamento podem variar de acordo com feriados e eventos especiais. Acompanhe nossas redes sociais para comunicados.</p>
        <h3>Propriedade Intelectual</h3>
        <p>Todo o conteúdo deste site, incluindo marca, logotipo, layout, textos e imagens, são de propriedade exclusiva da Bella Cucina e protegidos por lei.</p>
        <h3>Legislação Aplicável</h3>
        <p>Estes termos são regidos pelas leis da República Federativa do Brasil. Quaisquer disputas serão resolvidas no foro da comarca onde o estabelecimento está localizado.</p>
        <button class="modal-close" onclick="closeModal('aviso')">Entendi</button>
    </div>
</div>

<script>
function showModal(id) {
    const el = document.getElementById('modal-' + id);
    if (!el) return;
    el.classList.add('show');
    document.body.style.overflow = 'hidden';
    const focusable = el.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    focusable?.focus?.();
}
function closeModal(id) {
    const el = document.getElementById('modal-' + id);
    if (!el) return;
    el.classList.remove('show');
    document.body.style.overflow = '';
}
// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if(e.target === this) {
            this.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
});

// ESC fecha modal
document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    const open = document.querySelector('.modal-overlay.show');
    if (!open) return;
    open.classList.remove('show');
    document.body.style.overflow = '';
});

// Acessibilidade (localStorage)
function applyA11yPrefs() {
    const contrast = localStorage.getItem('pref_contrast') === '1';
    const reduceMotion = localStorage.getItem('pref_reduce_motion') === '1';
    document.body.classList.toggle('pref-contrast', contrast);
    document.body.classList.toggle('pref-reduce-motion', reduceMotion);

    document.querySelectorAll('[data-a11y-toggle="reduce_motion"]').forEach(el => el.classList.toggle('active', reduceMotion));
}
function toggleA11yPref(key) {
    if (window.A11Y_PREFS) {
        window.A11Y_PREFS.toggle(key);
        return;
    }
    const map = { contrast: 'pref_contrast', reduce_motion: 'pref_reduce_motion' };
    const storageKey = map[key];
    if (!storageKey) return;
    const current = localStorage.getItem(storageKey) === '1';
    localStorage.setItem(storageKey, current ? '0' : '1');
    applyA11yPrefs();
}
if (!window.A11Y_PREFS) applyA11yPrefs();

// Password eye toggle (para modais)
document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-toggle-password');
        const input = document.getElementById(id);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        const icon = btn.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
        }
    });
});
</script>

</body>
</html>
