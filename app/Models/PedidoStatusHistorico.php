<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoStatusHistorico extends Model
{
    use HasFactory;

    protected $table = 'pedido_status_historico';

    protected $fillable = [
        'pedido_id', 'status', 'user_id', 'observacao'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
