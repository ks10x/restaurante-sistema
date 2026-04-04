<?php

namespace App\Http\Controllers\Cozinha;

use App\Events\PedidoStatusAtualizado;
use App\Http\Controllers\Controller;
use App\Models\Insumo;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class FilaController extends Controller
{
    public function index()
    {
        $pedidosAtivos = $this->getPedidosAtivos();
        $pedidosPorStatus = [
            'confirmado' => $pedidosAtivos->where('status', 'confirmado')->values(),
            'em_producao' => $pedidosAtivos->where('status', 'em_producao')->values(),
            'saindo_entrega' => $pedidosAtivos->where('status', 'saindo_entrega')->values(),
        ];

        return view('cozinha.fila', compact('pedidosPorStatus'));
    }

    public function dashboard()
    {
        $hoje = Carbon::today();
        $pedidosAtivos = $this->getPedidosAtivos();
        $pedidosProduzidosHoje = $this->basePedidosQuery()
            ->whereNotNull('producao_em')
            ->whereDate('producao_em', $hoje)
            ->orderByDesc('producao_em')
            ->limit(12)
            ->get()
            ->map(fn (Pedido $pedido) => $this->decoratePedido($pedido));

        $pedidosEntreguesHoje = $this->basePedidosQuery()
            ->where('status', 'entregue')
            ->whereDate('entregue_em', $hoje)
            ->orderByDesc('entregue_em')
            ->limit(12)
            ->get()
            ->map(fn (Pedido $pedido) => $this->decoratePedido($pedido));

        $pedidosCanceladosHoje = $this->basePedidosQuery()
            ->where('status', 'cancelado')
            ->whereDate('cancelado_em', $hoje)
            ->orderByDesc('cancelado_em')
            ->limit(12)
            ->get()
            ->map(fn (Pedido $pedido) => $this->decoratePedido($pedido));

        $pedidosHoje = $this->basePedidosQuery()
            ->whereDate('created_at', $hoje)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Pedido $pedido) => $this->decoratePedido($pedido));

        $pedidosPorStatus = [
            'confirmado' => $pedidosAtivos->where('status', 'confirmado')->values(),
            'em_producao' => $pedidosAtivos->where('status', 'em_producao')->values(),
            'saindo_entrega' => $pedidosAtivos->where('status', 'saindo_entrega')->values(),
        ];

        $insumosCriticos = Insumo::query()
            ->ativos()
            ->criticos()
            ->orderBy('quantidade_atual')
            ->limit(8)
            ->get();

        $kpis = [
            'pedidos_hoje' => $pedidosHoje->count(),
            'ativos' => $pedidosAtivos->count(),
            'aguardando' => $pedidosPorStatus['confirmado']->count(),
            'em_producao' => $pedidosPorStatus['em_producao']->count(),
            'saindo_entrega' => $pedidosPorStatus['saindo_entrega']->count(),
            'entregues_hoje' => $pedidosEntreguesHoje->count(),
            'cancelados_hoje' => $pedidosCanceladosHoje->count(),
            'faturamento_hoje' => (float) $pedidosHoje->where('pagamento_status', 'aprovado')->sum('total'),
            'ticket_medio_hoje' => round((float) ($pedidosHoje->where('pagamento_status', 'aprovado')->avg('total') ?? 0), 2),
            'tempo_medio_inicio' => $this->averageMinutes($pedidosProduzidosHoje, 'confirmado_em', 'producao_em'),
            'tempo_medio_entrega' => $this->averageMinutes($pedidosEntreguesHoje, 'confirmado_em', 'entregue_em'),
            'itens_hoje' => $pedidosHoje->sum(fn (Pedido $pedido) => $pedido->itens->sum('quantidade')),
        ];

        $pratosMaisProduzidos = $pedidosHoje
            ->flatMap(fn (Pedido $pedido) => $pedido->itens)
            ->groupBy(fn ($item) => $item->nome_prato ?: optional($item->prato)->nome ?: 'Item')
            ->map(function (Collection $items, string $nome) {
                $first = $items->first();

                return (object) [
                    'nome' => $nome,
                    'quantidade' => $items->sum('quantidade'),
                    'imagem' => optional($first->prato)->imagem_url,
                    'descricao' => optional($first->prato)->descricao,
                ];
            })
            ->sortByDesc('quantidade')
            ->take(6)
            ->values();

        return view('cozinha.dashboard', compact(
            'pedidosPorStatus',
            'pedidosProduzidosHoje',
            'pedidosEntreguesHoje',
            'pedidosCanceladosHoje',
            'insumosCriticos',
            'kpis',
            'pratosMaisProduzidos'
        ));
    }

    public function avancarStatus(Pedido $pedido)
    {
        $sucesso = $pedido->avancarStatus(auth()->id());

        if (! $sucesso) {
            return response()->json(['error' => 'Nao e possivel avancar o status.'], 422);
        }

        $pedido = $this->decoratePedido($pedido->fresh()->load(['itens.prato.ingredientes', 'itens.prato.insumos', 'usuario', 'endereco']));

        return response()->json([
            'success' => true,
            'pedido' => [
                'id' => $pedido->id,
                'codigo' => $pedido->codigo,
                'status' => $pedido->status,
                'status_label' => $pedido->status_label,
                'minutos_decorridos' => $pedido->minutos_decorridos,
                'faixa_tempo' => $pedido->faixa_tempo,
            ],
        ]);
    }

    public function cancelar(Pedido $pedido, Request $request)
    {
        if (in_array($pedido->status, ['entregue', 'cancelado', 'reembolsado'], true)) {
            return response()->json(['error' => 'Este pedido nao pode mais ser cancelado.'], 422);
        }

        $pedido->update([
            'status' => 'cancelado',
            'cancelado_em' => now(),
            'cancelado_por' => auth()->id(),
            'motivo_cancelamento' => $request->string('motivo')->toString() ?: 'Cancelado pela cozinha.',
        ]);

        $pedido->historico()->create([
            'status' => 'cancelado',
            'user_id' => auth()->id(),
        ]);

        event(new PedidoStatusAtualizado($pedido->fresh()));

        return response()->json([
            'success' => true,
            'message' => 'Pedido cancelado com sucesso.',
        ]);
    }

    public function pedidosApi()
    {
        $pedidos = $this->getPedidosAtivos()
            ->map(fn (Pedido $pedido) => $this->serializePedido($pedido))
            ->values();

        return response()->json($pedidos);
    }

    private function getPedidosAtivos(): Collection
    {
        return $this->basePedidosQuery()
            ->whereIn('status', ['confirmado', 'em_producao', 'saindo_entrega'])
            ->orderByRaw("FIELD(status, 'confirmado', 'em_producao', 'saindo_entrega')")
            ->orderBy('created_at')
            ->get()
            ->map(fn (Pedido $pedido) => $this->decoratePedido($pedido));
    }

    private function basePedidosQuery()
    {
        return Pedido::query()->with([
            'itens.prato.ingredientes',
            'itens.prato.insumos',
            'usuario',
            'endereco',
        ]);
    }

    private function decoratePedido(Pedido $pedido): Pedido
    {
        $referencia = $pedido->confirmado_em ?? $pedido->created_at;
        $minutos = $referencia ? (int) round($referencia->diffInMinutes(now())) : 0;
        $pedido->minutos_decorridos = $minutos;
        $pedido->faixa_tempo = $minutos >= 30 ? 'critico' : ($minutos >= 15 ? 'atencao' : 'ok');
        $pedido->progresso = min(100, (int) round(($minutos / max(1, (int) ($pedido->tempo_estimado ?? 45))) * 100));
        $pedido->total_itens = $pedido->itens->sum('quantidade');
        $pedido->itens->each(fn (PedidoItem $item) => $this->decorateItem($item));
        $pedido->thumb = $pedido->itens->first()?->foto_url ?? asset('images/prato-placeholder.jpg');
        $pedido->endereco_formatado = $this->formatAddress($pedido);
        $pedido->observacoes_limpa = $this->cleanText($pedido->observacoes);

        return $pedido;
    }

    private function decorateItem(PedidoItem $item): void
    {
        $item->foto_url = $this->resolveItemImage($item);
        $item->descricao_curta = $this->cleanText(optional($item->prato)->descricao);
        $item->ingredientes_lista = $item->prato
            ? $item->prato->ingredientes->pluck('nome')->map(fn ($nome) => $this->cleanText($nome))->filter()->values()->all()
            : [];
        $item->observacao_limpa = $this->cleanText($item->observacao);
        $item->nome_exibicao = $this->cleanText($item->nome_prato ?: optional($item->prato)->nome ?: 'Item');
    }

    private function serializePedido(Pedido $pedido): array
    {
        return [
            'id' => $pedido->id,
            'codigo' => $pedido->codigo,
            'status' => $pedido->status,
            'status_label' => $pedido->status_label,
            'cliente' => $this->cleanText(optional($pedido->usuario)->name),
            'tipo_entrega' => $pedido->tipo_entrega,
            'minutos_decorridos' => $pedido->minutos_decorridos,
            'faixa_tempo' => $pedido->faixa_tempo,
            'progresso' => $pedido->progresso,
            'total' => (float) $pedido->total,
            'total_itens' => $pedido->total_itens,
            'endereco' => $pedido->endereco ? [
                'logradouro' => $this->cleanText($pedido->endereco->logradouro),
                'numero' => $this->cleanText($pedido->endereco->numero),
                'bairro' => $this->cleanText($pedido->endereco->bairro),
                'cidade' => $this->cleanText($pedido->endereco->cidade),
                'estado' => $this->cleanText($pedido->endereco->estado ?? $pedido->endereco->uf),
                'texto' => $pedido->endereco_formatado,
            ] : null,
            'observacoes' => $pedido->observacoes_limpa,
            'avancar_url' => route('cozinha.pedido.avancar', $pedido),
            'cancelar_url' => route('cozinha.pedido.cancelar', $pedido),
            'itens' => $pedido->itens->map(fn (PedidoItem $item) => [
                'nome' => $item->nome_exibicao,
                'quantidade' => $item->quantidade,
                'descricao' => $item->descricao_curta,
                'imagem' => $item->foto_url,
                'observacao' => $item->observacao_limpa,
                'ingredientes' => $item->ingredientes_lista,
                'opcoes' => is_array($item->opcoes ?? null) ? $item->opcoes : [],
            ])->values()->all(),
        ];
    }

    private function resolveItemImage(PedidoItem $item): string
    {
        if ($item->prato && filled($item->prato->imagem_url)) {
            return $item->prato->imagem_url;
        }

        $nomePrato = trim((string) ($item->nome_prato ?: optional($item->prato)->nome));

        if ($nomePrato !== '') {
            $pratoPorNome = $item->relationLoaded('prato') && $item->prato
                ? null
                : \App\Models\Prato::query()->withTrashed()->where('nome', $nomePrato)->first();

            if ($pratoPorNome && filled($pratoPorNome->imagem_url)) {
                return $pratoPorNome->imagem_url;
            }
        }

        return asset('images/prato-placeholder.jpg');
    }

    private function formatAddress(Pedido $pedido): ?string
    {
        if (! $pedido->endereco || $pedido->tipo_entrega !== 'entrega') {
            return null;
        }

        $parts = array_filter([
            trim(implode(', ', array_filter([
                $this->cleanText($pedido->endereco->logradouro),
                $this->cleanText($pedido->endereco->numero),
            ]))),
            $this->cleanText($pedido->endereco->bairro),
            trim(implode('/', array_filter([
                $this->cleanText($pedido->endereco->cidade),
                $this->cleanText($pedido->endereco->estado ?? $pedido->endereco->uf),
            ]))),
        ]);

        return $parts ? implode(' - ', $parts) : null;
    }

    private function cleanText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $clean = trim($value);
        $clean = preg_replace('/\\x{FFFD}+/u', ' ', $clean) ?? $clean;
        $clean = preg_replace('/[[:cntrl:]]+/u', ' ', $clean) ?? $clean;
        $clean = preg_replace('/\s{2,}/u', ' ', $clean) ?? $clean;

        return $clean === '' ? null : $clean;
    }

    private function averageMinutes(Collection $pedidos, string $inicio, string $fim): int
    {
        $tempos = $pedidos
            ->filter(fn (Pedido $pedido) => $pedido->{$inicio} && $pedido->{$fim})
            ->map(fn (Pedido $pedido) => $pedido->{$inicio}->diffInMinutes($pedido->{$fim}));

        return $tempos->isEmpty() ? 0 : (int) round($tempos->avg());
    }
}

