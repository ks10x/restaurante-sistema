<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'tipo', 'cep', 'logradouro', 'numero', 'complemento',
        'bairro', 'cidade', 'uf', 'principal'
    ];

    protected $casts = [
        'principal' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor para montar o endereço completo
    public function getEnderecoCompletoAttribute(): string
    {
        return "{$this->logradouro}, {$this->numero} - {$this->bairro}, {$this->cidade}/{$this->uf}";
    }
}