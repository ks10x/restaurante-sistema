<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'tipo', 'apelido', 'cep', 'logradouro', 'numero', 'complemento',
        'bairro', 'cidade', 'estado', 'uf', 'principal',
    ];

    protected $casts = [
        'principal' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getEnderecoCompletoAttribute(): string
    {
        $uf = $this->uf ?? $this->estado;

        return "{$this->logradouro}, {$this->numero} - {$this->bairro}, {$this->cidade}/{$uf}";
    }
}
