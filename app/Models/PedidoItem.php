<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id', 'prato_id', 'quantidade', 'preco_unitario',
        'opcoes_escolhidas', 'observacoes_item'
    ];

    protected $casts = [
        'quantidade'      => 'integer',
        'preco_unitario'  => 'decimal:2',
        'opcoes_escolhidas' => 'array', // Armazena as opções escolhidas como JSON
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function prato()
    {
        return $this->belongsTo(Prato::class);
    }

    // Accessor para calcular o subtotal do item
    public function getSubtotalAttribute(): float
    {
        return $this->quantidade * $this->preco_unitario;
    }
}