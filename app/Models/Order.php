<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos via CheckoutController
    protected $fillable = [
        'user_id',
        'id_unico',
        'status',
        'valor_total',
        'metodo_pagamento',
        'pagamento_id',
        'endereco_entrega',
        'cidade',
        'bairro'
    ];

    /**
     * Relacionamento: Um pedido pertence a um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Um pedido possui vários itens (Pizza, Bebida, etc).
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}