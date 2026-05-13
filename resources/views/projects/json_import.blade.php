@extends('layouts.app')

@section('content')
<style>
    .json-page { padding:18px; background:#f4f6f9; color:#111827; }
    .json-card { background:#fff; border:1px solid #d9dee7; border-radius:10px; box-shadow:0 2px 8px rgba(15,23,42,.06); margin-bottom:16px; overflow:hidden; max-width:980px; }
    .json-card-header { padding:12px 16px; background:#f8fafc; border-bottom:1px solid #d9dee7; font-weight:800; display:flex; justify-content:space-between; align-items:center; }
    .json-card-body { padding:16px; }
    .json-help { font-size:13px; color:#64748b; margin-top:6px; line-height:1.5; }
    .json-actions { margin-top:16px; display:flex; justify-content:flex-end; gap:8px; }
</style>

<div class="json-page">
    <div class="json-card">
        <div class="json-card-header">
            <div>Importar EDIFICAR KD JSON</div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>

        <div class="json-card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('projects.json.preview') }}" enctype="multipart/form-data">
                @csrf

                <label class="form-label fw-bold">Archivo edificar_import.json</label>
                <input type="file" name="json_file" class="form-control" accept=".json" required>

                <div class="json-help">
                    Este importador usa el contrato EDIFICAR KD IMPORT JSON V1.
                    Por ahora genera vista previa tecnica sin afectar el flujo XML/TXT actual.
                </div>

                <div class="json-actions">
                    <button type="submit" class="btn btn-primary">Procesar vista previa JSON</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection