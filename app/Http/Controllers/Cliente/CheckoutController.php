<?php

namespace App\Http\Controllers\Cliente; // Define que está na subpasta Cliente

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Aqui você retornará a view de checkout
        return view('checkout.index');
    }

    public function processarPagamento(Request $request) 
{
    $pagarme = new \PagarMe\Client('SUA_API_KEY_AQUI');

    $transaction = $pagarme->transactions()->create([
        'amount' => $request->total * 100, // Valor em centavos
        'payment_method' => 'pix',
        'postback_url' => route('webhook.pagarme'), // Onde o Pagar.me avisa se pagou
        'customer' => [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'type' => 'individual',
            'country' => 'br',
            'documents' => [
                [
                    'type' => 'cpf',
                    'number' => '00000000000' // Pegar do perfil do usuário
                ]
            ]
        ]
    ]);

    // Dentro do método de processar pagamento no CheckoutController
$order = Order::create([
    'user_id' => auth()->id(),
    'id_unico' => 'PED-' . strtoupper(uniqid()),
    'valor_total' => $totalCarrinho,
    'metodo_pagamento' => 'pix',
    'endereco_entrega' => auth()->user()->address,
    'status' => 'pedido enviado'
]);

foreach ($carrinho as $item) {
    OrderItem::create([
        'order_id' => $order->id,
        'produto_nome' => $item->name,
        'quantidade' => $item->quantity,
        'preco_unitario' => $item->price,
        'subtotal' => $item->quantity * $item->price
    ]);
}

    // Se for PIX, o Pagar.me retorna o 'pix_qr_code' e a 'pix_expiration_date'
    return view('checkout.sucesso', ['qr_code' => $transaction->pix_qr_code]);
}
}