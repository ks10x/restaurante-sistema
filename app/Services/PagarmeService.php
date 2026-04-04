<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagarmeService
{
    private string $apiKey;

    private string $baseUrl = 'https://api.pagar.me/core/v5';

    public function __construct()
    {
        $this->apiKey = config('services.pagarme.secret_key', '');
    }

    public function criarPedidoPix(Pedido $pedido, User $user): array
    {
        $payload = $this->buildBasePayload($pedido, $user, [
            [
                'payment_method' => 'pix',
                'pix' => [
                    'expires_in' => 3600,
                    'additional_information' => [
                        [
                            'name' => 'Pedido',
                            'value' => $pedido->codigo,
                        ],
                    ],
                ],
                'amount' => $this->amountInCents($pedido->total),
            ],
        ]);

        try {
            $data = $this->sendOrder($payload, $pedido);
            $charge = $data['charges'][0] ?? [];
            $lastTransaction = $charge['last_transaction'] ?? [];

            $pedido->update([
                'pagamento_ref' => $charge['id'] ?? null,
            ]);

            return [
                'order_id'    => $data['id'] ?? null,
                'charge_id'   => $charge['id'] ?? null,
                'qr_code'     => $lastTransaction['qr_code'] ?? null,
                'qr_code_url' => $lastTransaction['qr_code_url'] ?? null,
                'expires_at'  => $lastTransaction['expires_at'] ?? null,
                'amount'      => $this->amountInCents($pedido->total),
            ];
        } catch (\Throwable $e) {
            Log::error('PagarmeService exception on PIX', [
                'message' => $e->getMessage(),
                'pedido' => $pedido->codigo,
            ]);

            return $this->simulatePix($pedido, $this->amountInCents($pedido->total));
        }
    }

    public function criarPedidoCartao(Pedido $pedido, User $user, array $cardData): array
    {
        if (blank($this->apiKey)) {
            throw new \RuntimeException('Configure PAGARME_SECRET_KEY para processar pagamentos com cartao.');
        }

        if (blank($cardData['card_token'] ?? null)) {
            throw new \RuntimeException('Token do cartao nao informado.');
        }

        $payload = $this->buildBasePayload($pedido, $user, [
            [
                'payment_method' => 'credit_card',
                'amount' => $this->amountInCents($pedido->total),
                'credit_card' => [
                    'installments' => (int) ($cardData['installments'] ?? 1),
                    'statement_descriptor' => $this->statementDescriptor(),
                    'card_token' => $cardData['card_token'],
                    'billing_address' => $this->shippingAddress($pedido),
                ],
            ],
        ]);

        $data = $this->sendOrder($payload, $pedido);
        $charge = $data['charges'][0] ?? [];
        $lastTransaction = $charge['last_transaction'] ?? [];
        $transactionStatus = $lastTransaction['status'] ?? null;
        $paidStatuses = ['paid', 'captured', 'authorized_pending_capture', 'waiting_capture'];
        $isApproved = in_array($transactionStatus, $paidStatuses, true) || ($charge['status'] ?? null) === 'paid';

        $pedido->update([
            'pagamento_ref' => $charge['id'] ?? null,
            'pagamento_status' => $isApproved ? 'aprovado' : 'pendente',
            'status' => $isApproved ? 'confirmado' : 'aguardando_pagamento',
            'confirmado_em' => $isApproved ? now() : null,
        ]);

        if ($isApproved) {
            $pedido->historico()->create([
                'status' => 'confirmado',
                'user_id' => $pedido->user_id,
            ]);
        }

        return [
            'order_id' => $data['id'] ?? null,
            'charge_id' => $charge['id'] ?? null,
            'status' => $transactionStatus ?? ($charge['status'] ?? 'pending'),
            'approved' => $isApproved,
            'message' => $isApproved
                ? 'Pagamento com cartao aprovado com sucesso.'
                : 'Pagamento enviado ao gateway. Aguarde a confirmacao.',
            'card' => [
                'brand' => $cardData['card_brand'] ?? null,
                'last_four' => $cardData['card_last_four'] ?? null,
                'installments' => (int) ($cardData['installments'] ?? 1),
            ],
        ];
    }

    private function buildBasePayload(Pedido $pedido, User $user, array $payments): array
    {
        return [
            'items' => $pedido->itens->map(fn ($item) => [
                'amount' => (int) round($item->preco_unitario * $item->quantidade * 100),
                'description' => $item->prato->nome ?? 'Item do pedido',
                'quantity' => $item->quantidade,
                'code' => (string) $item->prato_id,
            ])->toArray(),
            'customer' => [
                'name' => $this->customerName($user),
                'email' => $user->email,
                'type' => 'individual',
                'document' => $this->customerDocument($user),
                'phones' => [
                    'mobile_phone' => $this->customerPhone($user),
                ],
            ],
            'shipping' => [
                'amount' => $this->amountInCents($pedido->taxa_entrega),
                'description' => 'Entrega',
                'recipient_name' => $this->customerName($user),
                'address' => $this->shippingAddress($pedido),
            ],
            'payments' => $payments,
            'closed' => true,
        ];
    }

    private function sendOrder(array $payload, Pedido $pedido): array
    {
        if (blank($this->apiKey)) {
            throw new \RuntimeException('Configure PAGARME_SECRET_KEY para usar o gateway.');
        }

        $response = Http::withBasicAuth($this->apiKey, '')
            ->timeout(30)
            ->post("{$this->baseUrl}/orders", $payload);

        if ($response->failed()) {
            Log::error('Pagar.me API error', [
                'status' => $response->status(),
                'body' => $response->json(),
                'pedido' => $pedido->codigo,
            ]);

            $message = data_get($response->json(), 'message')
                ?? data_get($response->json(), 'errors.0.message')
                ?? 'Erro desconhecido no gateway.';

            throw new \RuntimeException($message);
        }

        return $response->json();
    }

    private function shippingAddress(Pedido $pedido): array
    {
        $endereco = $pedido->endereco;
        $estado = strtoupper($endereco->estado ?? $endereco->uf ?? 'SP');

        return [
            'line_1' => trim("{$endereco->numero}, {$endereco->logradouro}, {$endereco->bairro}"),
            'zip_code' => preg_replace('/\D/', '', $endereco->cep ?? ''),
            'city' => $endereco->cidade ?? 'Sao Paulo',
            'state' => $estado,
            'country' => 'BR',
        ];
    }

    private function customerName(User $user): string
    {
        $fullName = trim(($user->name ?? '') . ' ' . ($user->last_name ?? ''));

        return $fullName !== '' ? $fullName : 'Cliente Bella Cucina';
    }

    private function customerDocument(User $user): string
    {
        return preg_replace('/\D/', '', $user->cpf ?? $user->cpf_encrypted ?? '00000000000');
    }

    private function customerPhone(User $user): array
    {
        $phone = preg_replace('/\D/', '', $user->phone ?? '11999999999');
        $areaCode = strlen($phone) >= 10 ? substr($phone, 0, 2) : '11';
        $number = strlen($phone) >= 10 ? substr($phone, 2) : '999999999';

        return [
            'country_code' => '55',
            'area_code' => $areaCode,
            'number' => $number,
        ];
    }

    private function statementDescriptor(): string
    {
        return substr(preg_replace('/[^A-Z0-9 ]/', '', strtoupper(config('app.name', 'BELLA CUCINA'))), 0, 13) ?: 'BELLA CUCINA';
    }

    private function amountInCents(float|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    private function simulatePix(Pedido $pedido, int $amountInCents): array
    {
        $fakeCode = '00020126580014br.gov.bcb.pix0136'
            . bin2hex(random_bytes(18))
            . '5204000053039865802BR5925BELLA CUCINA RESTAURANTE6009SAO PAULO62070503***6304'
            . strtoupper(bin2hex(random_bytes(2)));

        return [
            'order_id' => 'sim_' . uniqid(),
            'charge_id' => 'sim_ch_' . uniqid(),
            'qr_code' => $fakeCode,
            'qr_code_url' => null,
            'expires_at' => now()->addHour()->toIso8601String(),
            'amount' => $amountInCents,
            'simulated' => true,
        ];
    }
}
