<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $table = 'pedido_itens';

    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'prato_id',
        'nome_prato',
        'preco_unitario',
        'quantidade',
        'subtotal',
        'opcoes',
        'observacao',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'opcoes' => 'array',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function prato()
    {
        return $this->belongsTo(Prato::class)->withTrashed();
    }

}
