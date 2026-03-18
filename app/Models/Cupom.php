<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo', 'tipo_desconto', 'valor_desconto', 'valor_minimo_pedido',
        'usos_maximos', 'usos_atuais', 'valido_a_partir_de', 'expira_em',
        'ativo', 'primeira_compra'
    ];

    protected $casts = [
        'valor_desconto'      => 'decimal:2',
        'valor_minimo_pedido' => 'decimal:2',
        'usos_maximos'        => 'integer',
        'usos_atuais'         => 'integer',
        'valido_a_partir_de'  => 'datetime',
        'expira_em'           => 'datetime',
        'ativo'               => 'boolean',
        'primeira_compra'     => 'boolean',
    ];

    public function usos()
    {
        return $this->hasMany(CupomUso::class);
    }

    public function getEstaValidoAttribute(): bool
    {
        $agora = now();

        if (!$this->ativo) return false;
        if ($this->expira_em && $agora->isAfter($this->expira_em)) return false;
        if ($this->valido_a_partir_de && $agora->isBefore($this->valido_a_partir_de)) return false;
        if ($this->usos_maximos && $this->usos_atuais >= $this->usos_maximos) return false;

        return true;
    }
}