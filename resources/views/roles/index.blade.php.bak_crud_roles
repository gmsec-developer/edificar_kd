@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Roles</h1>

    <table class="w-full bg-white shadow rounded">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 text-left">ID</th>
                <th class="p-2 text-left">Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                <tr class="border-t">
                    <td class="p-2">{{ $role->id }}</td>
                    <td class="p-2">{{ $role->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection