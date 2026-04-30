@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Editar Usuario</h1>

    <form method="POST" action="{{ route('users.update', $user->id) }}" class="bg-white p-4 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Nombre</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label>Rol</label>
            <select name="role" class="w-full border p-2 rounded">
                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Actualizar
        </button>
    </form>
@endsection