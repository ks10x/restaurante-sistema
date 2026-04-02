<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insumo;
use App\Models\Notificacao;
use App\Models\Prato;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function index()
    {
        $insumos = Insumo::ativos()
            ->with(['pratos:id,nome'])
            ->orderByRaw("CASE WHEN quantidade_atual <= quantidade_minima THEN 0 ELSE 1 END")
            ->orderBy('nome')
            ->paginate(25);

        $resumo = $this->buildResumo();
        $categorias = Insumo::ativos()
            ->whereNotNull('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        return view('admin.estoque.index', compact('insumos', 'resumo', 'categorias'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateInsumo($request);
        $insumo = Insumo::create($data);

        $this->notifyIfCritical($insumo);

        return response()->json([
            'success' => true,
            'insumo' => $insumo->fresh(),
        ]);
    }

    public function update(Request $request, Insumo $insumo): JsonResponse
    {
        $data = $this->validateInsumo($request, true);
        $insumo->update($data);
        $insumo = $insumo->fresh();

        $this->notifyIfCritical($insumo);

        return response()->json([
            'success' => true,
            'insumo' => $insumo,
        ]);
    }

    public function destroy(Insumo $insumo): JsonResponse
    {
        if ($insumo->pratos()->exists()) {
            return response()->json([
                'message' => 'Esse ingrediente está vinculado a pratos e não pode ser excluído agora.',
            ], 422);
        }

        $insumo->delete();

        return response()->json(['success' => true]);
    }

    public function movimentar(Request $request, Insumo $insumo): JsonResponse
    {
        $data = $request->validate([
            'tipo' => 'required|in:entrada,saida,ajuste,perda',
            'quantidade' => 'required|numeric|min:0.001',
            'observacoes' => 'nullable|string|max:255',
        ]);

        $insumo->movimentar(
            $data['tipo'],
            (float) $data['quantidade'],
            (int) auth()->id(),
            ['motivo' => $data['observacoes'] ?? null]
        );

        $insumo = $insumo->fresh(['pratos:id,nome']);
        $this->notifyIfCritical($insumo);

        return response()->json([
            'success' => true,
            'quantidade_atual' => $insumo->quantidade_atual,
            'nivel' => $insumo->nivel,
            'pratos_comprometidos' => $this->getCompromisedDishNamesForInsumo($insumo),
        ]);
    }

    private function validateInsumo(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return $request->validate([
            'nome' => [$required, 'string', 'max:120'],
            'descricao' => 'nullable|string',
            'categoria' => 'nullable|string|max:60',
            'unidade' => [$required, 'in:kg,g,l,ml,un,cx,pct'],
            'quantidade_atual' => [$required, 'numeric', 'min:0'],
            'quantidade_minima' => [$required, 'numeric', 'min:0'],
            'quantidade_maxima' => 'nullable|numeric|gte:quantidade_minima',
            'preco_unitario' => [$required, 'numeric', 'min:0'],
            'fornecedor' => 'nullable|string|max:120',
            'codigo_barras' => 'nullable|string|max:50',
            'ativo' => 'sometimes|boolean',
        ]);
    }

    private function buildResumo(): array
    {
        return [
            'total' => Insumo::ativos()->count(),
            'critico' => Insumo::ativos()->criticos()->count(),
            'zerado' => Insumo::ativos()->where('quantidade_atual', 0)->count(),
            'custo_total' => (float) Insumo::ativos()->selectRaw('COALESCE(SUM(quantidade_atual * preco_unitario), 0) as total')->value('total'),
            'pratos_comprometidos' => Prato::query()
                ->whereNull('deleted_at')
                ->comprometidos()
                ->count(),
        ];
    }

    private function notifyIfCritical(Insumo $insumo): void
    {
        if (! $insumo->abaixo_do_minimo) {
            return;
        }

        $tipo = "estoque_critico_insumo_{$insumo->id}";
        $pratosComprometidos = $this->getCompromisedDishNamesForInsumo($insumo);
        $mensagem = count($pratosComprometidos) > 0
            ? "O ingrediente {$insumo->nome} está abaixo do mínimo e impacta: " . implode(', ', $pratosComprometidos) . '.'
            : "O ingrediente {$insumo->nome} está abaixo do mínimo configurado.";

        $usuarios = User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COZINHA])
            ->get(['id']);

        foreach ($usuarios as $usuario) {
            $existeAberta = Notificacao::query()
                ->where('user_id', $usuario->id)
                ->where('tipo', $tipo)
                ->where('lida', false)
                ->exists();

            if ($existeAberta) {
                continue;
            }

            Notificacao::create([
                'user_id' => $usuario->id,
                'tipo' => $tipo,
                'titulo' => 'Alerta de estoque baixo',
                'mensagem' => $mensagem,
                'dados' => [
                    'insumo_id' => $insumo->id,
                    'insumo_nome' => $insumo->nome,
                    'nivel' => $insumo->nivel,
                    'pratos_comprometidos' => $pratosComprometidos,
                ],
            ]);
        }
    }

    private function getCompromisedDishNamesForInsumo(Insumo $insumo): array
    {
        return $insumo->pratos()
            ->whereNull('pratos.deleted_at')
            ->pluck('pratos.nome')
            ->values()
            ->all();
    }
}
