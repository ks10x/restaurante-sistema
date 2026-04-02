<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use Illuminate\Http\JsonResponse;

class NotificacaoController extends Controller
{
    public function markAsRead(Notificacao $notificacao): JsonResponse
    {
        abort_unless((int) $notificacao->user_id === (int) auth()->id(), 403);

        $notificacao->update([
            'lida' => true,
            'lida_em' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
