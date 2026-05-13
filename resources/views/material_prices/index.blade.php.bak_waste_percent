@extends('layouts.app')

@section('content')
<style>
    .mp-page { background:#f4f6f9; padding:18px; font-size:15px; color:#111827; }
    .mp-card { background:#fff; border:1px solid #d9dee7; border-radius:10px; box-shadow:0 2px 8px rgba(15,23,42,.06); margin-bottom:16px; overflow:hidden; }
    .mp-card-header { padding:12px 16px; background:#f8fafc; border-bottom:1px solid #d9dee7; font-weight:800; font-size:17px; display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .mp-card-body { padding:16px; }
    .mp-summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
    .mp-summary-item { background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:10px; }
    .mp-label { font-size:13px; color:#6b7280; margin-bottom:3px; }
    .mp-value { font-size:18px; font-weight:800; color:#111827; }
    .mp-table-wrap { overflow-x:auto; }
    .mp-table { width:100%; border-collapse:collapse; background:#fff; font-size:14px; }
    .mp-table th { background:#eef2f7; border-bottom:1px solid #cfd6e1; padding:8px; white-space:nowrap; font-weight:700; }
    .mp-table td { border-bottom:1px solid #edf0f5; padding:8px; vertical-align:middle; white-space:nowrap; }
    .mp-table tbody tr:nth-child(even) { background:#fafbff; }
    .mp-table tbody tr:hover { background:#eef6ff; }
    .mp-row-pending { background:#fff7ed !important; }
    .mp-row-priced { background:#f8fafc; }
    .mp-material { white-space:normal !important; min-width:320px; }
    .mp-num { text-align:right; }
    .mp-center { text-align:center; }
    .mp-badge-ok { display:inline-block; padding:3px 8px; border-radius:999px; background:#dcfce7; color:#15803d; font-weight:700; }
    .mp-badge-pending { display:inline-block; padding:3px 8px; border-radius:999px; background:#fef3c7; color:#b45309; font-weight:700; }
    .mp-actions { display:flex; gap:8px; justify-content:flex-end; }
    .mp-filter { display:flex; gap:10px; flex-wrap:wrap; align-items:end; }
</style>

<div class="mp-page">

    <div class="mp-card">
        <div class="mp-card-header">
            <div>Costos de materiales</div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3">Volver</a>
        </div>

        <div class="mp-card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mp-summary-grid">
                <div class="mp-summary-item">
                    <div class="mp-label">Materiales detectados</div>
                    <div class="mp-value">{{ $summary['total'] }}</div>
                </div>

                <div class="mp-summary-item">
                    <div class="mp-label">Pendientes de precio</div>
                    <div class="mp-value">{{ $summary['pending'] }}</div>
                </div>

                <div class="mp-summary-item">
                    <div class="mp-label">Con precio interno</div>
                    <div class="mp-value">{{ $summary['priced'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mp-card">
        <div class="mp-card-header">Filtro</div>
        <div class="mp-card-body">
            <form method="GET" action="{{ route('material-prices.index') }}" class="mp-filter">
                <div>
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Codigo, material o color">
                </div>

                <div>
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pendientes</option>
                        <option value="priced" @selected(request('status') === 'priced')>Con precio</option>
                    </select>
                </div>

                <div>
                    <button class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('material-prices.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mp-card">
        <div class="mp-card-header">Lista de materiales</div>

        <div class="mp-table-wrap">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th>Cod. Material</th>
                        <th>Material</th>
                        <th>Cod. Color</th>
                        <th>Color</th>
                        <th>Unidad</th>
                        <th>Costo unitario</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr class="{{ (float) $material->unit_cost > 0 ? 'mp-row-priced' : 'mp-row-pending' }}">
                            <td><strong>{{ $material->material_code }}</strong></td>
                            <td class="mp-material">{{ $material->material_name }}</td>
                            <td>{{ $material->color_code }}</td>
                            <td>{{ $material->color_name }}</td>
                            <td>{{ $material->unit_type }}</td>
                            <td class="mp-num">{{ number_format($material->unit_cost, 4) }}</td>
                            <td>
                                @if((float) $material->unit_cost > 0)
                                    <span class="mp-badge-ok">Con precio</span>
                                @else
                                    <span class="mp-badge-pending">Pendiente</span>
                                @endif
                            </td>
                            <td>{{ $material->is_active ? 'Si' : 'No' }}</td>
                            <td>
                                <a href="{{ route('material-prices.edit', $material) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted">No hay materiales registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mp-card-body">
            {{ $materials->links() }}
        </div>
    </div>

</div>
@endsection
