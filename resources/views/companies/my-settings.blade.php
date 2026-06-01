@extends('layouts.app')

@section('content')
@php
    $settings = $company->settings ?? [];

    $defaultProfit = old('default_profit_percent', $settings['default_profit_percent'] ?? 35);
    $defaultWaste = old('default_waste_percent', $settings['default_waste_percent'] ?? 10);
    $taxPercent = old('tax_percent', $settings['tax_percent'] ?? 15);
    $currency = old('default_currency', $settings['default_currency'] ?? 'USD');
    $requireValidation = old('require_validation_before_save', $settings['require_validation_before_save'] ?? true);
    $allowReplace = old('allow_project_replace', $settings['allow_project_replace'] ?? true);
@endphp

<div class="space-y-4">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Mi empresa</h1>
        <p class="mt-1 text-sm text-gray-500">
            Consulta los datos de tu empresa y administra parametros operativos de EDIFICAR.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 pt-4 pb-2">
            <h2 class="text-lg font-bold text-gray-900">Datos de empresa</h2>
            <p class="mt-1 text-sm text-gray-500">
                Informacion administrada por el Superadmin. Solo lectura para administradores de empresa.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 px-5 pb-5 pt-4 lg:grid-cols-3">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:col-span-2">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Nombre</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->name }}</div>
                </div>

                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">RUC</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->ruc ?? 'No definido' }}</div>
                </div>

                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Codigo interno</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->code ?? 'No definido' }}</div>
                </div>

                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Codigo corto</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->short_code ?? 'No definido' }}</div>
                </div>

                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Email</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->email ?? 'No definido' }}</div>
                </div>

                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Telefono</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->phone ?? 'No definido' }}</div>
                </div>

                <div class="sm:col-span-2">
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Direccion</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->address ?? 'No definido' }}</div>
                </div>

                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Estado</div>
                    <div class="mt-1">
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $company->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $company->status === 'active' ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Maximo usuarios</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $company->max_users ?? 'No definido' }}</div>
                </div>
            </div>

            <div class="flex items-center justify-center rounded-xl border border-gray-100 bg-gray-50 p-6">
                @if ($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}"
                         class="max-h-48 max-w-full object-contain">
                @else
                    <div class="text-sm text-gray-500">Logo no definido</div>
                @endif
            </div>
        </div>
    </div>

    <form method="POST"
          action="{{ route('company-settings.update') }}"
          class="rounded-xl border border-gray-100 bg-white shadow-sm">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 px-5 pt-4 pb-2">
            <h2 class="text-lg font-bold text-gray-900">Parametros EDIFICAR</h2>
            <p class="mt-1 text-sm text-gray-500">
                Estos parametros aplican solo a proyectos, validaciones y calculos de tu empresa.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 px-5 pb-5 pt-4 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">% utilidad por defecto</label>
                <input type="number" step="0.01" min="0" max="100" name="default_profit_percent" value="{{ $defaultProfit }}"
                       class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">% desperdicio por defecto</label>
                <input type="number" step="0.01" min="0" max="100" name="default_waste_percent" value="{{ $defaultWaste }}"
                       class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">IVA %</label>
                <input type="number" step="0.01" min="0" max="100" name="tax_percent" value="{{ $taxPercent }}"
                       class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Moneda por defecto</label>
                <select name="default_currency"
                        class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="USD" @selected($currency === 'USD')>USD</option>
                    <option value="EUR" @selected($currency === 'EUR')>EUR</option>
                </select>
            </div>

            <div class="lg:col-span-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
                <label class="flex items-start gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <input type="checkbox" name="require_validation_before_save" value="1"
                           class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           @checked($requireValidation)>
                    <span>
                        <span class="block text-sm font-semibold text-gray-900">Requiere validacion antes de guardar</span>
                        <span class="block text-xs text-gray-500">Evita guardar proyectos sin pasar por revision previa.</span>
                    </span>
                </label>

                <label class="flex items-start gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <input type="checkbox" name="allow_project_replace" value="1"
                           class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           @checked($allowReplace)>
                    <span>
                        <span class="block text-sm font-semibold text-gray-900">Permitir reemplazo de proyecto</span>
                        <span class="block text-xs text-gray-500">Habilita reemplazar informacion de un proyecto existente.</span>
                    </span>
                </label>
            </div>
        </div>

        <div class="flex justify-end border-t border-gray-100 px-6 py-5">
            <button type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                Guardar parametros
            </button>
        </div>
    </form>
</div>
@endsection
