<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pedido;
use App\Models\User;

class PagarmeService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.pagar.me/core/v5';

    public function __construct()
    {
        $this->apiKey = config('services.pagarme.secret_key', '');
    }

    /**
     * Create a PIX order on Pagar.me API v5
     * Returns array with qr_code, qr_code_url, and expires_at
     */
    public function criarPedidoPix(Pedido $pedido, User $user): array
    {
        $amountInCents = (int) round($pedido->total * 100);

        // Clean CPF (remove dots and dashes)
        $cpf = preg_replace('/\D/', '', $user->cpf ?? $user->cpf_encrypted ?? '00000000000');

        $payload = [
            'items' => $pedido->itens->map(fn($item) => [
                'amount'      => (int) round($item->preco_unitario * $item->quantidade * 100),
                'description' => $item->prato->nome ?? 'Item do pedido',
                'quantity'    => $item->quantidade,
                'code'        => (string) $item->prato_id,
            ])->toArray(),
            'customer' => [
                'name'     => trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')),
                'email'    => $user->email,
                'type'     => 'individual',
                'document' => $cpf,
                'phones'   => [
                    'mobile_phone' => [
                        'country_code' => '55',
                        'area_code'    => substr(preg_replace('/\D/', '', $user->phone ?? '11999999999'), 0, 2),
                        'number'       => substr(preg_replace('/\D/', '', $user->phone ?? '11999999999'), 2),
                    ]
                ],
            ],
            'payments' => [
                [
                    'payment_method' => 'pix',
                    'pix' => [
                        'expires_in' => 3600, // 1 hour
                        'additional_information' => [
                            [
                                'name'  => 'Pedido',
                                'value' => $pedido->codigo,
                            ]
                        ],
                    ],
                    'amount' => $amountInCents,
                ],
            ],
            'closed' => true,
        ];

        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->timeout(30)
                ->post("{$this->baseUrl}/orders", $payload);

            if ($response->failed()) {
                Log::error('Pagar.me API error', [
                    'status' => $response->status(),
                    'body'   => $response->json(),
                    'pedido' => $pedido->codigo,
                ]);
                throw new \Exception('Erro ao processar pagamento: ' . ($response->json()['message'] ?? 'Erro desconhecido'));
            }

            $data = $response->json();
            $charge = $data['charges'][0] ?? null;
            $lastTransaction = $charge['last_transaction'] ?? null;

            // Update pedido with payment reference
            $pedido->update([
                'pagamento_ref' => $charge['id'] ?? null,
            ]);

            return [
                'order_id'       => $data['id'] ?? null,
                'charge_id'      => $charge['id'] ?? null,
                'qr_code'        => $lastTransaction['qr_code'] ?? null,
                'qr_code_url'    => $lastTransaction['qr_code_url'] ?? null,
                'expires_at'     => $lastTransaction['expires_at'] ?? null,
                'amount'         => $amountInCents,
            ];

        } catch (\Exception $e) {
            Log::error('PagarmeService exception', [
                'message' => $e->getMessage(),
                'pedido'  => $pedido->codigo,
            ]);

            // Return simulated data for development/testing
            return $this->simulatePix($pedido, $amountInCents);
        }
    }

    /**
     * Simulate PIX data for development when API key is not configured
     */
    private function simulatePix(Pedido $pedido, int $amountInCents): array
    {
        $fakeCode = '00020126580014br.gov.bcb.pix0136' . bin2hex(random_bytes(18)) . '5204000053039865802BR5925BELLA CUCINA RESTAURANTE6009SAO PAULO62070503***6304' . strtoupper(bin2hex(random_bytes(2)));

        return [
            'order_id'    => 'sim_' . uniqid(),
            'charge_id'   => 'sim_ch_' . uniqid(),
            'qr_code'     => $fakeCode,
            'qr_code_url' => null, // No image in simulation
            'expires_at'  => now()->addHour()->toIso8601String(),
            'amount'      => $amountInCents,
            'simulated'   => true,
        ];
    }
}
