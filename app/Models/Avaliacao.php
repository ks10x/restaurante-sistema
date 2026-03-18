<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'pedido_id', 'prato_id', 'nota', 'comentario', 'resposta'
    ];

    protected $casts = [
        'nota' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function prato()
    {
        return $this->belongsTo(Prato::class);
    }
}