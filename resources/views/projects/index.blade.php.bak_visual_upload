@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Proyectos KitchenDraw</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <strong>Cargar proyecto</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Archivo XML</label>
                    <input type="file" name="xml_file" class="form-control" required>
                    <small class="text-muted">Cabecera, ambientes, módulos y precios.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Archivo TXT de despiece</label>
                    <input type="file" name="txt_file" class="form-control" required>
                    <small class="text-muted">Piezas, materiales, medidas y relación con módulos.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Archivo SCN original</label>
                    <input type="file" name="scn_file" class="form-control">
                    <small class="text-muted">Opcional: respaldo del archivo original KitchenDraw.</small>
                </div>

                <button class="btn btn-primary">Procesar archivos</button>
            </form>
        </div>
    </div>

    <h4>Proyectos guardados</h4>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->status }}</td>
                    <td>{{ $project->created_at }}</td>
                    <td>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                            Ver
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted">No hay proyectos guardados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
