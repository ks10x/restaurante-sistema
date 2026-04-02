<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $fillable = [
        'user_id', 'titulo', 'mensagem', 'tipo', 'dados', 'lida', 'lida_em'
    ];

    protected $casts = [
        'lida' => 'boolean',
        'dados' => 'array',
        'lida_em' => 'datetime',
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
