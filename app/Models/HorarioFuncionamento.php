<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioFuncionamento extends Model
{
    use HasFactory;

    protected $table = 'horarios_funcionamento';

    public $timestamps = false;

    protected $fillable = [
        'dia_semana', 'aberto', 'horario_abertura', 'horario_fechamento'
    ];

    protected $casts = [
        'aberto' => 'boolean',
    ];

    // Ex: 0 para Domingo, 1 para Segunda, etc.
    public function getNomeDiaAttribute(): string
    {
        $dias = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
        return $this->dia_semana !== null ? $dias[$this->dia_semana] : '';
    }
}