<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil — Bella Cucina</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amber: #D4A373; --amber-d: #B38456; --dark: #1A1512;
            --dark2: #24201A; --surface: #2A2420; --text: #F5EDD8;
            --text-m: #A0A0A0; --border: rgba(212,163,115,0.15);
        }
        body { background: var(--dark); color: var(--text); font-family: 'DM Sans', sans-serif; margin: 0; padding-bottom: 50px; }
        
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .back-link { color: var(--amber); text-decoration: none; display: flex; align-items: center; gap: 8px; margin-bottom: 30px; font-weight: 500; }
        
        .profile-card { background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; margin-bottom: 25px; }
        .section-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 30px; display: flex; align-items: center; gap: 15px; }
        .section-title i { color: var(--amber); font-size: 1.4rem; }

        /* Foto de Perfil */
        .photo-upload { display: flex; align-items: center; gap: 25px; margin-bottom: 40px; }
        .current-photo { width: 100px; height: 100px; border-radius: 50%; background: var(--amber); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--dark); border: 4px solid var(--dark2); }
        .btn-upload { background: var(--dark2); border: 1px solid var(--border); color: var(--text); padding: 10px 20px; border-radius: 50px; cursor: pointer; font-size: 0.9rem; transition: 0.3s; }
        .btn-upload:hover { border-color: var(--amber); }

        /* Grid de Formulário */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .full-width { grid-column: span 2; }
        label { font-size: 0.85rem; color: var(--text-m); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
        input { background: var(--dark); border: 1px solid var(--border); border-radius: 12px; padding: 15px; color: var(--text); font-family: inherit; transition: 0.3s; }
        input:focus { outline: none; border-color: var(--amber); box-shadow: 0 0 0 4px rgba(212, 163, 115, 0.1); }

        .address-box { background: var(--dark2); border-radius: 16px; padding: 25px; margin-top: 15px; border-left: 4px solid var(--amber); }
        .btn-save { background: var(--amber); color: var(--dark); border: none; padding: 18px 40px; border-radius: 14px; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 20px; }
        .btn-save:hover { background: var(--amber-d); transform: translateY(-2px); }

        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('cardapio.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar ao Menu
    </a>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="profile-card">
            <h2 class="section-title"><i class="fas fa-user-circle"></i> Dados Pessoais</h2>
            
            <div class="photo-upload">
                <div class="current-photo">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
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
                <div class="input-group">
                    <label for="cpf">CPF (Apenas números)</label>
                    <input id="cpf" name="cpf" type="text" 
                        value="{{ old('cpf', $user->cpf) }}" 
                        placeholder="000.000.000-00"
                        maxlength="11">
                </div>
                <div class="form-group full-width">
                    <label>Nova Senha (deixe em branco para não alterar)</label>
                    <input type="password" name="password" autocomplete="new-password">
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Endereços Salvos</h2>
            
            <div class="address-box">
                <label style="color: var(--amber); display: block; margin-bottom: 15px;"><i class="fas fa-home"></i> Residencial</label>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <input type="text" name="address_home" placeholder="Rua, Número, Bairro, Guaianases..." value="{{ old('address_home', Auth::user()->address_home) }}">
                    </div>
                </div>
            </div>

            <div class="address-box" style="border-left-color: var(--text-s);">
                <label style="color: var(--text-m); display: block; margin-bottom: 15px;"><i class="fas fa-briefcase"></i> Trabalho</label>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <input type="text" name="address_work" placeholder="Endereço da sua empresa ou escritório" value="{{ old('address_work', Auth::user()->address_work) }}">
                    </div>
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn-save">Salvar Alterações</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>
