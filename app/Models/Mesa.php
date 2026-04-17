<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'capacidade',
        'status',
        'token_hash',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function getUrlAttribute()
    {
        return url('/m/' . $this->token_hash);
    }
}
