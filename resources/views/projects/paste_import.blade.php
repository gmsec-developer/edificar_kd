@extends('layouts.app')

@section('content')
<style>
    .kd-page { padding:18px; background:#f4f6f9; color:#111827; }
    .kd-card { background:#fff; border:1px solid #d9dee7; border-radius:10px; box-shadow:0 2px 8px rgba(15,23,42,.06); margin-bottom:16px; overflow:hidden; }
    .kd-card-header { padding:12px 16px; background:#f8fafc; border-bottom:1px solid #d9dee7; font-weight:800; display:flex; justify-content:space-between; align-items:center; }
    .kd-card-body { padding:16px; }
    .kd-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .kd-textarea { width:100%; min-height:260px; font-family:Consolas, monospace; font-size:13px; border:1px solid #cbd5e1; border-radius:8px; padding:10px; }
    .kd-table-wrap { overflow:auto; max-height:420px; }
    .kd-table { width:100%; border-collapse:collapse; font-size:13px; }
    .kd-table th { background:#eef2f7; border-bottom:1px solid #cfd6e1; padding:7px; white-space:nowrap; }
    .kd-table td { border-bottom:1px solid #edf0f5; padding:7px; white-space:nowrap; }
    .kd-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:12px; }
    .kd-summary { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
    .kd-summary-item { background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:10px; }
    .kd-label { color:#64748b; font-size:12px; font-weight:700; }
    .kd-value { font-size:18px; font-weight:900; }
    .kd-split { display:grid; grid-template-columns:360px 1fr; gap:14px; }
    .kd-module-list { max-height:520px; overflow:auto; border:1px solid #e5e7eb; border-radius:8px; }
    .kd-module-item { padding:10px 12px; border-bottom:1px solid #edf0f5; cursor:pointer; background:#fff; }
    .kd-module-item:hover { background:#eef6ff; }
    .kd-module-item.active { background:#dbeafe; border-left:4px solid #2563eb; }
    .kd-module-ref { font-weight:900; color:#111827; }
    .kd-module-desc { font-size:12px; color:#64748b; margin-top:3px; line-height:1.3; }
    .kd-muted { color:#64748b; font-size:13px; font-weight:700; }
    .kd-detail-title { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:10px; }
    @media (max-width: 1100px) { .kd-split { grid-template-columns:1fr; } }
</style>

<div class="kd-page">
    <div class="kd-card">
        <div class="kd-card-header">
            <div>Importar desde portapapeles KitchenDraw</div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>

        <div class="kd-card-body">
            <form method="POST" action="{{ route('projects.clipboard.preview') }}">
                @csrf

                <div class="kd-grid">
                    <div>
                        <label class="form-label fw-bold">Grid de modulos</label>
                        <textarea name="modules_raw" class="kd-textarea" placeholder="Pega aqui el grid de modulos">{{ old('modules_raw', $modulesRaw ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="form-label fw-bold">Grid de despiece</label>
                        <textarea name="parts_raw" class="kd-textarea" placeholder="Pega aqui el grid de despiece">{{ old('parts_raw', $partsRaw ?? '') }}</textarea>
                    </div>
                </div>

                <div class="kd-actions">
                    <button type="submit" class="btn btn-primary">Procesar vista previa</button>
                </div>
            </form>
        </div>
    </div>

    @isset($modules)
        <div class="kd-card">
            <div class="kd-card-header">Resumen temporal</div>
            <div class="kd-card-body">
                <div class="kd-summary">
                    <div class="kd-summary-item">
                        <div class="kd-label">Modulos</div>
                        <div class="kd-value">{{ count($modules) }}</div>
                    </div>
                    <div class="kd-summary-item">
                        <div class="kd-label">Despiece</div>
                        <div class="kd-value">{{ count($parts) }}</div>
                    </div>
                    <div class="kd-summary-item">
                        <div class="kd-label">Piezas tablero/frentes</div>
                        <div class="kd-value">{{ collect($parts)->whereIn('type_code', [1,2])->count() }}</div>
                    </div>
                    <div class="kd-summary-item">
                        <div class="kd-label">Herrajes/materiales</div>
                        <div class="kd-value">{{ collect($parts)->where('type_code', 4)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="kd-card">
            <div class="kd-card-header">Vista por modulo</div>
            <div class="kd-card-body">
                <div class="kd-split">
                    <div>
                        <div class="kd-muted" style="margin-bottom:8px;">Selecciona un modulo</div>
                        <div class="kd-module-list">
                            @foreach($modules as $m)
                                <div class="kd-module-item"
                                     data-module-number="{{ $m['number'] }}"
                                     onclick="selectKdModule('{{ $m['number'] }}', this)">
                                    <div class="kd-module-ref">#{{ $m['number'] }} - {{ $m['reference'] }}</div>
                                    <div class="kd-module-desc">{{ $m['description'] }}</div>
                                    <div class="kd-muted">Cant: {{ $m['quantity'] }} | PVP: {{ number_format($m['total_price'], 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="kd-detail-title">
                            <div>
                                <strong>Despiece del modulo seleccionado</strong>
                                <div class="kd-muted" id="selectedModuleLabel">Selecciona un modulo de la izquierda.</div>
                            </div>
                            <div class="kd-muted">
                                Filas: <span id="selectedModuleCount">0</span>
                            </div>
                        </div>

                        <div class="kd-table-wrap">
                            <table class="kd-table" id="modulePartsTable">
                                <thead>
                                    <tr>
                                        <th>Linea</th>
                                        <th>No</th>
                                        <th>Modulo</th>
                                        <th>Tipo</th>
                                        <th>Pieza</th>
                                        <th>Cant.</th>
                                        <th>Largo</th>
                                        <th>Ancho</th>
                                        <th>Lado</th>
                                        <th>Material</th>
                                        <th>Color</th>
                                        <th>Espesor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parts as $p)
                                        <tr class="kd-part-row" data-module-number="{{ $p['module_number'] }}" style="display:none;">
                                            <td>{{ $p['line_number'] ?? '-' }}</td>
                                            <td>{{ $p['module_number'] }}</td>
                                            <td><strong>{{ $p['module_reference'] }}</strong></td>
                                            <td>{{ $p['type_code'] }}</td>
                                            <td>{{ $p['part'] }}</td>
                                            <td>{{ $p['quantity'] }}</td>
                                            <td>{{ $p['length'] }}</td>
                                            <td>{{ $p['width'] }}</td>
                                            <td>{{ $p['side'] }}</td>
                                            <td>{{ $p['material'] }}</td>
                                            <td>{{ $p['color_code'] }} {{ $p['color_description'] }}</td>
                                            <td>{{ $p['thickness'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kd-card">
            <div class="kd-card-header">Modulos detectados</div>
            <div class="kd-table-wrap">
                <table class="kd-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Referencia</th>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>PVP unit.</th>
                            <th>PVP total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $m)
                            <tr>
                                <td>{{ $m['number'] }}</td>
                                <td><strong>{{ $m['reference'] }}</strong></td>
                                <td>{{ $m['description'] }}</td>
                                <td>{{ $m['quantity'] }}</td>
                                <td>{{ number_format($m['unit_price'], 2) }}</td>
                                <td>{{ number_format($m['total_price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="kd-card">
            <div class="kd-card-header">Despiece detectado</div>
            <div class="kd-table-wrap">
                <table class="kd-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Modulo</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Pieza</th>
                            <th>Cant.</th>
                            <th>Largo</th>
                            <th>Ancho</th>
                            <th>Lado</th>
                            <th>Material</th>
                            <th>Color</th>
                            <th>Espesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parts as $p)
                            <tr>
                                <td>{{ $p['line_number'] ?? '-' }}</td>
                                            <td>{{ $p['module_number'] }}</td>
                                <td><strong>{{ $p['module_reference'] }}</strong></td>
                                <td>{{ $p['module_name'] }}</td>
                                <td>{{ $p['type_code'] }}</td>
                                <td>{{ $p['part'] }}</td>
                                <td>{{ $p['quantity'] }}</td>
                                <td>{{ $p['length'] }}</td>
                                <td>{{ $p['width'] }}</td>
                                <td>{{ $p['side'] }}</td>
                                <td>{{ $p['material'] }}</td>
                                <td>{{ $p['color_code'] }} {{ $p['color_description'] }}</td>
                                <td>{{ $p['thickness'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endisset
</div>
<script>
    function selectKdModule(moduleNumber, element) {
        document.querySelectorAll('.kd-module-item').forEach(function (item) {
            item.classList.remove('active');
        });

        if (element) {
            element.classList.add('active');
        }

        let count = 0;
        document.querySelectorAll('.kd-part-row').forEach(function (row) {
            if (row.getAttribute('data-module-number') === String(moduleNumber)) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        const label = document.getElementById('selectedModuleLabel');
        if (label) {
            label.textContent = 'Modulo #' + moduleNumber;
        }

        const counter = document.getElementById('selectedModuleCount');
        if (counter) {
            counter.textContent = count;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const first = document.querySelector('.kd-module-item');
        if (first) {
            selectKdModule(first.getAttribute('data-module-number'), first);
        }
    });
</script>
@endsection