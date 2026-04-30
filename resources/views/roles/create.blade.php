@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Nuevo rol</h1>

<form method="POST" action="{{ route('roles.store') }}" class="bg-white p-6 rounded shadow max-w-xl">
    @csrf

    <label class="block mb-2 text-sm font-medium">Nombre del rol</label>
    <input type="text" name="name" class="w-full rounded border-gray-300 mb-4" required>

    <button class="px-4 py-2 bg-indigo-600 text-white rounded">
        Guardar
    </button>

    <a href="{{ route('roles.index') }}" class="ml-2 px-4 py-2 bg-gray-100 rounded">
        Cancelar
    </a>
</form>
@endsection