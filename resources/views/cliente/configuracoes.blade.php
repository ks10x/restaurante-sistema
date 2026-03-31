<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações — Bella Cucina</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .container { max-width: 600px; margin: 0 auto; padding: 40px 20px 80px; }
        .back-link { color: var(--brand); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 30px; font-weight: 500; font-size: 0.9rem; }

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

        /* Danger Zone */
        .danger-item { color: var(--red); }
        .danger-item .title { color: var(--red); }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; justify-content: center; align-items: center; padding: 20px; }
        .modal-overlay.show { display: flex; }
        .modal-content { background: #fff; border-radius: 24px; width: 100%; max-width: 500px; max-height: 80vh; overflow-y: auto; padding: 30px; }
        .modal-content h2 { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 20px; color: var(--text); }
        .modal-content p, .modal-content li { font-size: 0.88rem; color: var(--text-m); line-height: 1.7; margin-bottom: 10px; }
        .modal-content h3 { font-size: 1rem; font-weight: 700; color: var(--text); margin: 18px 0 8px; }
        .modal-close { display: block; width: 100%; margin-top: 20px; padding: 14px; background: var(--brand); color: #fff; border: none; border-radius: 14px; font-weight: 700; cursor: pointer; font-size: 0.95rem; }

        .version-text { text-align: center; color: var(--text-s); font-size: 0.75rem; margin-top: 30px; }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('cardapio.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar ao Menu
    </a>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Foto">
            @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            @endif
        </div>
        <div class="profile-name">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
        <div class="profile-email">{{ Auth::user()->email }}</div>
    </div>

    <!-- Conta -->
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

    <!-- Preferências -->
    <div class="section">
        <div class="section-title">Preferências</div>
        <div class="section-card">
            <div class="menu-item" onclick="this.querySelector('.toggle-switch').classList.toggle('active')">
                <div class="menu-icon purple"><i class="fas fa-bell"></i></div>
                <div class="menu-text">
                    <div class="title">Notificações por E-mail</div>
                    <div class="subtitle">Receba promoções e avisos de pedidos</div>
                </div>
                <div class="toggle-switch active"></div>
            </div>
            <div class="menu-item" onclick="this.querySelector('.toggle-switch').classList.toggle('active')">
                <div class="menu-icon purple"><i class="fas fa-bullhorn"></i></div>
                <div class="menu-text">
                    <div class="title">Notificações por WhatsApp</div>
                    <div class="subtitle">Atualizações sobre seu pedido em tempo real</div>
                </div>
                <div class="toggle-switch active"></div>
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
            <div class="menu-item danger-item" onclick="if(confirm('ATENÇÃO: Esta ação é irreversível. Todos os seus dados serão excluídos permanentemente. Deseja continuar?')) document.getElementById('delete-form').submit();">
                <div class="menu-icon red"><i class="fas fa-trash-alt"></i></div>
                <div class="menu-text">
                    <div class="title">Excluir Conta</div>
                    <div class="subtitle">Remover permanentemente seus dados</div>
                </div>
                <i class="fas fa-chevron-right menu-chevron"></i>
            </div>
            <form id="delete-form" method="POST" action="{{ route('profile.destroy') }}" style="display:none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="password" value="">
            </form>
        </div>
    </div>

    <div class="version-text">Bella Cucina v2.0 • Feito com ❤️</div>
</div>

<!-- Modais -->
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
        <h3>4. Compartilhamento</h3>
        <p>Não vendemos, alugamos ou compartilhamos seus dados pessoais com terceiros, exceto quando necessário para completar seu pedido (parceiros de entrega e processamento de pagamento).</p>
        <h3>5. Seus Direitos</h3>
        <p>Você pode solicitar acesso, correção, exportação ou exclusão dos seus dados pessoais a qualquer momento pela seção "Minha Conta" ou entrando em contato conosco.</p>
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
    document.getElementById('modal-' + id).classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById('modal-' + id).classList.remove('show');
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
</script>

</body>
</html>
