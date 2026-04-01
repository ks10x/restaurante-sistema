<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracao;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $config = [
            'restaurante_nome' => Configuracao::get('restaurante_nome', 'Sistema Restaurante'),
            'restaurante_telefone' => Configuracao::get('restaurante_telefone', ''),
            'restaurante_aberto' => Configuracao::get('restaurante_aberto', '1'),
            'taxa_entrega_padrao' => Configuracao::get('taxa_entrega_padrao', '0.00'),
            'tempo_estimado_minutos' => Configuracao::get('tempo_estimado_minutos', '40'),
            'mensagem_aviso' => Configuracao::get('mensagem_aviso', ''),
        ];

        return view('admin.configuracoes', compact('config'));
    }

    public function store(Request $request)
    {
        $keys = [
            'restaurante_nome', 'restaurante_telefone', 'taxa_entrega_padrao', 
            'tempo_estimado_minutos', 'mensagem_aviso'
        ];

        foreach($keys as $key) {
            if($request->has($key)) {
                // Formatting delivery fee if it comes with commas
                $valor = $request->input($key);
                if($key == 'taxa_entrega_padrao') {
                    $valor = str_replace(',', '.', $valor);
                }
                Configuracao::set($key, $valor);
            }
        }

        $aberto = $request->has('restaurante_aberto') ? '1' : '0';
        Configuracao::set('restaurante_aberto', $aberto);

        return redirect()->back()->with('success', 'Configurações atualizadas no sistema.');
    }
}
