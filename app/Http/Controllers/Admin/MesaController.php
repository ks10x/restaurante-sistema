<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::orderBy('numero')->get();
        return view('admin.mesas.index', compact('mesas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|max:10|unique:mesas,numero',
            'capacidade' => 'required|integer|min:1'
        ]);

        Mesa::create([
            'numero' => $request->numero,
            'capacidade' => $request->capacidade,
            'token_hash' => Str::random(12),
        ]);

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa adicionada com sucesso.');
    }

    public function destroy(Mesa $mesa)
    {
        $mesa->delete();
        return redirect()->route('admin.mesas.index')->with('success', 'Mesa removida com sucesso.');
    }

    public function showQr(Mesa $mesa)
    {
        $url = $mesa->url;
        
        $renderer = new ImageRenderer(
            new RendererStyle(300, 0),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($url);

        // Retorna logo um HTML simples para impressão individual (ou o lojista pode salvar)
        return response(view('admin.mesas.qr', compact('mesa', 'svg', 'url')));
    }
}
