@extends('layouts.app')

@section('content')
<style>
    .mp-page { background:#f4f6f9; padding:18px; font-size:15px; color:#111827; }
    .mp-card { background:#fff; border:1px solid #d9dee7; border-radius:14px; box-shadow:0 2px 10px rgba(15,23,42,.07); margin-bottom:16px; overflow:hidden; }
    .mp-card-header { padding:14px 18px; background:linear-gradient(90deg,#f8fafc,#eef2ff); border-bottom:1px solid #d9dee7; font-weight:900; font-size:17px; display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .mp-card-body { padding:16px; }
    .mp-summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
    .mp-summary-item { background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:12px; }
    .mp-label { font-size:13px; color:#6b7280; margin-bottom:3px; font-weight:700; }
    .mp-value { font-size:20px; font-weight:900; color:#111827; }
    .mp-filter { display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr auto; gap:14px; align-items:end; }
    .mp-bulk-panel { display:grid; grid-template-columns:1fr 1.2fr 1.2fr 1fr auto; gap:14px; align-items:end; background:#f8fafc; border:1px solid #c7d2fe; border-left:5px solid #2563eb; border-radius:14px; padding:16px; box-shadow:inset 0 1px 0 rgba(255,255,255,.7); }
    .mp-help { font-size:12px; color:#6b7280; margin-top:6px; }
    .mp-table-wrap { overflow-x:auto; }
    .mp-table { width:100%; border-collapse:collapse; background:#fff; font-size:14px; }
    .mp-table th { background:#e8eef8; border-bottom:1px solid #cfd6e1; padding:11px 10px; white-space:nowrap; font-weight:900; color:#0f172a; }
    .mp-table td { border-bottom:1px solid #edf0f5; padding:10px; vertical-align:middle; white-space:nowrap; }
    .mp-table tbody tr:nth-child(even) { background:#fafbff; }
    .mp-table tbody tr:hover { background:#eef6ff; }
    .mp-row-pending { background:#fff7ed !important; }
    .mp-row-priced { background:#f8fafc; }
    .mp-material { white-space:normal !important; min-width:320px; }
    .mp-num { text-align:right; }
    .mp-center { text-align:center; }
    .mp-badge-ok { display:inline-block; padding:3px 8px; border-radius:999px; background:#dcfce7; color:#15803d; font-weight:800; }
    .mp-badge-pending { display:inline-block; padding:3px 8px; border-radius:999px; background:#fef3c7; color:#b45309; font-weight:800; }
    .mp-badge-off { display:inline-block; padding:3px 8px; border-radius:999px; background:#fee2e2; color:#b91c1c; font-weight:800; }
    .mp-actions { display:flex; gap:8px; justify-content:flex-end; } .mp-toolbar { display:flex; align-items:center; gap:8px; flex-wrap:wrap; } .mp-selected-pill { display:inline-block; padding:5px 10px; border-radius:999px; background:#dbeafe; color:#1d4ed8; font-weight:900; } .mp-soft-title { font-size:13px; color:#475569; font-weight:800; }
    .mp-check { width:18px; height:18px; cursor:pointer; }
    @media (max-width: 1100px) {
        .mp-filter, .mp-bulk-panel, .mp-summary-grid { grid-template-columns:1fr; }
    }
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

            @if($errors->any())
                <div class="alert alert-danger">Revisa los campos ingresados para el cambio masivo.</div>
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
        <div class="mp-card-header">Filtros avanzados</div>
        <div class="mp-card-body">
            <form method="GET" action="{{ route('material-prices.index') }}" class="mp-filter">
                <div>
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Codigo, material o color">
                </div>

                <div>
                    <label class="form-label">Precio</label>
                    <select name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pendientes</option>
                        <option value="priced" @selected(request('status') === 'priced')>Con precio</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Unidad</label>
                    <select name="unit_type" class="form-control">
                        <option value="">Todas</option>
                        <option value="m2" @selected(request('unit_type') === 'm2')>m2</option>
                        <option value="ml" @selected(request('unit_type') === 'ml')>ml</option>
                        <option value="unidad" @selected(request('unit_type') === 'unidad')>unidad</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Desperdicio</label>
                    <select name="waste_status" class="form-control">
                        <option value="">Todos</option>
                        <option value="zero" @selected(request('waste_status') === 'zero')>0%</option>
                        <option value="positive" @selected(request('waste_status') === 'positive')>Mayor a 0%</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Activo</label>
                    <select name="active" class="form-control">
                        <option value="">Todos</option>
                        <option value="yes" @selected(request('active') === 'yes')>Si</option>
                        <option value="no" @selected(request('active') === 'no')>No</option>
                    </select>
                </div>

                <div class="mp-toolbar">
                    <button class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('material-prices.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <form method="POST" action="{{ route('material-prices.bulk-update') }}" onsubmit="return confirmBulkMaterialUpdate();">
        @csrf

        <div class="mp-card">
            <div class="mp-card-header">
                <div>Cambio masivo a seleccionados</div>
                <span class="mp-selected-pill"><span id="selectedCount">0</span> seleccionados</span>
            </div>

            <div class="mp-card-body">
                <div class="mp-bulk-panel">
                    <div>
                        <label class="form-label">Nueva unidad</label>
                        <select name="bulk_unit_type" class="form-control">
                            <option value="">No cambiar</option>
                            <option value="m2">m2</option>
                            <option value="ml">ml</option>
                            <option value="unidad">unidad</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Nuevo costo unitario</label>
                        <input type="number" step="0.0001" min="0" name="bulk_unit_cost" class="form-control" placeholder="No cambiar">
                    </div>

                    <div>
                        <label class="form-label">Nuevo desperdicio %</label>
                        <input type="number" step="0.01" min="0" name="bulk_waste_percent" class="form-control" placeholder="No cambiar">
                    </div>

                    <div>
                        <label class="form-label">Activo</label>
                        <select name="bulk_active" class="form-control">
                            <option value="keep">No cambiar</option>
                            <option value="1">Activar</option>
                            <option value="0">Desactivar</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success">Aplicar cambios</button>
                    </div>
                </div>

                <div class="mp-help">Marca los materiales abajo y aplica solo los campos que quieras cambiar.</div>
            </div>
        </div>

        <div class="mp-card">
            <div class="mp-card-header">
                <div>Lista de materiales</div>
                <div>
                    <div class="mp-toolbar">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleMaterialChecks(true)">Seleccionar pagina</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleMaterialChecks(false)">Quitar seleccion</button>
                </div>
                </div>
            </div>

            <div class="mp-table-wrap">
                <table class="mp-table">
                    <thead>
                        <tr>
                            <th class="mp-center"><input type="checkbox" class="mp-check" onclick="toggleMaterialChecks(this.checked)"></th>
                            <th>Cod. Material</th>
                            <th>Material</th>
                            <th>Cod. Color</th>
                            <th>Color</th>
                            <th>Unidad</th>
                            <th>Costo unitario</th>
                            <th>Desperdicio %</th>
                            <th>Estado</th>
                            <th>Activo</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $material)
                            <tr class="{{ (float) $material->unit_cost > 0 ? 'mp-row-priced' : 'mp-row-pending' }}">
                                <td class="mp-center">
                                    <input type="checkbox" name="material_ids[]" value="{{ $material->id }}" class="mp-check material-check">
                                </td>
                                <td><strong>{{ $material->material_code }}</strong></td>
                                <td class="mp-material">{{ $material->material_name }}</td>
                                <td>{{ $material->color_code }}</td>
                                <td>{{ $material->color_name }}</td>
                                <td>{{ $material->unit_type }}</td>
                                <td class="mp-num">{{ number_format($material->unit_cost, 4) }}</td>
                                <td class="mp-num">{{ number_format($material->waste_percent ?? 0, 2) }}%</td>
                                <td>
                                    @if((float) $material->unit_cost > 0)
                                        <span class="mp-badge-ok">Con precio</span>
                                    @else
                                        <span class="mp-badge-pending">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    @if($material->is_active)
                                        <span class="mp-badge-ok">Si</span>
                                    @else
                                        <span class="mp-badge-off">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('material-prices.edit', $material) }}" class="btn btn-sm btn-outline-primary mp-table-action">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-muted">No hay materiales registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mp-card-body">
                {{ $materials->links() }}
            </div>
        </div>
    </form>

</div>

<script>
    function toggleMaterialChecks(checked) {
        document.querySelectorAll('.material-check').forEach(function (box) {
            box.checked = checked;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.material-check:checked').length;
        const target = document.getElementById('selectedCount');
        if (target) {
            target.textContent = checked;
        }
    }

    document.addEventListener('change', function (event) {
        if (event.target.classList && event.target.classList.contains('material-check')) {
            updateSelectedCount();
        }
    });

    function confirmBulkMaterialUpdate() {
        const checked = document.querySelectorAll('.material-check:checked').length;

        if (checked === 0) {
            alert('Selecciona al menos un material.');
            return false;
        }

        return confirm('Aplicar cambio masivo a ' + checked + ' material(es)?');
    }
</script>
@endsection