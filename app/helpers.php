<?php

use App\Models\Configuracao;

if (!function_exists('config_val')) {
    /**
     * Busca um valor da tabela de configurações ou retorna um padrão.
     */
    function config_val($chave, $padrao = null)
    {
        // Tenta buscar a chave no banco de dados
        $config = Configuracao::where('chave', $chave)->first();

        // Se encontrar, retorna o valor. Se não, retorna o padrão.
        return $config ? $config->valor : $padrao;
    }
}