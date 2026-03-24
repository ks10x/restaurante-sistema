<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handlePagarme(Request $request)
    {
        // O Pagar.me envia os dados no corpo da requisição
        $data = $request->all();

        // Log para debug (importante no início)
        Log::info('Webhook Pagar.me recebido', $data);

        // Verificamos se o evento é de pagamento confirmado
        if ($data['event'] === 'transaction.status_changed' && $data['current_status'] === 'paid') {
            
            $order = Order::where('pagamento_id', $data['id'])->first();

            if ($order) {
                // Atualiza para o status que dispara o acompanhamento na sua View
                $order->update([
                    'status' => 'pedido enviado'
                ]);
                
                return response()->json(['status' => 'success'], 200);
            }
        }

        return response()->json(['status' => 'ignored'], 200);
    }
}