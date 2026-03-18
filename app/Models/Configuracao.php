<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    // Não usa $fillable, pois usamos um método estático para buscar
    protected $table = 'configuracoes';

    public $timestamps = false; // Tabela não tem created_at/updated_at

    /**
     * Busca uma configuração pela chave.
     */
    public static function get(string $chave, $default = null)
    {
        $config = static::where('chave', $chave)->first();
        return $config ? $config->valor : $default;
    }

    /**
     * Salva ou atualiza uma configuração.
     */
    public static function set(string $chave, $valor): void
    {
        static::updateOrCreate(['chave' => $chave], ['valor' => $valor]);
    }
}