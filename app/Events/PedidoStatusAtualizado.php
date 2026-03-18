<?php

namespace App\Events;
 
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
 
class PedidoStatusAtualizado implements ShouldBroadcast
{
    use SerializesModels;
 
    public function __construct(public Pedido $pedido) {}
 
    public function broadcastOn(): array {
        return [
            new Channel('cozinha'),
            new Channel("cliente.{$this->pedido->user_id}"),
            new Channel('admin'),
        ];
    }
 
    public function broadcastAs(): string { return 'pedido.status'; }
 
    public function broadcastWith(): array {
        return [
            'id'          => $this->pedido->id,
            'codigo'      => $this->pedido->codigo,
            'status'      => $this->pedido->status,
            'status_label'=> $this->pedido->status_label,
        ];
    }
}