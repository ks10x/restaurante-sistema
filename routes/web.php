<?php
// routes/web.php — Sistema de Restaurante
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{Auth as AuthCtrl};
use App\Http\Controllers\Cliente\{CardapioController, PedidoController, EnderecoController, AvaliacaoController};
use App\Http\Controllers\Cozinha\FilaController;
use App\Http\Controllers\Admin\{DashboardController, CardapioController as AdminCardapioController,
    EstoqueController, FuncionarioController, ClienteController, RelatorioController, ConfiguracaoController};

// ============================================================
// AUTH
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthCtrl\LoginController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthCtrl\LoginController::class, 'login']);
    Route::get('/cadastro', [AuthCtrl\RegisterController::class, 'showRegister'])->name('register');
    Route::post('/cadastro',[AuthCtrl\RegisterController::class, 'register']);
    Route::get('/auth/google',          [AuthCtrl\SocialController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthCtrl\SocialController::class, 'handleGoogleCallback']);
});
Route::post('/logout', [AuthCtrl\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// CLIENTE (requer login)
// ============================================================
Route::middleware(['auth', 'role:cliente'])->prefix('')->name('cliente.')->group(function () {

    // Cardápio (público — sem middleware de auth também, duplicado abaixo)
    Route::get('/cardapio',            [CardapioController::class, 'index'])->name('cardapio');
    Route::get('/cardapio/buscar',     [CardapioController::class, 'buscar'])->name('cardapio.buscar');
    Route::get('/cardapio/{prato}',    [CardapioController::class, 'show'])->name('cardapio.prato');

    // Carrinho & Pedido
    Route::get('/carrinho',            [PedidoController::class, 'carrinho'])->name('carrinho');
    Route::get('/checkout',            [PedidoController::class, 'checkout'])->name('checkout');
    Route::post('/pedidos',            [PedidoController::class, 'store'])->name('pedido.store');
    Route::get('/pedidos/{codigo}',    [PedidoController::class, 'acompanhar'])->name('pedido.acompanhar');
    Route::get('/pedidos/{codigo}/status', [PedidoController::class, 'statusApi'])->name('pedido.status');
    Route::get('/meus-pedidos',        [PedidoController::class, 'historico'])->name('pedidos.historico');

    // Endereços
    Route::resource('/enderecos', EnderecoController::class)->only(['index','store','update','destroy']);
    Route::post('/enderecos/{id}/principal', [EnderecoController::class, 'setarPrincipal'])->name('enderecos.principal');

    // Avaliações
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacao.store');
});

// Cardápio público (visitantes podem ver sem login)
Route::get('/', [CardapioController::class, 'index'])->name('home');
Route::get('/cardapio', [CardapioController::class, 'index']);
Route::get('/cardapio/buscar', [CardapioController::class, 'buscar']);
Route::get('/cardapio/{prato}', [CardapioController::class, 'show'])->name('cardapio.prato.show');

// ============================================================
// COZINHA
// ============================================================
Route::middleware(['auth', 'role:cozinha,admin'])->prefix('cozinha')->name('cozinha.')->group(function () {
    Route::get('/',                             [FilaController::class, 'index'])->name('fila');
    Route::get('/pedidos',                      [FilaController::class, 'pedidosApi'])->name('pedidos.api');
    Route::patch('/pedidos/{pedido}/avancar',   [FilaController::class, 'avancarStatus'])->name('pedido.avancar');
    Route::patch('/pedidos/{pedido}/cancelar',  [FilaController::class, 'cancelar'])->name('pedido.cancelar');
});

// ============================================================
// ADMINISTRADOR
// ============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kpis', [DashboardController::class, 'kpisApi'])->name('kpis.api');

    // Cardápio
    Route::prefix('cardapio')->name('cardapio.')->group(function () {
        Route::get('/',                          [AdminCardapioController::class, 'index'])->name('index');
        Route::post('/',                         [AdminCardapioController::class, 'store'])->name('store');
        Route::put('/{prato}',                   [AdminCardapioController::class, 'update'])->name('update');
        Route::delete('/{prato}',                [AdminCardapioController::class, 'destroy'])->name('destroy');
        Route::patch('/{prato}/toggle',          [AdminCardapioController::class, 'toggleDisponivel'])->name('toggle');
        Route::post('/{prato}/restore',          [AdminCardapioController::class, 'restore'])->name('restore');
        Route::get('/categorias',                [AdminCardapioController::class, 'categorias'])->name('categorias');
        Route::post('/categorias',               [AdminCardapioController::class, 'storeCategoria'])->name('categorias.store');
    });

    // Estoque
    Route::prefix('estoque')->name('estoque.')->group(function () {
        Route::get('/',                          [EstoqueController::class, 'index'])->name('index');
        Route::post('/',                         [EstoqueController::class, 'store'])->name('store');
        Route::put('/{insumo}',                  [EstoqueController::class, 'update'])->name('update');
        Route::post('/{insumo}/movimentar',      [EstoqueController::class, 'movimentar'])->name('movimentar');
        Route::get('/{insumo}/historico',        [EstoqueController::class, 'historico'])->name('historico');
        Route::get('/criticos',                  [EstoqueController::class, 'criticos'])->name('criticos');
    });

    // Funcionários
    Route::resource('funcionarios', FuncionarioController::class)->except(['show']);

    // Clientes
    Route::get('/clientes',                      [ClienteController::class, 'index'])->name('clientes');
    Route::get('/clientes/{user}',               [ClienteController::class, 'show'])->name('clientes.show');
    Route::patch('/clientes/{user}/status',      [ClienteController::class, 'toggleStatus'])->name('clientes.status');

    // Pedidos
    Route::get('/pedidos',                       [DashboardController::class, 'pedidos'])->name('pedidos');
    Route::get('/pedidos/{pedido}',              [DashboardController::class, 'pedidoDetalhe'])->name('pedidos.show');
    Route::patch('/pedidos/{pedido}/cancelar',   [DashboardController::class, 'cancelarPedido'])->name('pedidos.cancelar');

    // Relatórios
    Route::get('/relatorios/vendas',             [RelatorioController::class, 'vendas'])->name('relatorios.vendas');
    Route::get('/relatorios/pratos',             [RelatorioController::class, 'pratos'])->name('relatorios.pratos');
    Route::get('/relatorios/clientes',           [RelatorioController::class, 'clientes'])->name('relatorios.clientes');
    Route::get('/relatorios/estoque',            [RelatorioController::class, 'estoque'])->name('relatorios.estoque');
    Route::get('/relatorios/exportar',           [RelatorioController::class, 'exportar'])->name('relatorios.exportar');

    // Configurações
    Route::get('/configuracoes',                 [ConfiguracaoController::class, 'index'])->name('configuracoes');
    Route::post('/configuracoes',                [ConfiguracaoController::class, 'save'])->name('configuracoes.save');
    Route::resource('/configuracoes/horarios',   ConfiguracaoController::class)->only(['update']);
    Route::resource('/cupons', \App\Http\Controllers\Admin\CupomController::class)->except(['show']);
});