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
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Photo">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
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
                    <input type="password" name="password" autocomplete="new-password">
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Endereços Salvos</h2>
            
            <div class="address-box">
                <label style="color: var(--brand); display: block; margin-bottom: 15px;"><i class="fas fa-home"></i> Residencial</label>
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
