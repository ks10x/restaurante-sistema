@extends('layouts.admin')

@section('titulo', 'Gestão de Mesas')

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mesas Cadastradas</h5>
            </div>
            <div class="card-body">
                @if($mesas->isEmpty())
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-chair text-opacity-50" style="font-size: 3rem;"></i>
                        <p class="mt-3">Nenhuma mesa cadastrada.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Capacidade</th>
                                    <th>Status Atual</th>
                                    <th>QR Code</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mesas as $mesa)
                                <tr>
                                    <td><strong>{{ $mesa->numero }}</strong></td>
                                    <td>{{ $mesa->capacidade }} pessoas</td>
                                    <td>
                                        @if($mesa->status == 'livre')
                                            <span class="badge bg-success">Livre</span>
                                        @elseif($mesa->status == 'ocupada')
                                            <span class="badge bg-danger">Ocupada</span>
                                        @elseif($mesa->status == 'chamando')
                                            <span class="badge bg-warning text-dark">Chamando</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Aguardando Pgto</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.mesas.qr', $mesa) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-qrcode"></i> Imprimir QR
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.mesas.destroy', $mesa) }}" method="POST" onsubmit="return confirm('Tem certeza? Remover esta mesa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Adicionar Mesa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mesas.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Número ou Identificação</label>
                        <input type="text" name="numero" class="form-control" placeholder="Ex: 01 ou Varanda" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacidade (Pessoas)</label>
                        <input type="number" name="capacidade" class="form-control" value="4" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Cadastrar Mesa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
