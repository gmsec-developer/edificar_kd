@extends('layouts.app')

@section('content')
<style>
    .ekd-page { background:#f4f6f9; padding:18px; font-size:15px; color:#111827; }
    .ekd-card { background:#fff; border:1px solid #d9dee7; border-radius:10px; box-shadow:0 2px 8px rgba(15,23,42,.06); margin-bottom:16px; overflow:hidden; }
    .ekd-card-header { padding:10px 14px; background:#f8fafc; border-bottom:1px solid #d9dee7; font-weight:700; font-size:16px; display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .ekd-card-body { padding:14px; }

    .ekd-topbar { background:#fff; border:1px solid #d9dee7; border-radius:10px; padding:14px 18px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(15,23,42,.06); }
    .ekd-topbar h3 { margin:0; font-size:20px; font-weight:800; }
    .ekd-topbar-subtitle { color:#6b7280; font-size:14px; margin-top:3px; }

    .ekd-summary-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
    .ekd-summary-item { background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:10px; }
    .ekd-label { font-size:13px; color:#6b7280; margin-bottom:3px; }
    .ekd-value { font-size:15px; font-weight:700; color:#111827; }

    .ekd-tabs { display:flex; gap:4px; border-bottom:1px solid #d9dee7; margin-bottom:14px; }
    .ekd-tab { border:1px solid #d9dee7; border-bottom:none; background:#f8fafc; padding:9px 16px; font-weight:700; border-radius:8px 8px 0 0; color:#374151; }
    .ekd-tab.active { background:#fff; color:#0f172a; }
    .ekd-tab-panel { display:none; }
    .ekd-tab-panel.active { display:block; }

    .ekd-table-wrap { overflow-x:auto; max-height:560px; overflow-y:auto; }
    .ekd-table { width:100%; border-collapse:collapse; background:#fff; font-size:14px; }
    .ekd-table th { position:sticky; top:0; background:#eef2f7; border-bottom:1px solid #cfd6e1; padding:7px 9px; white-space:nowrap; font-weight:700; z-index:2; cursor:pointer; user-select:none; }
    .ekd-table th:hover { background:#dbeafe; }
    .ekd-table td { border-bottom:1px solid #edf0f5; padding:6px 9px; vertical-align:middle; white-space:nowrap; }
    .ekd-table tbody tr:nth-child(even) { background:#fafbff; }
    .ekd-table tbody tr:hover { background:#eef6ff; }

    .ekd-text { white-space:normal !important; min-width:340px; }
    .ekd-material { white-space:normal !important; min-width:360px; }
    .ekd-num { text-align:right; }
    .ekd-center { text-align:center; }
    .ekd-badge { display:inline-block; padding:2px 8px; border-radius:999px; background:#e0ecff; color:#1d4ed8; font-weight:700; font-size:13px; }
    .ekd-filter { max-width:360px; font-size:14px; }

    .row-warning { background:#fff7ed !important; }
    .row-error { background:#fee2e2 !important; }
    .status-ok { color:#15803d; font-weight:700; }
    .status-warning { color:#b45309; font-weight:700; }
    .status-error { color:#b91c1c; font-weight:700; }

    .ekd-actions { background:#fff; border:1px solid #d9dee7; border-radius:10px; padding:16px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(15,23,42,.06); margin-top:16px; }
    .ekd-actions-left { font-size:13px; color:#64748b; }
    .ekd-actions-right { display:flex; gap:10px; align-items:center; }
    .ekd-btn { display:inline-flex; align-items:center; justify-content:center; font-weight:700; padding:10px 18px; border-radius:8px; font-size:14px; text-decoration:none; }
    .ekd-btn-primary { background:#16a34a; color:#fff; border:1px solid #16a34a; }
    .ekd-btn-primary:hover { background:#15803d; color:#fff; }
    .ekd-btn-secondary { background:#f8fafc; color:#334155; border:1px solid #cbd5e1; }
    .ekd-btn-secondary:hover { background:#e2e8f0; color:#334155; }
</style>

@php
    $totalModules = count($modules);
    $totalDespiece = count($despiece);
    $totalType1 = collect($despiece)->where('type_code', 1)->count();
    $totalType2 = collect($despiece)->where('type_code', 2)->count();
    $totalType4 = collect($despiece)->where('type_code', 4)->count();

    $missingMaterial = collect($despiece)->filter(fn($r) => empty($r['material_code']) || empty($r['material']))->count();
    $missingColor = collect($despiece)->filter(fn($r) => empty($r['color_code']) && ($r['type_code'] == 1 || $r['type_code'] == 2))->count();
    $badMeasures = collect($despiece)->filter(fn($r) => empty($r['length']) || empty($r['width']) || empty($r['thickness']) || empty($r['quantity']))->count();

    $modulesWithParts = collect($despiece)->pluck('module_number')->unique();
    $modulesWithoutParts = collect($modules)->filter(fn($m) => !$modulesWithParts->contains($m['number']))->count();

    $validationErrors = $missingMaterial + $badMeasures + $modulesWithoutParts;
    $validationWarnings = $missingColor;
@endphp

<div class="ekd-page">

    <div class="ekd-topbar">
        <div>
            <h3>Vista previa KitchenDraw</h3>
            <div class="ekd-topbar-subtitle">Validacion de modulos, piezas, materiales y medidas antes de guardar.</div>
        </div>

        <a href="{{ route('projects.index') }}" class="ekd-btn ekd-btn-secondary">Volver</a>
    </div>

    <div class="ekd-card">
        <div class="ekd-card-header">Cabecera del proyecto</div>
        <div class="ekd-card-body">
            <div class="ekd-summary-grid">
                <div class="ekd-summary-item"><div class="ekd-label">No. Presupuesto</div><div class="ekd-value">{{ $header['estimate_id'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Proyecto</div><div class="ekd-value">{{ $header['project_name'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Cliente</div><div class="ekd-value">{{ $header['client_name'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Empresa</div><div class="ekd-value">{{ $header['company_name'] ?? '' }}</div></div>

                <div class="ekd-summary-item"><div class="ekd-label">Disenador</div><div class="ekd-value">{{ $header['designer'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Vendedor</div><div class="ekd-value">{{ $header['seller_name'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Ciudad</div><div class="ekd-value">{{ $header['city'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Pais</div><div class="ekd-value">{{ $header['country'] ?? '' }}</div></div>

                <div class="ekd-summary-item"><div class="ekd-label">Estado</div><div class="ekd-value">{{ $header['state'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Moneda</div><div class="ekd-value">{{ $header['currency'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Total sin IVA</div><div class="ekd-value">{{ $header['currency'] ?? '' }} {{ $header['total_without_tax'] ?? '' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Total IVA</div><div class="ekd-value">{{ $header['currency'] ?? '' }} {{ $header['total_with_tax'] ?? '' }}</div></div>
            </div>
        </div>
    </div>

    <div class="ekd-card">
        <div class="ekd-card-header">Resumen y validaciones inteligentes</div>
        <div class="ekd-card-body">
            <div class="ekd-summary-grid">
                <div class="ekd-summary-item"><div class="ekd-label">Modulos</div><div class="ekd-value">{{ $totalModules }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Despiece total</div><div class="ekd-value">{{ $totalDespiece }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Frentes / Piezas / Materiales</div><div class="ekd-value">{{ $totalType2 }} / {{ $totalType1 }} / {{ $totalType4 }}</div></div>
                <div class="ekd-summary-item">
                    <div class="ekd-label">Estado de validacion</div>
                    <div class="ekd-value">
                        @if($validationErrors > 0)
                            <span class="status-error">{{ $validationErrors }} errores</span>
                        @elseif($validationWarnings > 0)
                            <span class="status-warning">{{ $validationWarnings }} alertas</span>
                        @else
                            <span class="status-ok">Sin observaciones</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-3">
                @if($modulesWithoutParts > 0)<div class="alert alert-danger py-2 mb-2">Hay {{ $modulesWithoutParts }} modulo(s) sin despiece detectado.</div>@endif
                @if($badMeasures > 0)<div class="alert alert-danger py-2 mb-2">Hay {{ $badMeasures }} fila(s) con cantidad, largo, ancho o espesor incompleto.</div>@endif
                @if($missingMaterial > 0)<div class="alert alert-danger py-2 mb-2">Hay {{ $missingMaterial }} fila(s) sin codigo o descripcion de material.</div>@endif
                @if($missingColor > 0)<div class="alert alert-warning py-2 mb-2">Hay {{ $missingColor }} fila(s) sin color separado.</div>@endif
            </div>
        </div>
    </div>

    <div class="ekd-card">
        <div class="ekd-card-body">

            <div class="ekd-tabs">
                <button type="button" class="ekd-tab active" onclick="showEkdTab('modulosTab', this)">Detalle de Modulos</button>
                <button type="button" class="ekd-tab" onclick="showEkdTab('despieceTab', this)">Detalle de Despiece</button>
            </div>

            <div class="ekd-tab-panel active" id="modulosTab">
                <div class="ekd-card mb-0">
                    <div class="ekd-card-header">
                        <div>Modulos / productos cotizados <span class="ekd-badge">{{ count($modules) }}</span></div>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <label class="mb-0"><input type="checkbox" id="onlyModuleIssues" onchange="applyModuleFilters()"> Solo observaciones</label>
                            <input type="text" id="modulesSearch" class="form-control form-control-sm ekd-filter" placeholder="Filtrar modulos..." onkeyup="applyModuleFilters()">
                        </div>
                    </div>

                    <div class="ekd-table-wrap">
                        <table class="ekd-table" id="modulesTable">
                            <thead>
                                <tr>
                                    <th onclick="sortTable('modulesTable', 0)">No</th>
                                    <th onclick="sortTable('modulesTable', 1)">Referencia</th>
                                    <th onclick="sortTable('modulesTable', 2)">Descripcion / modulo</th>
                                    <th onclick="sortTable('modulesTable', 3)">Ancho</th>
                                    <th onclick="sortTable('modulesTable', 4)">Alto</th>
                                    <th onclick="sortTable('modulesTable', 5)">Profundidad</th>
                                    <th onclick="sortTable('modulesTable', 6)">Cantidad</th>
                                    <th onclick="sortTable('modulesTable', 7)">P.V. IVA</th>
                                    <th onclick="sortTable('modulesTable', 8)">Total IVA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                    @php $hasParts = $modulesWithParts->contains($module['number']); @endphp
                                    <tr data-status="{{ $hasParts ? 'ok' : 'warning' }}" class="{{ $hasParts ? '' : 'row-error' }}">
                                        <td class="ekd-num">{{ $module['number'] }}</td>
                                        <td><strong>{{ $module['reference'] }}</strong></td>
                                        <td class="ekd-text">{{ $module['description'] }}</td>
                                        <td class="ekd-num">{{ $module['dx'] }}</td>
                                        <td class="ekd-num">{{ $module['dz'] }}</td>
                                        <td class="ekd-num">{{ $module['dy'] }}</td>
                                        <td class="ekd-center">{{ $module['quantity'] }}</td>
                                        <td class="ekd-num">{{ $module['pvp'] }}</td>
                                        <td class="ekd-num"><strong>{{ $module['total'] }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="ekd-tab-panel" id="despieceTab">
                <div class="ekd-card mb-0">
                    <div class="ekd-card-header">
                        <div>Despiece <span class="ekd-badge">{{ count($despiece) }}</span></div>

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <label class="mb-0"><input type="checkbox" class="ekd-type-filter" value="2" checked onchange="applyDespieceFilters()"> Frentes</label>
                            <label class="mb-0"><input type="checkbox" class="ekd-type-filter" value="1" checked onchange="applyDespieceFilters()"> Piezas</label>
                            <label class="mb-0"><input type="checkbox" class="ekd-type-filter" value="4" checked onchange="applyDespieceFilters()"> Materiales</label>
                            <label class="mb-0"><input type="checkbox" id="onlyIssues" onchange="applyDespieceFilters()"> Solo observaciones</label>
                            <input type="text" id="despieceSearch" class="form-control form-control-sm ekd-filter" placeholder="Filtrar despiece..." onkeyup="applyDespieceFilters()">
                        </div>
                    </div>

                    <div class="ekd-table-wrap">
                        <table class="ekd-table" id="despieceTable">
                            <thead>
                                <tr>
                                    <th onclick="sortTable('despieceTable', 0)">No</th>
                                    <th onclick="sortTable('despieceTable', 1)">Ref. codigo</th>
                                    <th onclick="sortTable('despieceTable', 2)">TipoCod</th>
                                    <th onclick="sortTable('despieceTable', 3)">Desc. pieza</th>
                                    <th onclick="sortTable('despieceTable', 4)">Largo</th>
                                    <th onclick="sortTable('despieceTable', 5)">Ancho</th>
                                    <th onclick="sortTable('despieceTable', 6)">Cantidad</th>
                                    <th onclick="sortTable('despieceTable', 7)">Cod. Material</th>
                                    <th onclick="sortTable('despieceTable', 8)">Material</th>
                                    <th onclick="sortTable('despieceTable', 9)">Cod. Color</th>
                                    <th onclick="sortTable('despieceTable', 10)">Color</th>
                                    <th onclick="sortTable('despieceTable', 11)">Espesor</th>
                                    <th>Validacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($despiece as $row)
                                    @php
                                        $issues = [];
                                        if (empty($row['quantity']) || empty($row['length']) || empty($row['width']) || empty($row['thickness'])) $issues[] = 'Medidas incompletas';
                                        if (empty($row['material_code']) || empty($row['material'])) $issues[] = 'Material incompleto';
                                        if (($row['type_code'] == 1 || $row['type_code'] == 2) && empty($row['color_code'])) $issues[] = 'Color sin separar';
                                        $status = count($issues) > 0 ? 'warning' : 'ok';
                                    @endphp
                                    <tr data-type-code="{{ $row['type_code'] }}" data-status="{{ $status }}" class="{{ $status === 'warning' ? 'row-warning' : '' }}">
                                        <td class="ekd-num">{{ $row['module_number'] }}</td>
                                        <td><strong>{{ $row['module_reference'] }}</strong></td>
                                        <td class="ekd-center">{{ $row['type_code'] }}</td>
                                        <td>{{ $row['part'] }}</td>
                                        <td class="ekd-num">{{ $row['length'] }}</td>
                                        <td class="ekd-num">{{ $row['width'] }}</td>
                                        <td class="ekd-center">{{ $row['quantity'] }}</td>
                                        <td class="ekd-center">{{ $row['material_code'] }}</td>
                                        <td class="ekd-material">{{ $row['material'] }}</td>
                                        <td class="ekd-center">{{ $row['color_code'] ?? '' }}</td>
                                        <td>{{ $row['color_description'] ?? '' }}</td>
                                        <td class="ekd-num">{{ $row['thickness'] }}</td>
                                        <td>
                                            @if(count($issues) === 0)
                                                <span class="status-ok">OK</span>
                                            @else
                                                <span class="status-warning">{{ implode(', ', $issues) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="ekd-actions">
        <div class="ekd-actions-left">Verifica que no existan observaciones antes de guardar la validacion tecnica.</div>

        <div class="ekd-actions-right">
            <a href="{{ route('projects.index') }}" class="ekd-btn ekd-btn-secondary">Volver</a>

            <form action="{{ route('projects.storeFinal') }}" method="POST" class="mb-0">
                @csrf
                <input type="hidden" name="data" value="{{ json_encode([
                    'header' => $header,
                    'headings' => $headings,
                    'modules' => $modules,
                    'despiece' => $despiece,
                    'files' => $files,
                ]) }}">
                <button type="submit" class="ekd-btn ekd-btn-primary">Guardar validacion tecnica</button>
            </form>
        </div>
    </div>

</div>

<script>
    const sortState = {};

    function normalizeValue(value) {
        value = (value || '').trim();
        const numeric = value.replace(',', '.').replace(/[^\d.-]/g, '');
        if (numeric !== '' && !isNaN(numeric)) return parseFloat(numeric);
        return value.toLowerCase();
    }

    function sortTable(tableId, columnIndex) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        const key = tableId + '_' + columnIndex;
        sortState[key] = sortState[key] === 'asc' ? 'desc' : 'asc';
        const direction = sortState[key];

        rows.sort((a, b) => {
            const aValue = normalizeValue(a.children[columnIndex]?.innerText || '');
            const bValue = normalizeValue(b.children[columnIndex]?.innerText || '');

            if (typeof aValue === 'number' && typeof bValue === 'number') {
                return direction === 'asc' ? aValue - bValue : bValue - aValue;
            }

            return direction === 'asc'
                ? String(aValue).localeCompare(String(bValue))
                : String(bValue).localeCompare(String(aValue));
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    function applyModuleFilters() {
        const query = (document.getElementById('modulesSearch')?.value || '').toLowerCase();
        const onlyIssues = document.getElementById('onlyModuleIssues')?.checked || false;
        const rows = document.querySelectorAll('#modulesTable tbody tr');

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const text = row.innerText.toLowerCase();
            const textOk = text.includes(query);
            const issueOk = !onlyIssues || status !== 'ok';
            row.style.display = (textOk && issueOk) ? '' : 'none';
        });
    }

    function applyDespieceFilters() {
        const checkedTypes = Array.from(document.querySelectorAll('.ekd-type-filter:checked')).map(input => input.value);
        const query = (document.getElementById('despieceSearch')?.value || '').toLowerCase();
        const onlyIssues = document.getElementById('onlyIssues')?.checked || false;
        const rows = document.querySelectorAll('#despieceTable tbody tr');

        rows.forEach(row => {
            const type = row.getAttribute('data-type-code');
            const status = row.getAttribute('data-status');
            const text = row.innerText.toLowerCase();
            const typeOk = checkedTypes.includes(type);
            const textOk = text.includes(query);
            const issueOk = !onlyIssues || status !== 'ok';
            row.style.display = (typeOk && textOk && issueOk) ? '' : 'none';
        });
    }

    function showEkdTab(panelId, button) {
        document.querySelectorAll('.ekd-tab-panel').forEach(panel => panel.classList.remove('active'));
        document.querySelectorAll('.ekd-tab').forEach(tab => tab.classList.remove('active'));
        document.getElementById(panelId).classList.add('active');
        button.classList.add('active');
    }
</script>
@endsection
