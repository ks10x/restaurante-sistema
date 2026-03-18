<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome', 'descricao', 'icone', 'cor', 'ordem', 'ativa'
    ];

    protected $casts = [
        'ativa' => 'boolean',
        'ordem' => 'integer',
    ];

    public function pratos()
    {
        return $this->hasMany(Prato::class);
    }

    public function scopeAtiva($query)
    {
        return $query->where('ativa', true);
    }

    public function scopeOrdenada($query)
    {
        return $query->orderBy('ordem')->orderBy('nome');
    }
}