<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\{Prato, Categoria, Insumo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 
class CardapioController extends Controller
{
    public function index() {
        $pratos = Prato::withTrashed()->with('categoria')->orderBy('categoria_id')->orderBy('ordem')->paginate(20);
        $categorias = Categoria::orderBy('ordem')->get();
        return view('admin.cardapio.index', compact('pratos', 'categorias'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_id'     => 'required|exists:categorias,id',
            'nome'             => 'required|string|max:120',
            'descricao'        => 'required|string',
            'preco'            => 'required|numeric|min:0.01',
            'preco_promocional'=> 'nullable|numeric|min:0',
            'imagem'           => 'nullable|image|max:2048',
            'calorias'         => 'nullable|integer|min:0',
            'tempo_preparo'    => 'nullable|integer|min:1',
            'porcao'           => 'nullable|string|max:60',
            'disponivel'       => 'boolean',
            'destaque'         => 'boolean',
            'ingredientes'     => 'nullable|array',
            'insumos'          => 'nullable|array',
        ]);
 
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('pratos', 'public');
        }
 
        $prato = Prato::create($data);
 
        if ($request->ingredientes) {
            $prato->ingredientes()->createMany($request->ingredientes);
        }
 
        if ($request->insumos) {
            $prato->insumos()->sync($request->insumos);
        }
 
        return response()->json(['success' => true, 'prato' => $prato->load('categoria')]);
    }
 
    public function update(Request $request, Prato $prato) {
        // Similar ao store com validação completa
        $data = $request->validate([
            'categoria_id'     => 'sometimes|exists:categorias,id',
            'nome'             => 'sometimes|string|max:120',
            'descricao'        => 'sometimes|string',
            'preco'            => 'sometimes|numeric|min:0.01',
            'preco_promocional'=> 'nullable|numeric|min:0',
            'disponivel'       => 'sometimes|boolean',
            'destaque'         => 'sometimes|boolean',
        ]);
 
        $prato->update($data);
        return response()->json(['success' => true, 'prato' => $prato->fresh('categoria')]);
    }
 
    public function toggleDisponivel(Prato $prato) {
        $prato->update(['disponivel' => !$prato->disponivel]);
        return response()->json(['disponivel' => $prato->disponivel]);
    }
 
    public function destroy(Prato $prato) {
        $prato->delete(); // soft delete
        return response()->json(['success' => true]);
    }
}