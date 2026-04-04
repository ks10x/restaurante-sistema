<?php

namespace App\Services;
 
use App\Models\{Pedido, Prato, User, Cupom, Insumo};
use App\Events\NovoPedidoCriado;
use Illuminate\Support\Facades\DB;
 
class PedidoService
{
    public function criar(User $user, array $dados): Pedido
    {
        return DB::transaction(function () use ($user, $dados) {
            $itens     = collect($dados['itens']);
            $subtotal  = 0;
 
            // Calcular subtotal e validar pratos
            $itensPronto = $itens->map(function ($item) use (&$subtotal) {
                $prato = Prato::findOrFail($item['prato_id']);
                abort_if(!$prato->disponivel || !$prato->ativo, 422, "Prato {$prato->nome} indisponível.");
 
                $preco    = $prato->preco_ativo;
                $itemTotal = $preco * $item['quantidade'];
                $subtotal += $itemTotal;
 
                return [
                    'prato_id'      => $prato->id,
                    'nome_prato'    => $prato->nome,
                    'preco_unitario'=> $preco,
                    'quantidade'    => $item['quantidade'],
                    'subtotal'      => $itemTotal,
                    'opcoes'        => $item['opcoes'] ?? null,
                    'observacao'    => $item['observacao'] ?? null,
                ];
            });
 
            // Aplicar cupom
            $desconto = 0;
            if (!empty($dados['cupom_codigo'])) {
                $cupom = $this->validarCupom($dados['cupom_codigo'], $user->id, $subtotal);
                $desconto = $this->calcularDesconto($cupom, $subtotal);
            }
 
            $taxaEntrega = ($dados['tipo_entrega'] === 'entrega')
                ? 5.00
                : 0;
 
            $total = max(0, $subtotal + $taxaEntrega - $desconto);
 
            // Criar pedido
            $pedido = Pedido::create([
                'user_id'          => $user->id,
                'endereco_id'      => $dados['endereco_id'] ?? null,
                'tipo_entrega'     => $dados['tipo_entrega'],
                'status'           => 'confirmado',
                'subtotal'         => $subtotal,
                'taxa_entrega'     => $taxaEntrega,
                'desconto'         => $desconto,
                'total'            => $total,
                'pagamento_metodo' => $dados['pagamento_metodo'],
                'pagamento_status' => 'aprovado', // integrar gateway real
                'troco_para'       => $dados['troco_para'] ?? null,
                'observacoes'      => $dados['observacoes'] ?? null,
                'tempo_estimado'   => (int) config_val('tempo_estimado_entrega', 45),
                'confirmado_em'    => now(),
            ]);
 
            $pedido->itens()->createMany($itensPronto);
 
            // Histórico inicial
            $pedido->historico()->create(['status' => 'confirmado', 'user_id' => $user->id]);
 
            // Disparar evento WebSocket (cozinha recebe em tempo real)
            event(new NovoPedidoCriado($pedido->load('itens', 'usuario')));
 
            return $pedido;
        });
    }
 
    private function validarCupom(string $codigo, int $userId, float $subtotal): Cupom {
        $cupom = Cupom::where('codigo', $codigo)->where('ativo', 1)->firstOrFail();
        abort_if($cupom->validade_fim && now()->isAfter($cupom->validade_fim), 422, 'Cupom expirado.');
        abort_if($cupom->uso_maximo && $cupom->total_uso >= $cupom->uso_maximo, 422, 'Cupom esgotado.');
        if ($cupom->pedido_minimo) {
            abort_if($subtotal < $cupom->pedido_minimo, 422, "Pedido mínimo para este cupom: R$ {$cupom->pedido_minimo}.");
        }
        return $cupom;
    }
 
    private function calcularDesconto(Cupom $cupom, float $subtotal): float {
        return match($cupom->tipo) {
            'percentual'  => round($subtotal * ($cupom->valor / 100), 2),
            'fixo'        => min($cupom->valor, $subtotal),
            'frete_gratis'=> 5.00,
            default       => 0,
        };
    }
}
 
