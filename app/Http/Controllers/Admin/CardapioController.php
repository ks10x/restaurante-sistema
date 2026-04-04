<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\{Prato, Categoria, Insumo};
use App\Services\PratoEstoqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
 
class CardapioController extends Controller
{
    public function index(Request $request) {
        $categoriaId = $request->query('categoria');

        if ($categoriaId !== null) {
            $request->validate([
                'categoria' => 'nullable|integer|exists:categorias,id',
            ]);
        }

        $pratosQuery = Prato::withTrashed()
            ->with(['categoria', 'insumos:id,nome,unidade'])
            ->orderBy('categoria_id')
            ->orderBy('ordem')
            ->when(filled($categoriaId), fn ($q) => $q->where('categoria_id', (int) $categoriaId));

        $pratos = $pratosQuery
            ->paginate(20)
            ->appends($request->query());
        $categorias = Categoria::orderBy('ordem')->get();
        $insumos = Insumo::ativos()->orderBy('nome')->get(['id', 'nome', 'unidade', 'categoria']);

        return view('admin.cardapio.index', compact('pratos', 'categorias', 'insumos'));
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
            'insumos.*.id'     => 'required_with:insumos|exists:insumos,id',
            'insumos.*.quantidade' => 'required_with:insumos|numeric|min:0.001',
        ]);

        $data['slug'] = $this->generateUniqueSlug($data['nome']);
 
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('pratos', 'public');
        }
 
        $prato = Prato::create($data);
 
        if ($request->ingredientes) {
            $prato->ingredientes()->createMany($request->ingredientes);
        }
 
        $this->syncInsumos($prato, $request->input('insumos', []));

        app(PratoEstoqueService::class)->syncAtivoForPrato($prato);
 
        return response()->json(['success' => true, 'prato' => $prato->load(['categoria', 'insumos'])]);
    }
 
    public function update(Request $request, Prato $prato) {
        // Similar ao store com validação completa
        $data = $request->validate([
            'categoria_id'     => 'sometimes|exists:categorias,id',
            'nome'             => 'sometimes|string|max:120',
            'descricao'        => 'sometimes|string',
            'preco'            => 'sometimes|numeric|min:0.01',
            'preco_promocional'=> 'nullable|numeric|min:0',
            'imagem'           => 'nullable|image|max:2048',
            'disponivel'       => 'sometimes|boolean',
            'destaque'         => 'sometimes|boolean',
            'insumos'          => 'nullable|array',
            'insumos.*.id'     => 'required_with:insumos|exists:insumos,id',
            'insumos.*.quantidade' => 'required_with:insumos|numeric|min:0.001',
        ]);
 
        if (array_key_exists('nome', $data) && $data['nome'] !== $prato->nome) {
            $data['slug'] = $this->generateUniqueSlug($data['nome'], $prato->id);
        }

        if ($request->hasFile('imagem')) {
            if ($prato->imagem) {
                Storage::disk('public')->delete($prato->imagem);
            }

            $data['imagem'] = $request->file('imagem')->store('pratos', 'public');
        }

        $prato->update($data);
        $this->syncInsumos($prato, $request->input('insumos', []));

        app(PratoEstoqueService::class)->syncAtivoForPrato($prato);

        return response()->json(['success' => true, 'prato' => $prato->fresh(['categoria', 'insumos'])]);
    }
 
    public function toggleDisponivel(Prato $prato) {
        $prato->update(['disponivel' => !$prato->disponivel]);
        return response()->json(['disponivel' => $prato->disponivel]);
    }
 
    public function destroy(Prato $prato) {
        $prato->delete(); // soft delete
        return response()->json(['success' => true]);
    }

    private function generateUniqueSlug(string $nome, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($nome) ?: 'prato';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Prato::withTrashed()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function syncInsumos(Prato $prato, array $insumos): void
    {
        $syncData = collect($insumos)
            ->filter(fn ($insumo) => filled($insumo['id'] ?? null) && filled($insumo['quantidade'] ?? null))
            ->mapWithKeys(fn ($insumo) => [
                $insumo['id'] => ['quantidade' => $insumo['quantidade']],
            ])
            ->all();

        $prato->insumos()->sync($syncData);
    }
}
