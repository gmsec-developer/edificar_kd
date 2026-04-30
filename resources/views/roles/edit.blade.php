@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Editar rol</h1>

<form method="POST" action="{{ route('roles.update', $role) }}" class="bg-white p-6 rounded shadow max-w-2xl">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-2 text-sm font-medium">Nombre del rol</label>
        <input type="text" name="name" value="{{ old('name', $role->name) }}"
               class="w-full rounded border-gray-300 mb-4" required>
    </div>

    <div class="mb-4">
        <label class="block mb-2 text-sm font-medium">Permisos</label>

        <div class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto border p-3 rounded">
            @foreach ($permissions as $permission)
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $permission->name }}"
                           {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                    <span>{{ $permission->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex gap-2 mt-4">
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">
            Actualizar
        </button>

        <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-100 rounded">
            Cancelar
        </a>
    </div>
</form>
@endsection