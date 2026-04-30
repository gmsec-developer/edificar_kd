@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Gestion de roles</h1>
        <p class="text-sm text-gray-500">Administra los roles del sistema.</p>
    </div>

    <a href="{{ route('roles.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
        Nuevo rol
    </a>
</div>

@if (session('success'))
    <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guard</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            {{ $role->name }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $role->guard_name }}
                        </td>

                        <td class="px-4 py-3 text-sm text-right">
                            <div class="flex justify-end gap-2">
                                @if ($role->name !== 'superadmin')
                                    <a href="{{ route('roles.edit', $role) }}"
                                       class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md text-xs hover:bg-yellow-200">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                          onsubmit="return confirm('Confirmas eliminar este rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-xs hover:bg-red-200">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded-md text-xs">
                                        Protegido
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                            No existen roles registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $roles->links() }}
    </div>
</div>
@endsection