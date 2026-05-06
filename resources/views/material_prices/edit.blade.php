@extends('layouts.app')

@section('content')
<style>
    .mp-page { background:#f4f6f9; padding:18px; font-size:15px; color:#111827; }
    .mp-card { background:#fff; border:1px solid #d9dee7; border-radius:10px; box-shadow:0 2px 8px rgba(15,23,42,.06); margin-bottom:16px; overflow:hidden; max-width:900px; }
    .mp-card-header { padding:12px 16px; background:#f8fafc; border-bottom:1px solid #d9dee7; font-weight:800; font-size:17px; display:flex; justify-content:space-between; align-items:center; }
    .mp-card-body { padding:16px; }
    .mp-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .mp-actions { margin-top:16px; display:flex; justify-content:flex-end; gap:10px; }
</style>

<div class="mp-page">

    <div class="mp-card">
        <div class="mp-card-header">
            <div>Editar costo de material</div>
            <a href="{{ route('material-prices.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>

        <div class="mp-card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    Revisa los campos ingresados.
                </div>
            @endif

            <form method="POST" action="{{ route('material-prices.update', $materialPrice) }}">
                @csrf
                @method('PUT')

                <div class="mp-grid">
                    <div>
                        <label class="form-label">Cod. Material</label>
                        <input type="text" class="form-control" value="{{ $materialPrice->material_code }}" disabled>
                    </div>

                    <div>
                        <label class="form-label">Cod. Color</label>
                        <input type="text" class="form-control" value="{{ $materialPrice->color_code }}" disabled>
                    </div>

                    <div>
                        <label class="form-label">Material</label>
                        <input type="text" name="material_name" class="form-control" value="{{ old('material_name', $materialPrice->material_name) }}" required>
                    </div>

                    <div>
                        <label class="form-label">Color</label>
                        <input type="text" name="color_name" class="form-control" value="{{ old('color_name', $materialPrice->color_name) }}">
                    </div>

                    <div>
                        <label class="form-label">Unidad</label>
                        <select name="unit_type" class="form-control" required>
                            <option value="m2" @selected(old('unit_type', $materialPrice->unit_type) === 'm2')>m2</option>
                            <option value="ml" @selected(old('unit_type', $materialPrice->unit_type) === 'ml')>ml</option>
                            <option value="unidad" @selected(old('unit_type', $materialPrice->unit_type) === 'unidad')>unidad</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Costo unitario</label>
                        <input type="number" step="0.0001" min="0" name="unit_cost" class="form-control" value="{{ old('unit_cost', $materialPrice->unit_cost) }}" required>
                    </div>

                    <div>
                        <label class="form-label">Activo</label><br>
                        <label>
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $materialPrice->is_active))>
                            Material activo para costeo
                        </label>
                    </div>
                </div>

                <div class="mp-actions">
                    <a href="{{ route('material-prices.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar costo</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
