<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
 
class Prato extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'categoria_id','nome','slug','descricao','preco','preco_promocional',
        'imagem','imagens_extras','calorias','tempo_preparo','porcao',
        'ativo','disponivel','destaque','ordem','avaliacao_media','total_avaliacoes',
    ];
 
    protected $casts = [
        'imagens_extras' => 'array',
        'preco'            => 'decimal:2',
        'preco_promocional'=> 'decimal:2',
        'ativo'            => 'boolean',
        'disponivel'       => 'boolean',
        'destaque'         => 'boolean',
    ];
 
    public function categoria()    { return $this->belongsTo(Categoria::class); }
    public function ingredientes() { return $this->hasMany(PratoIngrediente::class); }
    public function opcoes()       { return $this->hasMany(PratoOpcao::class); }
    public function insumos()      { return $this->belongsToMany(Insumo::class, 'prato_insumos')->withPivot('quantidade'); }
    public function avaliacoes()   { return $this->hasMany(Avaliacao::class); }
 
    public function getPrecoAtivoAttribute(): float {
        return $this->preco_promocional ?? $this->preco;
    }
 
    public function getImagemUrlAttribute(): string {
        return $this->imagem
            ? asset('storage/' . $this->imagem)
            : asset('images/prato-placeholder.jpg');
    }
 
    public function getAlergenicosAttribute(): array {
        return $this->ingredientes
            ->where('alergenico', true)
            ->pluck('tipo_alergenico')
            ->unique()
            ->values()
            ->toArray();
    }
 
    public function scopeDisponivel($q)  { return $q->where('ativo', 1)->where('disponivel', 1); }
    public function scopeDestaques($q)   { return $q->where('destaque', 1); }
    public function scopeByCategoria($q, $catId) { return $q->where('categoria_id', $catId); }
    public function scopeComprometidos(Builder $query)
    {
        return $query->whereHas('insumos', function (Builder $subQuery) {
            $subQuery->whereColumn('insumos.quantidade_atual', '<=', 'insumos.quantidade_minima');
        });
    }
}
