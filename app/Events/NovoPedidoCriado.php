<?php

namespace App\Events;
 
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
 
class NovoPedidoCriado implements ShouldBroadcast
{
    use SerializesModels;
 
    public function __construct(public Pedido $pedido) {}
 
    public function broadcastOn(): array {
        return [
            new Channel('cozinha'),
            new Channel("cliente.{$this->pedido->user_id}"),
        ];
    }
 
    public function broadcastAs(): string { return 'novo.pedido'; }
 
    public function broadcastWith(): array {
        return [
            'id'     => $this->pedido->id,
            'codigo' => $this->pedido->codigo,
            'status' => $this->pedido->status,
            'total'  => $this->pedido->total,
            'itens'  => $this->pedido->itens->count(),
            'cliente'=> $this->pedido->usuario->name,
        ];
    }
}