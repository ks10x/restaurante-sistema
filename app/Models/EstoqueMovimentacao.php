<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstoqueMovimentacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'insumo_id', 'user_id', 'tipo', 'quantidade',
        'quantidade_anterior', 'quantidade_posterior', 'motivo'
    ];

    protected $casts = [
        'quantidade'           => 'decimal:3',
        'quantidade_anterior'  => 'decimal:3',
        'quantidade_posterior' => 'decimal:3',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}