<?php

use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────
// Controllers de Auth
// ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PhoneVerificationController;

// ─────────────────────────────────────────────────────────────
// Controllers Admin
// ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CardapioController as AdminCardapioController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\NotificacaoController as AdminNotificacaoController;

// ─────────────────────────────────────────────────────────────
// Controllers Cliente
// ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Cliente\CardapioController as ClienteCardapioController;
use App\Http\Controllers\Cliente\PedidoController;
use App\Http\Controllers\Cliente\CheckoutController;

// ─────────────────────────────────────────────────────────────
// Controllers Cozinha
// ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Cozinha\FilaController;

// ─────────────────────────────────────────────────────────────
// Outros
// ─────────────────────────────────────────────────────────────
use App\Http\Controllers\LgpdController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;


// ═════════════════════════════════════════════════════════════
// ROTA RAIZ
// ═════════════════════════════════════════════════════════════
Route::get('/', fn() => redirect()->route('cardapio.index'));


// ═════════════════════════════════════════════════════════════
// AUTENTICAÇÃO (apenas para visitantes não logados)
// ═════════════════════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

// Verificação de e-mail
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Verificação de telefone (dev: código no log)
    Route::get('verify-phone', [PhoneVerificationController::class, 'notice'])->name('phone.verification.notice');
    Route::post('phone/verification-notification', [PhoneVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('phone.verification.send');
    Route::post('verify-phone', [PhoneVerificationController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('phone.verification.verify');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});


// ═════════════════════════════════════════════════════════════
// 2FA — AUTENTICAÇÃO EM DUAS ETAPAS
// ═════════════════════════════════════════════════════════════
// 2FA desativado (mantém rotas "dummy" só para não quebrar links antigos)
Route::middleware(['auth'])->prefix('2fa')->name('2fa.')->group(function () {
    Route::get('/', fn () => redirect()->route('cliente.configuracoes')->with('warning', '2FA está desativado.'))->name('index');
    Route::post('/', fn () => abort(404))->name('verify');
    Route::get('setup', fn () => redirect()->route('cliente.configuracoes')->with('warning', '2FA está desativado.'))->name('setup');
    Route::post('setup', fn () => abort(404))->name('confirm');
});


// ═════════════════════════════════════════════════════════════
// CARDÁPIO PÚBLICO (sem login)
// ═════════════════════════════════════════════════════════════
Route::prefix('cardapio')->name('cardapio.')->group(function () {
    Route::get('/', [ClienteCardapioController::class, 'index'])->name('index');
    Route::get('/buscar', [ClienteCardapioController::class, 'buscar'])->name('buscar');
    Route::get('/{prato:slug}', [ClienteCardapioController::class, 'show'])->name('show');
});


// ═════════════════════════════════════════════════════════════
// ÁREA DO CLIENTE (autenticado, role=2)
// ═════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:0,2'])->group(function () {

    // Carrinho e Checkout
    Route::get('/carrinho', [PedidoController::class, 'carrinho'])->name('cliente.carrinho');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('cliente.checkout');
    Route::post('/checkout/pagar', [CheckoutController::class, 'processarPagamento'])->name('cliente.checkout.pagar');
    Route::post('/enderecos', [CheckoutController::class, 'salvarEndereco'])->name('cliente.enderecos.store');

    // Pedidos
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('cliente.pedido.store');
    Route::get('/pedidos/{codigo}/pagamento', [PedidoController::class, 'pagamento'])->name('cliente.pedido.pagamento');
    Route::get('/pedidos/{codigo}/acompanhar', [PedidoController::class, 'acompanhar'])->name('cliente.pedido.acompanhar');
    Route::get('/pedidos/{codigo}/status', [PedidoController::class, 'statusApi'])->name('cliente.pedido.status-api');
    Route::get('/meus-pedidos', [PedidoController::class, 'historico'])->name('cliente.pedidos');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Configurações do Cliente
    Route::get('/configuracoes', [\App\Http\Controllers\Cliente\MinhaContaController::class, 'index'])->name('cliente.configuracoes');

    // Segurança (Minha Conta)
    Route::delete('/seguranca/sessoes/outras', [\App\Http\Controllers\Cliente\MinhaContaController::class, 'revokeOutrasSessoes'])->name('cliente.seguranca.sessoes.outras');
    Route::delete('/seguranca/sessoes/{id}', [\App\Http\Controllers\Cliente\MinhaContaController::class, 'revokeSessao'])->name('cliente.seguranca.sessoes.revoke');

    // LGPD
    Route::prefix('privacidade')->name('lgpd.')->group(function () {
        Route::get('/', [LgpdController::class, 'index'])->name('index');
        Route::post('/consentimento', [LgpdController::class, 'updateConsent'])->name('consent');
        Route::get('/exportar', [LgpdController::class, 'export'])->name('export');
        Route::delete('/excluir-conta', [LgpdController::class, 'anonymize'])->name('anonymize');
    });
});


