<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
 
class Pedido extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'codigo','user_id','endereco_id','tipo_entrega','status',
        'subtotal','taxa_entrega','desconto','total',
        'pagamento_metodo','pagamento_status','pagamento_ref','troco_para',
        'observacoes','tempo_estimado',
        'confirmado_em','producao_em','saiu_em','entregue_em','cancelado_em',
        'cancelado_por','motivo_cancelamento',
    ];
 
    protected $casts = [
        'subtotal'      => 'decimal:2',
        'taxa_entrega'  => 'decimal:2',
        'desconto'      => 'decimal:2',
        'total'         => 'decimal:2',
        'confirmado_em' => 'datetime',
        'producao_em'   => 'datetime',
        'saiu_em'       => 'datetime',
        'entregue_em'   => 'datetime',
        'cancelado_em'  => 'datetime',
    ];
 
    // Labels de status para exibição
    public const STATUS_LABELS = [
        'aguardando_pagamento' => ['label' => 'Aguardando Pagamento', 'cor' => 'gray'],
        'confirmado'           => ['label' => 'Confirmado',           'cor' => 'blue'],
        'em_producao'          => ['label' => 'Em Produção',          'cor' => 'amber'],
        'saindo_entrega'       => ['label' => 'Saindo para Entrega',  'cor' => 'orange'],
        'entregue'             => ['label' => 'Entregue',             'cor' => 'green'],
        'cancelado'            => ['label' => 'Cancelado',            'cor' => 'red'],
        'reembolsado'          => ['label' => 'Reembolsado',          'cor' => 'purple'],
    ];
 
    protected static function booted(): void {
        static::creating(function (Pedido $pedido) {
            $pedido->codigo = strtoupper(Str::random(8));
        });
    }
 
    public function usuario()    { return $this->belongsTo(User::class, 'user_id'); }
    public function endereco()   { return $this->belongsTo(Endereco::class); }
    public function itens()      { return $this->hasMany(PedidoItem::class); }
    public function historico()  { return $this->hasMany(PedidoStatusHistorico::class)->orderBy('created_at'); }
 
    public function getStatusLabelAttribute(): string {
        return self::STATUS_LABELS[$this->status]['label'] ?? $this->status;
    }
 
    public function getStatusCorAttribute(): string {
        return self::STATUS_LABELS[$this->status]['cor'] ?? 'gray';
    }
 
    public function avancarStatus(int $userId = null): bool {
        $fluxo = ['confirmado','em_producao','saindo_entrega','entregue'];
        $atual  = array_search($this->status, $fluxo);
        if ($atual === false || $atual >= count($fluxo) - 1) return false;
 
        $proximo = $fluxo[$atual + 1];
        $campo   = match($proximo) {
            'em_producao'    => 'producao_em',
            'saindo_entrega' => 'saiu_em',
            'entregue'       => 'entregue_em',
            default          => null,
        };
 
        $this->update(array_filter([
            'status' => $proximo,
            $campo   => now(),
        ]));
 
        // Registrar histórico
        $this->historico()->create(['status' => $proximo, 'user_id' => $userId]);
 
        // Disparar evento para WebSocket
        event(new \App\Events\PedidoStatusAtualizado($this));
 
        return true;
    }
}