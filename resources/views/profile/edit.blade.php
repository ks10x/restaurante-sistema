<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil — Bella Cucina</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/a11y-prefs.js') }}" defer></script>
    <style>
        :root {
            --brand: #1e3a8a; /* blue-900 */
            --brand-d: #1e40af; /* blue-800 */
            --bg: #f8fafc; /* slate-50 */
            --bg2: #f1f5f9; /* slate-100 */
            --surface: #ffffff; 
            --text: #0f172a; /* slate-900 */
            --text-m: #64748b; /* slate-500 */
            --text-s: #94a3b8; /* slate-400 */
            --border: rgba(30, 58, 138, 0.15);
        }
        body { background: var(--bg); color: var(--text); font-family: 'DM Sans', sans-serif; margin: 0; padding-bottom: 50px; }
        
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .back-link { color: var(--brand); text-decoration: none; display: flex; align-items: center; gap: 8px; margin-bottom: 30px; font-weight: 500; }
        
        .profile-card { background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; margin-bottom: 25px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .section-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 30px; display: flex; align-items: center; gap: 15px; color: var(--text); }
        .section-title i { color: var(--brand); font-size: 1.4rem; }

        /* Foto de Perfil */
        .photo-upload { display: flex; align-items: center; gap: 25px; margin-bottom: 40px; }
        .current-photo { width: 100px; height: 100px; border-radius: 50%; background: var(--bg2); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--brand); border: 4px solid var(--bg); overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .current-photo img { width: 100%; height: 100%; object-fit: cover; }
        .btn-upload { background: var(--surface); border: 1px solid var(--brand); color: var(--brand); padding: 10px 20px; border-radius: 50px; cursor: pointer; font-size: 0.9rem; transition: 0.3s; font-weight: 500;}
        .btn-upload:hover { background: var(--brand); color: #fff; }

        /* Grid de Formulário */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .full-width { grid-column: span 2; }
        label { font-size: 0.85rem; color: var(--text-m); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        input { background: var(--bg); border: 1px solid rgba(0,0,0,0.1); border-radius: 12px; padding: 15px; color: var(--text); font-family: inherit; transition: 0.3s; }
        input:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1); }

        .address-box { background: var(--bg2); border-radius: 16px; padding: 25px; margin-top: 15px; border-left: 4px solid var(--brand); }
        .btn-save { background: var(--brand); color: #fff; border: none; padding: 18px 40px; border-radius: 14px; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 20px; box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2); }
        .btn-save:hover { background: var(--brand-d); transform: translateY(-2px); }

        .pass-wrap { position: relative; }
        .pass-wrap input { width: 100%; padding-right: 46px; }
        .pass-wrap button { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-m); z-index: 2; }

        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('cardapio.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar ao Menu
    </a>

    @if(session('status') === 'profile-updated')
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 16px 20px; border-radius: 16px; margin-bottom: 25px; display: flex; align-items: center; gap: 12px; font-size: 0.9rem; font-weight: 600;">
        <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i> Suas informações foram atualizadas com sucesso!
    </div>
    @endif

    @if($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 16px 20px; border-radius: 16px; margin-bottom: 25px; font-size: 0.85rem;">
        <p style="font-weight: 700; margin-bottom: 8px;"><i class="fas fa-exclamation-circle"></i> Corrija os erros abaixo:</p>
        @foreach($errors->all() as $error)
            <p>• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="profile-card">
            <h2 class="section-title"><i class="fas fa-user-circle"></i> Dados Pessoais</h2>
            
            <div class="photo-upload">
                <div class="current-photo">
                    @if(Auth::user()->avatar)
                        <img id="avatarPreview" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Photo">
                    @else
                        <img id="avatarPreview" src="" alt="User Photo" style="display:none;">
                        <span id="avatarFallback">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <label>Foto de Perfil</label>
                    <div style="margin-top: 10px;">
                        <input type="file" name="photo" id="photo" style="display: none;">
                        <label for="photo" class="btn-upload">Alterar imagem</label>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Sobrenome</label>
                    <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}">
                </div>
                <div class="form-group">
                    <label>Telefone / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="(11) 99999-9999">
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF (Apenas números)</label>
                    <input id="cpf" name="cpf" type="text" 
                        value="{{ old('cpf', Auth::user()->cpf) }}" 
                        placeholder="000.000.000-00"
                        maxlength="14">
                </div>
                <div class="form-group full-width">
                    <label>Nova Senha (deixe em branco para não alterar)</label>
                    <div class="form-grid" style="gap:20px; margin-top: 6px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <div class="pass-wrap">
                                <input id="profile_password" type="password" name="password" autocomplete="new-password" placeholder="Nova senha">
                                <button type="button" data-toggle-password="profile_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <div class="pass-wrap">
                                <input id="profile_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Confirmar nova senha">
                                <button type="button" data-toggle-password="profile_password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Meu Endereço</h2>

            @php($addr = $endereco)
            <div class="address-box">
                <label style="color: var(--brand); display: block; margin-bottom: 15px;"><i class="fas fa-home"></i> Endereço principal</label>

                <div class="form-grid">
                    <div class="form-group">
                        <label>CEP</label>
                        <input id="addr-cep" type="text" name="cep" placeholder="00000-000" value="{{ old('cep', $addr?->cep) }}">
                        <div id="addr-cep-hint" style="margin-top:6px;font-size:11px;color:var(--text-s);"></div>
                    </div>
                    <div class="form-group">
                        <label>Número</label>
                        <input id="addr-numero" type="text" name="numero" value="{{ old('numero', $addr?->numero) }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Rua (Logradouro)</label>
                        <input id="addr-logradouro" type="text" name="logradouro" value="{{ old('logradouro', $addr?->logradouro) }}">
                    </div>

                    <div class="form-group">
                        <label>Bairro</label>
                        <input id="addr-bairro" type="text" name="bairro" value="{{ old('bairro', $addr?->bairro) }}">
                    </div>
                    <div class="form-group">
                        <label>Cidade</label>
                        <input id="addr-cidade" type="text" name="cidade" value="{{ old('cidade', $addr?->cidade) }}">
                    </div>

                    <div class="form-group">
                        <label>UF</label>
                        <input id="addr-estado" type="text" name="estado" maxlength="2" value="{{ old('estado', $addr?->estado) }}" placeholder="SP">
                    </div>
                    <div class="form-group">
                        <label>Complemento</label>
                        <input id="addr-complemento" type="text" name="complemento" value="{{ old('complemento', $addr?->complemento) }}">
                    </div>
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn-save">Salvar Alterações</button>
            </div>
        </div>
    </form>
</div>
<script>
    function onlyDigits(v) { return (v || '').toString().replace(/\D/g, ''); }

    function maskCpf(v) {
        const d = onlyDigits(v).slice(0,11);
        return d
            .replace(/^(\d{3})(\d)/, '$1.$2')
            .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
            .replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
    }

    function maskCep(v) {
        const d = onlyDigits(v).slice(0,8);
        return d.replace(/^(\d{5})(\d)/, '$1-$2');
    }

    function maskPhone(v) {
        const d = onlyDigits(v).slice(0,11);
        if (d.length <= 10) {
            return d
                .replace(/^(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{4})(\d)/, '$1-$2');
        }
        return d
            .replace(/^(\d{2})(\d)/, '($1) $2')
            .replace(/(\d{5})(\d)/, '$1-$2');
    }

    // Password eye toggle
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

    // Avatar preview
    const photoInput = document.getElementById('photo');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarFallback = document.getElementById('avatarFallback');

    photoInput?.addEventListener('change', (e) => {
        const file = e.target.files && e.target.files[0];
        if (!file || !avatarPreview) return;

        const url = URL.createObjectURL(file);
        avatarPreview.src = url;
        avatarPreview.style.display = 'block';
        if (avatarFallback) avatarFallback.style.display = 'none';
    });

    // Masks
    const cpfInput = document.getElementById('cpf');
    const phoneInput = document.querySelector('input[name="phone"]');
    const cepInput = document.getElementById('addr-cep');
    const estadoInput = document.getElementById('addr-estado');

    cpfInput?.addEventListener('input', (e) => { e.target.value = maskCpf(e.target.value); });
    phoneInput?.addEventListener('input', (e) => { e.target.value = maskPhone(e.target.value); });
    cepInput?.addEventListener('input', (e) => { e.target.value = maskCep(e.target.value); });
    estadoInput?.addEventListener('input', (e) => {
        e.target.value = (e.target.value || '').toUpperCase().replace(/[^A-Z]/g,'').slice(0,2);
    });

    // ViaCEP
    const cepHint = document.getElementById('addr-cep-hint');
    let lastCepLookup = null;

    async function lookupCep() {
        if (!cepInput) return;
        const cepDigits = onlyDigits(cepInput.value);
        if (cepDigits.length !== 8) return;
        if (cepDigits === lastCepLookup) return;

        lastCepLookup = cepDigits;
        if (cepHint) cepHint.textContent = 'Buscando CEP...';

        try {
            const resp = await fetch(`https://viacep.com.br/ws/${cepDigits}/json/`);
            const data = await resp.json();

            if (data.erro) {
                if (cepHint) cepHint.textContent = 'CEP não encontrado. Você pode preencher manualmente.';
                return;
            }

            const logradouro = document.getElementById('addr-logradouro');
            const bairro = document.getElementById('addr-bairro');
            const cidade = document.getElementById('addr-cidade');
            const estado = document.getElementById('addr-estado');

            if (logradouro && !logradouro.value) logradouro.value = data.logradouro || '';
            if (bairro && !bairro.value) bairro.value = data.bairro || '';
            if (cidade && !cidade.value) cidade.value = data.localidade || '';
            if (estado) estado.value = (data.uf || '').toUpperCase();

            if (cepHint) cepHint.textContent = 'Endereço preenchido automaticamente. Você pode editar se quiser.';
        } catch (e) {
            if (cepHint) cepHint.textContent = 'Erro ao consultar ViaCEP. Preencha manualmente.';
        }
    }

    cepInput?.addEventListener('blur', lookupCep);
    cepInput?.addEventListener('keyup', () => {
        if (onlyDigits(cepInput.value).length === 8) lookupCep();
    });
</script>

</body>
</html>
