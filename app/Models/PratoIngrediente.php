<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratoIngrediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'prato_id', 'nome', 'tipo_alergenico', 'alergenico', 'ordem'
    ];

    protected $casts = [
        'alergenico' => 'boolean',
        'ordem'      => 'integer',
    ];

    public function prato()
    {
        return $this->belongsTo(Prato::class);
    }
}