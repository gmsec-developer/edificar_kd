@extends('layouts.app')

@section('content')
    <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 20px;">
        Empresas
    </h1>

    <div style="background:white; padding:20px; border-radius:10px;">
        <a href="{{ route('companies.create') }}"
           style="background:#2563eb; color:white; padding:8px 14px; border-radius:6px;">
            Nueva Empresa
        </a>

        <table style="width:100%; margin-top:20px; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="border:1px solid #ddd; padding:8px;">ID</th>
                    <th style="border:1px solid #ddd; padding:8px;">Nombre</th>
                    <th style="border:1px solid #ddd; padding:8px;">Código</th>
                    <th style="border:1px solid #ddd; padding:8px;">Usuarios Máx</th>
                    <th style="border:1px solid #ddd; padding:8px;">Estado</th>
                    <th style="border:1px solid #ddd; padding:8px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $company)
                    <tr>
                        <td style="border:1px solid #ddd; padding:8px;">{{ $company->id }}</td>
                        <td style="border:1px solid #ddd; padding:8px;">{{ $company->name }}</td>
                        <td style="border:1px solid #ddd; padding:8px;">{{ $company->code }}</td>
                        <td style="border:1px solid #ddd; padding:8px;">{{ $company->max_users }}</td>
                        <td style="border:1px solid #ddd; padding:8px;">{{ $company->status }}</td>
                        <td style="border:1px solid #ddd; padding:8px;">
                            <a href="{{ route('companies.edit', $company) }}">Editar</a>
                            <form action="{{ route('companies.toggle', $company) }}"
                                method="POST"
                                style="display:inline; margin-left:10px;">
                                @csrf
                                <button type="submit" style="color:#ca8a04;">
                                    {{ $company->status === 'active' ? 'Inactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection