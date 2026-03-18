<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratoOpcaoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prato_opcao_id', 'nome', 'descricao', 'preco_adicional', 'disponivel'
    ];

    protected $casts = [
        'preco_adicional' => 'decimal:2',
        'disponivel'      => 'boolean',
    ];

    public function opcao()
    {
        return $this->belongsTo(PratoOpcao::class, 'prato_opcao_id');
    }
}