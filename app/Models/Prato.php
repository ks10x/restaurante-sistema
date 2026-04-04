<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
 
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
        if (blank($this->imagem)) {
            return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';
        }

        if (str_starts_with($this->imagem, 'http://') || str_starts_with($this->imagem, 'https://')) {
            return $this->imagem;
        }

        if (Storage::disk('public')->exists($this->imagem)) {
            return asset('storage/' . ltrim($this->imagem, '/'));
        }

        return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=500&auto=format&fit=crop';
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