// ═════════════════════════════════════════════════════════════
// COZINHA (role=1)
// ═════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:0,1', 'verified.contacts'])
    ->prefix('cozinha')
    ->name('cozinha.')
    ->group(function () {
        Route::get('/', [FilaController::class, 'index'])->name('fila');
        Route::patch('/pedidos/{pedido}/avancar', [FilaController::class, 'avancarStatus'])->name('pedido.avancar');
        Route::get('/api/pedidos', [FilaController::class, 'pedidosApi'])->name('api.pedidos');
    });


// ═════════════════════════════════════════════════════════════
// ENTREGADOR (role=3)
// ═════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:0,3'])
    ->prefix('entregador')
    ->name('entregador.')
    ->group(function () {
        Route::get('/', fn() => 'Painel do Entregador (Em breve)')->name('index');
    });


// ═════════════════════════════════════════════════════════════
// ADMIN (role=0)
// ═════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:0', 'verified.contacts'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Cardápio
        Route::prefix('cardapio')->name('cardapio.')->group(function () {
            Route::get('/', [AdminCardapioController::class, 'index'])->name('index');
            Route::post('/', [AdminCardapioController::class, 'store'])->name('store');
            Route::put('/{prato}', [AdminCardapioController::class, 'update'])->name('update');
            Route::patch('/{prato}/toggle', [AdminCardapioController::class, 'toggleDisponivel'])->name('toggle');
            Route::delete('/{prato}', [AdminCardapioController::class, 'destroy'])->name('destroy');
        });

        // Pedidos
        Route::get('/pedidos', [\App\Http\Controllers\Admin\PedidoController::class, 'index'])->name('pedidos.index');
        Route::patch('/pedidos/{pedido}/status', [\App\Http\Controllers\Admin\PedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');

        // Estoque
        Route::prefix('estoque')->name('estoque.')->group(function () {
            Route::get('/', [EstoqueController::class, 'index'])->name('index');
            Route::post('/', [EstoqueController::class, 'store'])->name('store');
            Route::put('/{insumo}', [EstoqueController::class, 'update'])->name('update');
            Route::delete('/{insumo}', [EstoqueController::class, 'destroy'])->name('destroy');
            Route::post('/{insumo}/movimentar', [EstoqueController::class, 'movimentar'])->name('movimentar');
        });

        Route::patch('/notificacoes/{notificacao}/read', [AdminNotificacaoController::class, 'markAsRead'])->name('notificacoes.read');

        // Funcionários
        Route::prefix('funcionarios')->name('funcionarios.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\FuncionarioController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\FuncionarioController::class, 'store'])->name('store');
            Route::put('/{user}', [\App\Http\Controllers\Admin\FuncionarioController::class, 'update'])->name('update');
            Route::delete('/{user}', [\App\Http\Controllers\Admin\FuncionarioController::class, 'destroy'])->name('destroy');
        });

        // Clientes
        Route::prefix('clientes')->name('clientes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ClienteController::class, 'index'])->name('index');
            Route::patch('/{user}/toggle-block', [\App\Http\Controllers\Admin\ClienteController::class, 'toggleBlock'])->name('toggleBlock');
        });

        // Configurações
        Route::get('/configuracoes', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'index'])->name('configuracoes.index');
        Route::post('/configuracoes', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'store'])->name('configuracoes.store');
    });


// ═════════════════════════════════════════════════════════════
// WEBHOOKS (sem CSRF — já excluído no bootstrap/app.php)
// ═════════════════════════════════════════════════════════════
Route::post('/webhooks/pagarme', [WebhookController::class, 'pagarme'])->name('webhook.pagarme');
