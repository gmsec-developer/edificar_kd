@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Configuración del sistema</h1>

<form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="bg-white shadow rounded-lg p-6 space-y-6">
        @foreach ($settings as $setting)
            <div>
                <label class="block font-medium text-sm text-gray-700">
                    {{ $setting->label }}
                </label>

                <p class="text-xs text-gray-500 mb-2">
                    {{ $setting->description }}
                </p>

                @if ($setting->type === 'boolean')
                    <select name="settings[{{ $setting->key }}]" class="border rounded p-2 w-full">
                        <option value="true" @selected($setting->value == 'true')>Activo</option>
                        <option value="false" @selected($setting->value == 'false')>Inactivo</option>
                    </select>

                @elseif ($setting->type === 'image')
                    @if ($setting->value)
                        <img src="{{ asset('storage/' . $setting->value) }}"
                             style="height:60px; max-height:60px; object-fit:contain; margin-bottom:10px;">
                    @endif

                    <input type="file"
                           name="settings[{{ $setting->key }}]"
                           accept="image/*"
                           class="border rounded p-2 w-full">

                @else
                    <input type="text"
                           name="settings[{{ $setting->key }}]"
                           value="{{ $setting->value }}"
                           class="border rounded p-2 w-full">
                @endif
            </div>
        @endforeach

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Guardar cambios
        </button>
    </div>
</form>
@endsection