<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\{Insumo, EstoqueMovimentacao};
use Illuminate\Http\Request;
 
class EstoqueController extends Controller
{
    public function index()
    {
        $insumos = Insumo::where('ativo', 1)
            ->orderByRaw("CASE WHEN quantidade_atual <= quantidade_minima THEN 0 ELSE 1 END")
            ->orderBy('nome')
            ->paginate(25);
 
        $resumo = [
            'total'   => Insumo::where('ativo', 1)->count(),
            'criticos'=> Insumo::where('ativo', 1)->whereColumn('quantidade_atual', '<=', 'quantidade_minima')->count(),
            'zerados' => Insumo::where('ativo', 1)->where('quantidade_atual', 0)->count(),
        ];
 
        return view('admin.estoque.index', compact('insumos', 'resumo'));
    }
 
    public function movimentar(Request $request, Insumo $insumo)
    {
        $request->validate([
            'tipo'       => 'required|in:entrada,saida,ajuste,perda',
            'quantidade' => 'required|numeric|min:0.001',
            'motivo'     => 'nullable|string|max:255',
        ]);
 
        $mov = $insumo->movimentar(
            $request->tipo,
            $request->quantidade,
            auth()->id(),
            ['motivo' => $request->motivo]
        );
 
        return response()->json([
            'success'          => true,
            'quantidade_atual' => $insumo->fresh()->quantidade_atual,
            'nivel'            => $insumo->fresh()->nivel,
        ]);
    }
}
 