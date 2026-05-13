@extends('layouts.app')

@section('content')
<style>
    .ekd-page { background:#f4f6f9; padding:18px; font-size:15px; color:#111827; }
    .ekd-card { background:#fff; border:1px solid #d9dee7; border-radius:10px; box-shadow:0 2px 8px rgba(15,23,42,.06); margin-bottom:16px; overflow:hidden; }
    .ekd-card-header { padding:10px 14px; background:#f8fafc; border-bottom:1px solid #d9dee7; font-weight:700; font-size:16px; display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .ekd-card-body { padding:14px; }
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
    .ekd-table th { position:sticky; top:0; background:#eef2f7; border-bottom:1px solid #cfd6e1; padding:7px 9px; white-space:nowrap; font-weight:700; }
    .ekd-table td { border-bottom:1px solid #edf0f5; padding:6px 9px; vertical-align:middle; white-space:nowrap; }
    .ekd-table tbody tr:nth-child(even) { background:#fafbff; }
    .ekd-table tbody tr:hover { background:#eef6ff; }
    .ekd-text { white-space:normal !important; min-width:340px; }
    .ekd-material { white-space:normal !important; min-width:360px; }
    .ekd-num { text-align:right; }
    .ekd-center { text-align:center; }
    .ekd-badge { display:inline-block; padding:2px 8px; border-radius:999px; background:#e0ecff; color:#1d4ed8; font-weight:700; font-size:13px; }
    .status-ok { color:#15803d; font-weight:700; }
    .status-warning { color:#b45309; font-weight:700; }
    .row-warning { background:#fff7ed !important; }
    .ekd-flash {
        position:relative;
        padding:12px 42px 12px 12px;
        border-radius:8px;
        margin:12px 0;
        font-weight:700;
        transition:opacity .4s ease;
    }

    .ekd-flash-success {
        background:#ecfdf5;
        border:1px solid #86efac;
        color:#166534;
    }

    .ekd-flash-error {
        background:#fef2f2;
        border:1px solid #fca5a5;
        color:#991b1b;
    }

    .ekd-flash-close {
        position:absolute;
        right:12px;
        top:8px;
        border:0;
        background:transparent;
        color:inherit;
        font-size:18px;
        font-weight:800;
        cursor:pointer;
    }

.ekd-special-alt {
    display:inline-block;
    background:#e0f2fe;
    color:#075985;
    border:1px solid #7dd3fc;
    border-radius:999px;
    padding:3px 8px;
    font-size:11px;
    font-weight:800;
    white-space:nowrap;
}

.ekd-special-note {
    display:inline-block;
    background:#fef3c7;
    color:#92400e;
    border:1px solid #fbbf24;
    border-radius:8px;
    padding:4px 8px;
    font-size:11px;
    font-weight:800;
    max-width:220px;
    white-space:normal;
}
</style>

@php
    $header = $data['header'] ?? [];
    $modules = $data['modules'] ?? [];
    $despiece = $data['despiece'] ?? [];

    $totalModules = count($modules);
    $totalDespiece = count($despiece);
    $totalType1 = collect($despiece)->where('type_code', 1)->count();
    $totalType2 = collect($despiece)->where('type_code', 2)->count();
    $totalType4 = collect($despiece)->where('type_code', 4)->count();

    $pvpKitchen = (float) ($header['total_with_tax'] ?? 0);

    $wastePercent = 10;

    $totalBaseCost = collect($costRows ?? [])->sum('total_cost');
    $wasteCost = $totalBaseCost * ($wastePercent / 100);
    $totalEstimatedCost = $totalBaseCost + $wasteCost;
    $grossMargin = $pvpKitchen - $totalEstimatedCost;
    $grossMarginPercent = $pvpKitchen > 0 ? ($grossMargin / $pvpKitchen) * 100 : 0;

    $missingPrices = collect($costRows ?? [])->where('found', false)->count();

    $materialSummary = collect($costRows ?? [])->groupBy(function ($row) {
        return ($row['material_code'] ?? '') . '|' . ($row['color_code'] ?? '');
    })->map(function ($rows) use ($wastePercent) {
        $first = $rows->first();
        $area = $rows->sum('area_m2');
        $base = $rows->sum('total_cost');
        $waste = $base * ($wastePercent / 100);

        return [
            'material_code' => $first['material_code'] ?? '',
            'material' => $first['material'] ?? '',
            'color_code' => $first['color_code'] ?? '',
            'color_description' => $first['color_description'] ?? '',
            'area_m2' => $area,
            'unit_cost' => $first['unit_cost'] ?? null,
            'base_cost' => $base,
            'waste_cost' => $waste,
            'total_cost' => $base + $waste,
            'found' => $first['found'] ?? false,
            'rows_count' => $rows->count(),
        ];
    })->values();
@endphp

<div class="ekd-page">

@if(session('success'))
    <div class="ekd-flash ekd-flash-success" id="ekdFlashMessage">
        {{ session('success') }}
        <button type="button" class="ekd-flash-close" onclick="document.getElementById('ekdFlashMessage').style.display='none'">x</button>
    </div>
@endif

@if($errors->any())
    <div class="ekd-flash ekd-flash-error" id="ekdFlashError">
        {{ $errors->first() }}
        <button type="button" class="ekd-flash-close" onclick="document.getElementById('ekdFlashError').style.display='none'">x</button>
    </div>
@endif


    <div class="ekd-card">
        <div class="ekd-card-header">
            <div>Proyecto guardado #{{ $project->id }}</div>
            <div style="display:flex; gap:8px; align-items:center;">
    <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
    <button type="button" class="btn btn-warning btn-sm" onclick="document.getElementById('replaceSceneBox').style.display = document.getElementById('replaceSceneBox').style.display === 'none' ? 'block' : 'none';">
        Reemplazar escena
    </button>
</div>
        </div>
        <div class="ekd-card-body">
            <div class="ekd-summary-grid">
                <div class="ekd-summary-item"><div class="ekd-label">Proyecto</div><div class="ekd-value">{{ $header['project_name'] ?? $project->name }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Cliente</div><div class="ekd-value">{{ $header['client_name'] ?? '-' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Disenador</div><div class="ekd-value">{{ $header['designer'] ?? '-' }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Estado</div><div class="ekd-value">{{ $project->status }}</div></div>

                <div class="ekd-summary-item"><div class="ekd-label">Modulos</div><div class="ekd-value">{{ $totalModules }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Despiece</div><div class="ekd-value">{{ $totalDespiece }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Frentes / Piezas / Materiales</div><div class="ekd-value">{{ $totalType2 }} / {{ $totalType1 }} / {{ $totalType4 }}</div></div>
                <div class="ekd-summary-item"><div class="ekd-label">Total KitchenDraw</div><div class="ekd-value">{{ $header['currency'] ?? '' }} {{ number_format($pvpKitchen, 2) }}</div></div>
            </div>
        </div>
    </div>

    <div class="ekd-card">
        <div class="ekd-card-body">
            
<div id="replaceSceneBox" class="ekd-card" style="display:none; margin-top:12px;">
    <div class="ekd-card-header">Reemplazar escena KitchenDraw</div>
    <div class="ekd-card-body">
        <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:10px; align-items:end;">
                <div>
                    <label class="ekd-label">Nuevo XML</label>
                    <input type="file" name="xml_file" class="form-control form-control-sm" required>
                </div>

                <div>
                    <label class="ekd-label">Nuevo TXT</label>
                    <input type="file" name="txt_file" class="form-control form-control-sm" required>
                </div>

                <div>
                    <label class="ekd-label">Nuevo SCN</label>
                    <input type="file" name="scn_file" class="form-control form-control-sm">
                </div>

                <div>
                    <button type="submit" class="btn btn-warning btn-sm">
                        Reemplazar
                    </button>
                </div>
            </div>

            <div style="font-size:12px; color:#64748b; margin-top:8px;">
                Esta accion reemplazara los datos tecnicos del proyecto y recalculara la informacion desde los nuevos archivos.
            </div>
        </form>
    </div>
</div>

<div class="ekd-tabs">
                <button type="button" class="ekd-tab active" onclick="showEkdTab('resumenTab', this)">Resumen</button>
                <button type="button" class="ekd-tab" onclick="showEkdTab('modulosTab', this)">Detalle validado</button>
                <button type="button" class="ekd-tab" onclick="showEkdTab('despieceTab', this)">Despiece</button>
                <button type="button" class="ekd-tab" onclick="showEkdTab('costosTab', this)">Costos reales</button>
                <button type="button" class="ekd-tab" onclick="showEkdTab('pvpTab', this)">Comparacion PVP</button>
            </div>

            <div class="ekd-tab-panel active" id="resumenTab">
                <div class="ekd-card mb-0">
                    <div class="ekd-card-header">Resumen del proyecto</div>
                    <div class="ekd-card-body">
                        <div class="ekd-summary-grid">
                            <div class="ekd-summary-item"><div class="ekd-label">No. Presupuesto</div><div class="ekd-value">{{ $header['estimate_id'] ?? '-' }}</div></div>
                            <div class="ekd-summary-item"><div class="ekd-label">Empresa</div><div class="ekd-value">{{ $header['company_name'] ?? '-' }}</div></div>
                            <div class="ekd-summary-item"><div class="ekd-label">Vendedor</div><div class="ekd-value">{{ $header['seller_name'] ?? '-' }}</div></div>
                            <div class="ekd-summary-item"><div class="ekd-label">Moneda</div><div class="ekd-value">{{ $header['currency'] ?? '-' }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ekd-tab-panel" id="modulosTab">
                <div class="ekd-card mb-0">
                    <div class="ekd-card-header">Modulos validados <span class="ekd-badge">{{ count($modules) }}</span></div>
                    <div class="ekd-table-wrap">
                        <table class="ekd-table">
                            <thead>
                                <tr>
                                    <th>No</th><th>Referencia</th><th>Descripcion</th><th>Cod. alterno</th><th>Nota fabricacion</th><th>Ancho</th><th>Alto</th><th>Profundidad</th><th>Cantidad</th><th>P.V. IVA</th><th>Total IVA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                    <tr>
                                        <td class="ekd-num">{{ $module['number'] }}</td>
                                        <td><strong>{{ $module['reference'] }}</strong></td>
                                        <td class="ekd-text">{{ $module['description'] }}</td>

                                        <td>
                                            @if(!empty($module['alternate_code']))
                                                <span class="ekd-special-alt">{{ $module['alternate_code'] }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if(!empty($module['manufacturing_note']))
                                                <span class="ekd-special-note">{{ $module['manufacturing_note'] }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>

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
                    <div class="ekd-card-header">Despiece validado <span class="ekd-badge">{{ count($despiece) }}</span></div>
                    <div class="ekd-table-wrap">
                        <table class="ekd-table">
                            <thead>
                                <tr>
                                    <th>No</th><th>Ref. codigo</th><th>TipoCod</th><th>Desc. pieza</th><th>Largo</th><th>Ancho</th><th>Cantidad</th><th>Cod. Material</th><th>Material</th><th>Cod. Color</th><th>Color</th><th>Espesor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($despiece as $row)
                                    <tr>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="ekd-tab-panel" id="costosTab">
                <div class="ekd-card mb-0">
                    <div class="ekd-card-header">
                        <div>Costos reales Edificar</div>
                        <span class="ekd-badge">{{ count($costRows ?? []) }}</span>
                    </div>

                    <div class="ekd-card-body">
                        <div class="ekd-summary-grid">
                            <div class="ekd-summary-item"><div class="ekd-label">Costo base</div><div class="ekd-value">{{ number_format($totalBaseCost, 2) }}</div></div>
                            <div class="ekd-summary-item"><div class="ekd-label">Desperdicio {{ $wastePercent }}%</div><div class="ekd-value">{{ number_format($wasteCost, 2) }}</div></div>
                            <div class="ekd-summary-item"><div class="ekd-label">Costo estimado</div><div class="ekd-value">{{ number_format($totalEstimatedCost, 2) }}</div></div>
                            <div class="ekd-summary-item">
                                <div class="ekd-label">Sin precio interno</div>
                                <div class="ekd-value">
                                    @if($missingPrices > 0)
                                        <span class="status-warning">{{ $missingPrices }}</span>
                                    @else
                                        <span class="status-ok">0</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ekd-card-header">Resumen por material</div>
                    <div class="ekd-table-wrap">
                        <table class="ekd-table">
                            <thead>
                                <tr>
                                    <th>Cod. Material</th>
                                    <th>Material</th>
                                    <th>Color</th>
                                    <th>Filas</th>
                                    <th>Area m2</th>
                                    <th>Costo unit.</th>
                                    <th>Costo base</th>
                                    <th>Desperdicio</th>
                                    <th>Total estimado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialSummary as $row)
                                    <tr class="{{ $row['found'] ? '' : 'row-warning' }}">
                                        <td class="ekd-center">{{ $row['material_code'] }}</td>
                                        <td class="ekd-material">{{ $row['material'] }}</td>
                                        <td>{{ $row['color_code'] }} {{ $row['color_description'] }}</td>
                                        <td class="ekd-center">{{ $row['rows_count'] }}</td>
                                        <td class="ekd-num">{{ number_format($row['area_m2'], 4) }}</td>
                                        <td class="ekd-num">{{ $row['unit_cost'] !== null ? number_format($row['unit_cost'], 4) : '-' }}</td>
                                        <td class="ekd-num">{{ number_format($row['base_cost'], 2) }}</td>
                                        <td class="ekd-num">{{ number_format($row['waste_cost'], 2) }}</td>
                                        <td class="ekd-num"><strong>{{ number_format($row['total_cost'], 2) }}</strong></td>
                                        <td>
                                            @if($row['found'])
                                                <span class="status-ok">OK</span>
                                            @else
                                                <span class="status-warning">Sin precio</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="ekd-card-header">Detalle costeado por pieza</div>
                    <div class="ekd-table-wrap">
                        <table class="ekd-table">
                            <thead>
                                <tr>
                                    <th>No</th><th>Modulo</th><th>Tipo</th><th>Pieza</th><th>Largo</th><th>Ancho</th><th>Cant.</th><th>Area m2</th><th>Cod. Material</th><th>Color</th><th>Costo unit.</th><th>Costo total</th><th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($costRows ?? []) as $row)
                                    <tr class="{{ $row['found'] ? '' : 'row-warning' }}">
                                        <td class="ekd-num">{{ $row['module_number'] }}</td>
                                        <td><strong>{{ $row['module_reference'] }}</strong></td>
                                        <td class="ekd-center">{{ $row['type_code'] }}</td>
                                        <td>{{ $row['part'] }}</td>
                                        <td class="ekd-num">{{ $row['length'] }}</td>
                                        <td class="ekd-num">{{ $row['width'] }}</td>
                                        <td class="ekd-center">{{ $row['quantity'] }}</td>
                                        <td class="ekd-num">{{ number_format($row['area_m2'], 4) }}</td>
                                        <td class="ekd-center">{{ $row['material_code'] }}</td>
                                        <td>{{ $row['color_code'] }} {{ $row['color_description'] }}</td>
                                        <td class="ekd-num">{{ $row['unit_cost'] !== null ? number_format($row['unit_cost'], 4) : '-' }}</td>
                                        <td class="ekd-num">{{ $row['total_cost'] !== null ? number_format($row['total_cost'], 2) : '-' }}</td>
                                        <td>
                                            @if($row['found'])
                                                <span class="status-ok">OK</span>
                                            @else
                                                <span class="status-warning">Sin precio</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="ekd-tab-panel" id="pvpTab">
                <div class="ekd-card mb-0">
                    <div class="ekd-card-header">Comparacion PVP KitchenDraw vs Edificar</div>
                    <div class="ekd-card-body">

                        <div class="ekd-summary-grid">

                            <div class="ekd-summary-item">
                                <div class="ekd-label">PVP KitchenDraw</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['pvp_kd'], 2) }}
                                </div>
                            </div>

                            <div class="ekd-summary-item">
                                <div class="ekd-label">Costo materiales</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['material_base_cost'], 2) }}
                                </div>
                            </div>

                            <div class="ekd-summary-item">
                                <div class="ekd-label">Desperdicio {{ $commercialSummary['waste_percent'] }}%</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['waste_cost'], 2) }}
                                </div>
                            </div>

                            <div class="ekd-summary-item">
                                <div class="ekd-label">Costo EDIFICAR</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['edificar_cost'], 2) }}
                                </div>
                            </div>

                            <div class="ekd-summary-item">
                                <div class="ekd-label">Utilidad aplicada {{ $commercialSummary['profit_percent'] }}%</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['pvp_edificar'] - $commercialSummary['edificar_cost'], 2) }}
                                </div>
                            </div>

                            <div class="ekd-summary-item">
                                <div class="ekd-label">PVP EDIFICAR</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['pvp_edificar'], 2) }}
                                </div>
                            </div>

                            <div class="ekd-summary-item">
                                <div class="ekd-label">Diferencia KD vs EDIFICAR</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['difference'], 2) }}
                                </div>
                            </div>

                            <div class="ekd-summary-item">
                                <div class="ekd-label">Margen KD estimado</div>
                                <div class="ekd-value">
                                    {{ number_format($commercialSummary['margin_kd'], 2) }}%
                                </div>
                            </div>

                        </div>

                        <div class="mt-3">

                            @if($commercialSummary['missing_cost_rows'] > 0)
                                <div class="alert alert-warning mb-0">
                                    Existen {{ $commercialSummary['missing_cost_rows'] }} fila(s) sin costo interno configurado. El calculo es parcial.
                                </div>
                            @else
                                <div class="alert alert-success mb-0">
                                    Comparacion calculada con todos los materiales costeados.
                                </div>
                            @endif

                        </div>

                    </div>
                </div>

                <div class="ekd-card mt-3 mb-0">
                    <div class="ekd-card-header">Comparacion por modulo</div>

                    <div class="ekd-table-wrap">
                        <table class="ekd-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Modulo</th>
                                    <th>Catalogo</th>
                                    <th>Medidas</th>
                                    <th>PVP KD</th>
                                    <th>Materiales</th>
                                    <th>Desperdicio</th>
                                    <th>MO</th>
                                    <th>Indirectos</th>
                                    <th>Factor</th>
                                    <th>Costo EDIFICAR</th>
                                    <th>PVP EDIFICAR</th>
                                    <th>Diferencia</th>
                                    <th>Margen KD</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($moduleSummaries ?? []) as $module)
                                    <tr class="{{ ($module['missing_cost_rows'] > 0 || !$module['configured']) ? 'row-warning' : '' }}">
                                        <td class="ekd-num">{{ $module['module_number'] }}</td>
                                        <td>
                                            <strong>{{ $module['reference'] }}</strong>
                                            <div style="font-size:11px; color:#6b7280;">
                                                {{ Str::limit($module['description'], 55) }}
                                            </div>
                                        </td>
                                        <td>{{ $module['catalog_code'] }}</td>
                                        <td class="ekd-num">{{ $module['dx'] }} x {{ $module['dy'] }} x {{ $module['dz'] }}</td>
                                        <td class="ekd-num">{{ number_format($module['pvp_kd'], 2) }}</td>
                                        <td class="ekd-num">{{ number_format($module['material_cost'], 2) }}</td>
                                        <td class="ekd-num">{{ number_format($module['waste_cost'], 2) }}</td>
                                        <td class="ekd-num">{{ number_format($module['labor_cost'], 2) }}</td>
                                        <td class="ekd-num">{{ number_format($module['indirect_cost'], 2) }}</td>
                                        <td class="ekd-num">{{ number_format($module['complexity_factor'], 2) }}</td>
                                        <td class="ekd-num"><strong>{{ number_format($module['edificar_cost'], 2) }}</strong></td>
                                        <td class="ekd-num"><strong>{{ number_format($module['pvp_edificar'], 2) }}</strong></td>
                                        <td class="ekd-num">{{ number_format($module['difference'], 2) }}</td>
                                        <td class="ekd-num">{{ number_format($module['margin_kd'], 2) }}%</td>
                                        <td>
                                            @if($module['missing_cost_rows'] > 0)
                                                <span class="status-warning">Costo parcial</span>
                                            @elseif(!$module['configured'])
                                                <span class="status-warning">No configurado</span>
                                            @else
                                                <span class="status-ok">OK</span>
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

</div>

<script>
    function showEkdTab(panelId, button) {
        document.querySelectorAll('.ekd-tab-panel').forEach(panel => panel.classList.remove('active'));
        document.querySelectorAll('.ekd-tab').forEach(tab => tab.classList.remove('active'));
        document.getElementById(panelId).classList.add('active');
        button.classList.add('active');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        ['ekdFlashMessage', 'ekdFlashError'].forEach(function (id) {
            var box = document.getElementById(id);

            if (box) {
                setTimeout(function () {
                    box.style.opacity = '0';

                    setTimeout(function () {
                        box.style.display = 'none';
                    }, 400);
                }, 5000);
            }
        });
    });
</script>
@endsection
