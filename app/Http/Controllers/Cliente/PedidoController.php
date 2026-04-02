<?php

namespace App\Http\Controllers\Cliente;
 
use App\Http\Controllers\Controller;
use App\Models\{Pedido, Prato, Endereco, Cupom};
use App\Services\PedidoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class PedidoController extends Controller
{
    public function __construct(private PedidoService $service) {}
 
    public function carrinho() {
        return view('cliente.carrinho');
    }
 

 
    public function store(Request $request)
    {
        $request->validate([
            'itens'             => 'required|array|min:1',
            'itens.*.prato_id'  => 'required|exists:pratos,id',
            'itens.*.quantidade'=> 'required|integer|min:1|max:20',
            'tipo_entrega'      => 'required|in:entrega,retirada',
            'endereco_id'       => 'required_if:tipo_entrega,entrega|exists:enderecos,id',
            'pagamento_metodo'  => 'required|in:cartao_credito,cartao_debito,pix,dinheiro,vale_alimentacao',
            'troco_para'        => 'nullable|numeric|min:0',
            'cupom_codigo'      => 'nullable|string',
            'observacoes'       => 'nullable|string|max:500',
        ]);
 
        $pedido = $this->service->criar(Auth::user(), $request->validated());
 
        return response()->json([
            'success'  => true,
            'pedido_id'=> $pedido->id,
            'codigo'   => $pedido->codigo,
            'redirect' => route('cliente.pedido.acompanhar', $pedido->codigo),
        ]);
    }
 
    public function acompanhar(string $codigo) {
        $pedido = Pedido::where('codigo', $codigo)
            ->where('user_id', Auth::id())
            ->with(['itens.prato', 'endereco', 'historico'])
            ->firstOrFail();
 
        return view('cliente.acompanharPedido', compact('pedido'));
    }

    /**
     * Payment page (PIX QR code display)
     */
    public function pagamento(string $codigo) {
        $pedido = Pedido::where('codigo', $codigo)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // If already paid, redirect to tracking
        if ($pedido->pagamento_status === 'aprovado') {
            return redirect()->route('cliente.pedido.acompanhar', $pedido->codigo);
        }

        $pixCode = $pedido->pagamento_ref ? '' : '';
        $expiresAt = now()->addHour()->toIso8601String();

        return view('cliente.pagamento', compact('pedido', 'pixCode', 'expiresAt'));
    }
 
    public function historico() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pedidos = $user->pedidos()
            ->with('itens')
            ->latest()
            ->paginate(10);
        return view('cliente.historico', compact('pedidos'));
    }
 
    public function statusApi(string $codigo) {
        $pedido = Pedido::where('codigo', $codigo)
            ->where('user_id', Auth::id())
            ->firstOrFail();
 
        return response()->json([
            'status'           => $pedido->status,
            'status_label'     => $pedido->status_label,
            'pagamento_status' => $pedido->pagamento_status,
            'confirmado_em'    => $pedido->confirmado_em?->format('H:i'),
            'producao_em'      => $pedido->producao_em?->format('H:i'),
            'saiu_em'          => $pedido->saiu_em?->format('H:i'),
            'entregue_em'      => $pedido->entregue_em?->format('H:i'),
        ]);
    }
}