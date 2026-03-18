<?php

namespace App\Http\Controllers\Cozinha;
 
use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
 
class FilaController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::whereIn('status', ['confirmado', 'em_producao', 'saindo_entrega'])
            ->with(['itens.prato', 'usuario', 'endereco'])
            ->orderByRaw("FIELD(status, 'confirmado', 'em_producao', 'saindo_entrega')")
            ->orderBy('created_at')
            ->get();
 
        return view('cozinha.fila', compact('pedidos'));
    }
 
    public function avancarStatus(Pedido $pedido, Request $request)
    {
        $sucesso = $pedido->avancarStatus(auth()->id());
 
        if (!$sucesso) {
            return response()->json(['error' => 'Não é possível avançar o status.'], 422);
        }
 
        return response()->json([
            'success'      => true,
            'status'       => $pedido->fresh()->status,
            'status_label' => $pedido->fresh()->status_label,
        ]);
    }
 
    public function pedidosApi()
    {
        $pedidos = Pedido::whereIn('status', ['confirmado', 'em_producao'])
            ->with(['itens.prato', 'usuario'])
            ->orderBy('created_at')
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'codigo'    => $p->codigo,
                'status'    => $p->status,
                'cliente'   => $p->usuario->name,
                'itens'     => $p->itens->map(fn($i) => [
                    'nome'       => $i->nome_prato,
                    'quantidade' => $i->quantidade,
                    'opcoes'     => $i->opcoes,
                    'observacao' => $i->observacao,
                ]),
                'criado_em' => $p->created_at->format('H:i'),
                'minutos'   => $p->created_at->diffInMinutes(now()),
            ]);
 
        return response()->json($pedidos);
    }
}
 