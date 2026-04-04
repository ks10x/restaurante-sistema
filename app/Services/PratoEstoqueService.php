<?php

namespace App\Services;

use App\Models\Insumo;
use App\Models\Prato;

class PratoEstoqueService
{
    public function syncAtivoForInsumo(Insumo $insumo): void
    {
        $pratos = $insumo->pratos()
            ->whereNull('pratos.deleted_at')
            ->get(['pratos.id']);

        $pratos->each(fn (Prato $prato) => $this->syncAtivoForPrato($prato));
    }

    public function syncAtivoForPrato(Prato $prato): void
    {
        if ($prato->trashed()) {
            return;
        }

        $temFalta = $prato->insumos()
            ->where(function ($q) {
                $q->where('insumos.ativo', false)
                    ->orWhereColumn('insumos.quantidade_atual', '<', 'prato_insumos.quantidade');
            })
            ->exists();

        $novoAtivo = ! $temFalta;

        if ((bool) $prato->ativo === $novoAtivo) {
            return;
        }

        $prato->update(['ativo' => $novoAtivo]);
    }
}
