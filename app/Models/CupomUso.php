<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CupomUso extends Model
{
    use HasFactory;

    protected $fillable = [
        'cupom_id', 'user_id', 'pedido_id', 'desconto_aplicado'
    ];

    protected $casts = [
        'desconto_aplicado' => 'decimal:2',
    ];

    public function cupom()
    {
        return $this->belongsTo(Cupom::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}