<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function pagarme(Request $request)
    {
        $data = $request->all();

        Log::info('Webhook Pagar.me recebido', $data);

        // Pagar.me v5 webhook format
        $type = $data['type'] ?? '';

        if ($type === 'charge.paid' || $type === 'order.paid') {
            $chargeId = $data['data']['id'] ?? null;

            if ($chargeId) {
                $pedido = Pedido::where('pagamento_ref', $chargeId)->first();

                if ($pedido && $pedido->pagamento_status !== 'aprovado') {
                    $pedido->update([
                        'pagamento_status' => 'aprovado',
                        'status'           => 'confirmado',
                        'confirmado_em'    => now(),
                    ]);

                    // Register status history
                    $pedido->historico()->create([
                        'status'  => 'confirmado',
                        'user_id' => $pedido->user_id,
                    ]);

                    Log::info('Pedido confirmado via webhook', ['codigo' => $pedido->codigo]);

                    // Dispatch event for kitchen
                    try {
                        event(new \App\Events\NovoPedidoCriado($pedido->load('itens', 'usuario')));
                    } catch (\Exception $e) {
                        Log::warning('Event dispatch failed: ' . $e->getMessage());
                    }

                    return response()->json(['status' => 'success'], 200);
                }
            }
        }

        return response()->json(['status' => 'ignored'], 200);
    }
}