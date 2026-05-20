@extends('layouts.app')

@section('content')
<style>
    .kd-page { padding: 18px; background: #f4f6f9; }
    .kd-card { background: #fff; border: 1px solid #d9dee7; border-radius: 10px; padding: 16px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(15,23,42,.05); }
    .kd-title { font-weight: 800; font-size: 18px; margin-bottom: 12px; color: #111827; }
    .kd-subtitle { color: #6b7280; font-size: 13px; margin-bottom: 14px; }
    .kd-row { display: grid; grid-template-columns: 180px 1fr; gap: 12px; align-items: center; margin-bottom: 14px; }
    .kd-label { font-weight: 700; font-size: 14px; color: #374151; }
    .kd-help { font-size: 12px; color: #6b7280; margin-top: 3px; }
    .kd-input { border: 1px solid #d1d5db; border-radius: 8px; padding: 8px; width: 100%; background: #f9fafb; }
    .kd-actions { margin-top: 16px; display: flex; justify-content: flex-end; gap: 10px; }
    .kd-btn-primary { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; }
    .kd-btn-primary:hover { background: #1d4ed8; }
    .kd-btn-secondary { background:#f3f4f6; color:#374151; padding:10px 14px; border-radius:8px; font-weight:700; text-decoration:none; display:inline-block; }
    .kd-alert-error { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:14px; font-weight:700; }
</style>

<div class="kd-page">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Importaciones KD</h1>
        <p class="text-sm text-gray-500">Carga y previsualiza archivos KitchenDraw antes de guardar el proyecto.</p>
    </div>

    @if($errors->any())
        <div class="kd-alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="kd-card">
        <div class="kd-title">Cargar proyecto KitchenDraw</div>
        <div class="kd-subtitle">Carga el XML, TXT de despiece y opcionalmente el SCN original como respaldo.</div>

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="kd-row">
                <div class="kd-label">Archivo XML</div>
                <div>
                    <input type="file" name="xml_file" class="kd-input" accept=".xml" required>
                    <div class="kd-help">Cabecera, ambientes, modulos y precios.</div>
                </div>
            </div>

            <div class="kd-row">
                <div class="kd-label">Archivo TXT de despiece</div>
                <div>
                    <input type="file" name="txt_file" class="kd-input" accept=".txt" required>
                    <div class="kd-help">Piezas, materiales, medidas y relacion con modulos.</div>
                </div>
            </div>

            <div class="kd-row">
                <div class="kd-label">Archivo SCN original</div>
                <div>
                    <input type="file" name="scn_file" class="kd-input" accept=".scn">
                    <div class="kd-help">Opcional: respaldo del archivo original KitchenDraw.</div>
                </div>
            </div>

            <div class="kd-actions">
                <a href="{{ route('projects.index') }}" class="kd-btn-secondary">Volver a proyectos</a>
                <button type="submit" class="kd-btn-primary">Procesar archivos</button>
            </div>
        </form>
    </div>

</div>
@endsection