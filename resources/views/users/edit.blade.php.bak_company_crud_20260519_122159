@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Editar Usuario</h1>

    <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
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
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto de usuario</label>
            @if ($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full object-cover mb-3">
            @endif
            <input type="file" name="avatar" accept="image/*"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Actualizar
        </button>
    </form>
@endsection