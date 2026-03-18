<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'titulo', 'mensagem', 'tipo', 'lida'
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }
}