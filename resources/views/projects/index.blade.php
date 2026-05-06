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
    .kd-actions { margin-top: 16px; display: flex; justify-content: flex-end; }
    .kd-btn-primary { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; }
    .kd-btn-primary:hover { background: #1d4ed8; }
    .kd-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .kd-table th { background: #eef2f7; padding: 8px; border-bottom: 1px solid #d9dee7; text-align: left; }
    .kd-table td { padding: 8px; border-bottom: 1px solid #edf0f5; }
    .kd-table tr:hover { background: #f8fafc; }
</style>

<div class="kd-page">

    <div class="kd-card">
        <div class="kd-title">Cargar proyecto KitchenDraw</div>
        <div class="kd-subtitle">Carga el XML, TXT de despiece y opcionalmente el SCN original como respaldo.</div>

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="kd-row">
                <div class="kd-label">Archivo XML</div>
                <div>
                    <input type="file" name="xml_file" class="kd-input" required>
                    <div class="kd-help">Cabecera, ambientes, modulos y precios.</div>
                </div>
            </div>

            <div class="kd-row">
                <div class="kd-label">Archivo TXT de despiece</div>
                <div>
                    <input type="file" name="txt_file" class="kd-input" required>
                    <div class="kd-help">Piezas, materiales, medidas y relacion con modulos.</div>
                </div>
            </div>

            <div class="kd-row">
                <div class="kd-label">Archivo SCN original</div>
                <div>
                    <input type="file" name="scn_file" class="kd-input">
                    <div class="kd-help">Opcional: respaldo del archivo original KitchenDraw.</div>
                </div>
            </div>

            <div class="kd-actions">
                <button type="submit" class="kd-btn-primary">Procesar archivos</button>
            </div>
        </form>
    </div>

    <div class="kd-card">
        <div class="kd-title">Proyectos guardados</div>

        <table class="kd-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Cliente</th>
                    <th>Disenador</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>
    <strong>{{ $project->project_name ?? $project->name }}</strong>
</td>

<td>
    {{ $project->client_name ?? '-' }}
</td>

<td>
    {{ $project->designer ?? '-' }}
</td>
                        <td>{{ $project->status }}</td>
                        <td>{{ $project->created_at }}</td>
                        <td>
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted">No hay proyectos guardados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
