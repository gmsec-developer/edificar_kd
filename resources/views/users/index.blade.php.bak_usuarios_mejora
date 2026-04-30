@extends('layouts.app')

@section('content')

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('users.create') }}" class="bg-green-600 text-white px-3 py-1 rounded">
        Nuevo Usuario
    </a>

    <h1 class="text-2xl font-bold mb-4 mt-4">Usuarios</h1>

    <table class="w-full bg-white shadow rounded">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 text-left">ID</th>
                <th class="p-2 text-left">Nombre</th>
                <th class="p-2 text-left">Email</th>
                <th class="p-2 text-center">Estado</th>
                <th class="p-2 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-t">
                    <td class="p-2">{{ $user->id }}</td>
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2 text-center">
                        @if($user->is_active)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">Activo</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm">Inactivo</span>
                        @endif
                    </td>

                    <td class="p-2 text-center">
                        <div class="flex justify-center items-center gap-2">

                            <a href="{{ route('users.edit', $user->id) }}"
                               class="bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center justify-center h-8">
                                Editar
                            </a>

                            @if(!$user->hasRole('superadmin'))
                                <form method="POST" action="{{ route('users.toggle', $user->id) }}" class="m-0 p-0">
                                    @csrf
                                    <button type="submit"
                                        class="{{ $user->is_active ? 'bg-yellow-500' : 'bg-green-600' }} text-white px-3 py-1 rounded text-sm flex items-center justify-center h-8">
                                        {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('users.reset', $user->id) }}" class="m-0 p-0">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('¿Resetear contraseña?')"
                                        class="bg-purple-600 text-white px-3 py-1 rounded text-sm flex items-center justify-center h-8">
                                        Reset
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Â¿Seguro que deseas eliminar este usuario?')"
                                        class="bg-red-600 text-white px-3 py-1 rounded text-sm flex items-center justify-center h-8">
                                        Eliminar
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

@endsection