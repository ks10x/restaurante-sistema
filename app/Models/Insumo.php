<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Insumo extends Model
{
    protected $fillable = [
        'nome','descricao','unidade','quantidade_atual','quantidade_minima',
        'quantidade_maxima','preco_unitario','fornecedor','categoria','codigo_barras','ativo',
    ];
 
    protected $casts = [
        'quantidade_atual'  => 'decimal:3',
        'quantidade_minima' => 'decimal:3',
        'preco_unitario'    => 'decimal:4',
        'ativo'             => 'boolean',
    ];
 
    public function movimentacoes() {
        return $this->hasMany(EstoqueMovimentacao::class);
    }
 
    public function pratos() {
        return $this->belongsToMany(Prato::class, 'prato_insumos')->withPivot('quantidade');
    }
 
    public function getNivelAttribute(): string {
        if ($this->quantidade_atual <= 0) return 'zerado';
        if ($this->quantidade_atual <= $this->quantidade_minima) return 'critico';
        if ($this->quantidade_atual <= $this->quantidade_minima * 1.5) return 'atencao';
        return 'ok';
    }
 
    public function getPercentualEstoqueAttribute(): float {
        if ($this->quantidade_minima == 0) return 100;
        return round(($this->quantidade_atual / $this->quantidade_minima) * 100, 1);
    }
 
    public function movimentar(string $tipo, float $quantidade, int $userId, array $extra = []): EstoqueMovimentacao {
        $anterior = $this->quantidade_atual;
        $posterior = match($tipo) {
            'entrada' => $anterior + $quantidade,
            'saida', 'perda' => max(0, $anterior - $quantidade),
            'ajuste' => $quantidade,
            default => $anterior,
        };
 
        $this->update(['quantidade_atual' => $posterior]);
 
        return $this->movimentacoes()->create(array_merge([
            'user_id'             => $userId,
            'tipo'                => $tipo,
            'quantidade'          => $quantidade,
            'quantidade_anterior' => $anterior,
            'quantidade_posterior'=> $posterior,
        ], $extra));
    }
}