<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Campos necessários para registrar o que foi comprado
    protected $fillable = [
        'order_id',
        'produto_nome',
        'quantidade',
        'preco_unitario',
        'subtotal'
    ];

    protected $table = 'pedidos_itens';

    /**
     * Relacionamento: Este item pertence a um pedido específico.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}