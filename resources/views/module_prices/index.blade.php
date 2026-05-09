@extends('layouts.app')

@section('content')
<style>
    .mp-page { padding:18px; background:#f4f6f9; }
    .mp-card { background:#fff; border:1px solid #d9dee7; border-radius:10px; padding:16px; box-shadow:0 2px 8px rgba(15,23,42,.05); }
    .mp-title { font-size:18px; font-weight:800; color:#111827; margin-bottom:4px; }
    .mp-subtitle { font-size:13px; color:#6b7280; margin-bottom:14px; }
    .mp-table { width:100%; border-collapse:collapse; font-size:13px; }
    .mp-table th { background:#eef2f7; padding:8px; border-bottom:1px solid #d9dee7; text-align:left; white-space:nowrap; }
    .mp-table td { padding:8px; border-bottom:1px solid #edf0f5; vertical-align:middle; }
    .mp-table tr:hover { background:#f8fafc; }
    .mp-input { width:90px; border:1px solid #d1d5db; border-radius:6px; padding:5px; }
    .mp-select { width:110px; border:1px solid #d1d5db; border-radius:6px; padding:5px; }
    .mp-btn { background:#2563eb; color:#fff; border:0; border-radius:7px; padding:7px 10px; font-weight:700; cursor:pointer; }
    .mp-badge-on { background:#dcfce7; color:#166534; padding:4px 8px; border-radius:999px; font-weight:700; font-size:12px; }
    .mp-badge-off { background:#fee2e2; color:#991b1b; padding:4px 8px; border-radius:999px; font-weight:700; font-size:12px; }
    .mp-alert-success { background:#ecfdf5; border:1px solid #86efac; color:#166534; padding:12px 42px 12px 12px; border-radius:8px; margin-bottom:14px; font-weight:700; position:relative; transition:opacity .4s ease; }
    .mp-alert-close { position:absolute; right:12px; top:8px; border:0; background:transparent; color:inherit; font-size:18px; font-weight:800; cursor:pointer; }
</style>

<div class="mp-page">
    @if(session('success'))
        <div class="mp-alert-success" id="mpSuccessAlert">
            {{ session('success') }}
            <button type="button" class="mp-alert-close" onclick="document.getElementById('mpSuccessAlert').style.display='none'">x</button>
        </div>
    @endif

    <div class="mp-card">
        <div class="mp-title">Costos de modulos</div>
        <div class="mp-subtitle">Configura mano de obra, indirectos, desperdicio y complejidad por modulo maestro.</div>

        <table class="mp-table">
            <thead>
                <tr>
                    <th>Catalogo</th>
                    <th>Referencia</th>
                    <th>Descripcion</th>
                    <th>Medidas</th>
                    <th>Modelo</th>
                    <th>Complejidad</th>
                    <th>Factor</th>
                    <th>MO</th>
                    <th>Indirectos</th>
                    <th>Desperdicio %</th>
                    <th>Activo</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modulePrices as $item)
                    <tr>
                        <form action="{{ route('module-prices.update', $item) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <td>{{ $item->catalog_code }}</td>
                            <td><strong>{{ $item->reference }}</strong></td>
                            <td>{{ $item->description_base }}</td>
                            <td>{{ number_format($item->dx, 0) }} x {{ number_format($item->dy, 0) }} x {{ number_format($item->dz, 0) }}</td>
                            <td>{{ $item->model }}</td>

                            <td>
                                <select name="complexity_level" class="mp-select">
                                    <option value="simple" {{ $item->complexity_level === 'simple' ? 'selected' : '' }}>simple</option>
                                    <option value="media" {{ $item->complexity_level === 'media' ? 'selected' : '' }}>media</option>
                                    <option value="alta" {{ $item->complexity_level === 'alta' ? 'selected' : '' }}>alta</option>
                                    <option value="especial" {{ $item->complexity_level === 'especial' ? 'selected' : '' }}>especial</option>
                                </select>
                            </td>

                            <td><input name="complexity_factor" class="mp-input" type="number" step="0.01" min="0" value="{{ $item->complexity_factor }}"></td>
                            <td><input name="labor_cost" class="mp-input" type="number" step="0.01" min="0" value="{{ $item->labor_cost }}"></td>
                            <td><input name="indirect_cost" class="mp-input" type="number" step="0.01" min="0" value="{{ $item->indirect_cost }}"></td>
                            <td><input name="default_waste_percent" class="mp-input" type="number" step="0.01" min="0" max="100" value="{{ $item->default_waste_percent }}"></td>

                            <td>
                                <label>
                                    <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
                                    <span class="{{ $item->is_active ? 'mp-badge-on' : 'mp-badge-off' }}">
                                        {{ $item->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </label>
                            </td>

                            <td>
                                <button type="submit" class="mp-btn">Guardar</button>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">No hay modulos maestros cargados todavia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var alertBox = document.getElementById('mpSuccessAlert');

        if (alertBox) {
            setTimeout(function () {
                alertBox.style.opacity = '0';

                setTimeout(function () {
                    alertBox.style.display = 'none';
                }, 400);
            }, 5000);
        }
    });
</script>
@endsection