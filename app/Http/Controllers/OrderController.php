<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // Certifique-se de ter o Model Order
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Por enquanto, vamos passar um array vazio para a tela não quebrar
        // Depois buscamos os pedidos reais do banco
        $pedidos = []; 
        
        return view('orders.index', compact('pedidos'));
    }
}