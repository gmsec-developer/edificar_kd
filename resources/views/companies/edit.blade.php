@extends('layouts.app')

@section('content')
    <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 20px;">
        Editar Empresa
    </h1>

    @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; padding:10px; border-radius:6px; margin-bottom:15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:white; padding:20px; border-radius:10px; max-width:500px;">

        <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom:15px;">
                <label>Nombre</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $company->name) }}"
                       required
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>Codigo</label>
                <input type="text"
                       name="code"
                       value="{{ old('code', $company->code) }}"
                       required
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>Codigo corto</label>
                <input type="text"
                    name="short_code"
                    value="{{ old('short_code', $company->short_code) }}"
                    maxlength="10"
                    required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">

                <div style="font-size:12px; color:#6b7280; margin-top:4px;">
                    Ejemplo: ZUH, MUB, GMS.
                </div>
            </div>

            <div style="margin-bottom:15px;">
                <label>Maximo de usuarios</label>
                <input type="number"
                       name="max_users"
                       value="{{ old('max_users', $company->max_users) }}"
                       min="1"
                       required
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>Logo empresa</label>

                @if ($company->logo)
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('storage/' . $company->logo) }}"
                             style="width:70px; height:70px; object-fit:contain; border-radius:8px; border:1px solid #ddd;">
                    </div>
                @endif

                <input type="file"
                       name="logo"
                       accept="image/*"
                       style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
            </div>

            <div style="margin-top:20px;">
                <button type="submit"
                        style="background:#2563eb; color:white; padding:10px 15px; border-radius:6px;">
                    Actualizar
                </button>

                <a href="{{ route('companies.index') }}" style="margin-left:10px;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection