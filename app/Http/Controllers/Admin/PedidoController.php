<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PedidoController extends Controller
{
    public function index()
    {
        // Load orders with users and order by created_at. Handled pagination.
        $relations = ['usuario', 'itens.prato'];

        if (Schema::hasTable('pedido_status_historico') || Schema::hasTable('pedido_status_historicos')) {
            $relations[] = 'historico';
        }

        $pedidos = Pedido::with($relations)->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pedido', compact('pedidos'));
    }

    public function updateStatus(Request $request, Pedido $pedido)
    {
        $request->validate([
            'status' => 'required|in:aguardando_pagamento,confirmado,em_producao,saindo_entrega,entregue,cancelado,reembolsado',
        ]);

        $pedido->update(['status' => $request->status]);

        // Registrar no historico
        $pedido->historico()->create([
            'status' => $request->status,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Status do pedido atualizado com sucesso!');
    }
}
