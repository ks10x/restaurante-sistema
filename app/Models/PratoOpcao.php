<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PratoOpcao extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prato_id', 'nome', 'tipo', 'min_selecoes', 'max_selecoes', 'obrigatoria'
    ];

    protected $casts = [
        'obrigatoria' => 'boolean',
        'min_selecoes' => 'integer',
        'max_selecoes' => 'integer',
    ];

    public function prato()
    {
        return $this->belongsTo(Prato::class);
    }

    public function itens()
    {
        return $this->hasMany(PratoOpcaoItem::class);
    }
}