<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Cliente\CardapioController;
use App\Http\Controllers\Cliente\CheckoutController;

// Rota Pública
Route::get('/', [CardapioController::class, 'index'])->name('cardapio');

// Redireciona o login para o cardápio
Route::get('/cardapio', [CardapioController::class, 'index'])->name('cardapio.index');

// Rotas Protegidas (Apenas usuários logados)
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
    return redirect()->route('cardapio');
    })->name('dashboard');

    // Perfil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        Route::patch('/profile/endereco', 'updateAddress')->name('profile.address.update');
    });

    // Pedidos
    Route::get('/meus-pedidos', [OrderController::class, 'index'])->name('orders.index');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/processar', [CheckoutController::class, 'processarPagamento'])->name('checkout.process');
});

// Webhook (Fora do Middleware Auth, pois o Pagar.me não está logado no seu site)
Route::post('/webhooks/pagarme', [WebhookController::class, 'handlePagarme'])->name('webhook.pagarme');

require __DIR__.'/auth.php';