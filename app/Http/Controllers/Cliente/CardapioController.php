<?php

namespace App\Http\Controllers\Cliente;
 
use App\Http\Controllers\Controller;
use App\Models\{Categoria, Prato, Configuracao};
use Illuminate\Http\Request;
 
class CardapioController extends Controller
{

    public function index(Request $request)
    {
        $categorias = Categoria::where('ativa', 1)
            ->orderBy('ordem')
            ->with(['pratos' => function($q) {
                $q->where('disponivel', 1)
                  ->orderBy('ordem')
                  ->with(['ingredientes', 'insumos']); // ← adicionado aqui
            }])
            ->get();

        $destaques = Prato::where('disponivel', 1) 
            ->where('destaque', 1)
            ->with('categoria')
            ->get();

        $config = [
            'pedido_minimo' => 30,
            'taxa_entrega'  => 8,
            'tempo_estimado'=> 45,
        ];

        return view('cliente.cardapio', compact('categorias', 'destaques', 'config'));
    }
    
    public function show(Prato $prato)
    {
        abort_if(!$prato->disponivel, 404);
        $prato->load('ingredientes', 'opcoes.itens', 'avaliacoes.usuario');
 
        $relacionados = Prato::disponivel()
            ->where('categoria_id', $prato->categoria_id)
            ->where('id', '!=', $prato->id)
            ->limit(4)->get();
 
        return view('cliente.prato', compact('prato', 'relacionados'));
    }
 
    public function buscar(Request $request)
    {
        $termo = $request->get('q');
        $pratos = Prato::disponivel()
            ->where(fn($q) => $q
                ->where('nome', 'LIKE', "%{$termo}%")
                ->orWhere('descricao', 'LIKE', "%{$termo}%")
            )
            ->with('categoria')
            ->limit(20)
            ->get();
 
        return response()->json($pratos);
    }
}