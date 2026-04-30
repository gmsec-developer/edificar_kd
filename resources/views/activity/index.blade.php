@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Historial del sistema</h1>

<div class="bg-white shadow rounded-lg p-6">

    <form method="GET" class="mb-4 flex gap-2 flex-wrap">
        <select name="user" class="border p-2 rounded">
            <option value="">Todos los usuarios</option>
            @foreach ($users as $id => $name)
                <option value="{{ $id }}" @selected(request('user') == $id)>
                    {{ $name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="from" value="{{ request('from') }}" class="border p-2 rounded">
        <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded">

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Filtrar
        </button>
    </form>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs text-gray-500">Usuario</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500">Acción</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500">Fecha</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse ($logs as $log)
                <tr>
                    <td class="px-4 py-3 text-sm">
                        {{ $log->causer->name ?? 'Sistema' }}
                    </td>

                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-800">
                            {{ $log->description }}
                        </div>

                        @php
                            $props = $log->properties instanceof \Illuminate\Support\Collection
                                ? $log->properties->toArray()
                                : (array) $log->properties;

                            $old = $props['old'] ?? [];
                            $new = $props['new'] ?? [];

                            $labels = [
                                'name' => 'Nombre',
                                'email' => 'Correo',
                                'is_active' => 'Estado',
                                'avatar' => 'Foto',
                                'role' => 'Rol',
                            ];

                            $formatValue = function ($field, $value) {
                                if ($field === 'is_active') {
                                    return $value ? 'Activo' : 'Inactivo';
                                }

                                if ($value === null || $value === '') {
                                    return 'Vacío';
                                }

                                return $value;
                            };
                        @endphp

                        @if (!empty($old) && !empty($new))
                            <div class="mt-2 text-xs bg-gray-50 border rounded p-3 space-y-2">
                                @foreach ($new as $field => $newValue)
                                    @php
                                        $oldValue = $old[$field] ?? null;
                                    @endphp

                                    @if ($oldValue != $newValue)
                                        <div class="flex flex-wrap gap-1 items-center">
                                            <span class="font-semibold text-gray-700">
                                                {{ $labels[$field] ?? $field }}:
                                            </span>

                                            <span class="px-2 py-1 rounded bg-red-50 text-red-700">
                                                {{ $formatValue($field, $oldValue) }}
                                            </span>

                                            <span class="text-gray-400">→</span>

                                            <span class="px-2 py-1 rounded bg-green-50 text-green-700">
                                                {{ $formatValue($field, $newValue) }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-sm whitespace-nowrap">
                        {{ $log->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center p-4">
                        Sin registros
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection