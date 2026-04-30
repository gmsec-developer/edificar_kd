@extends('layouts.app')

@section('content')
@php
    $totalUsers = \App\Models\User::count();
    $activeUsers = \App\Models\User::where('is_active', true)->count();
    $inactiveUsers = \App\Models\User::where('is_active', false)->count();
    $rolesCount = \Spatie\Permission\Models\Role::count();
    $permissionsCount = \Spatie\Permission\Models\Permission::count();
    $logs = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->take(6)->get();

    $activePercent = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0;
    $inactivePercent = $totalUsers > 0 ? round(($inactiveUsers / $totalUsers) * 100) : 0;
@endphp

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500">Vista general del sistema EDIFICAR.</p>
    </div>

    <div class="text-sm text-gray-500">
        {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500">Usuarios</p>
        <div class="flex items-center justify-between mt-2">
            <h2 class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</h2>
            <span class="text-3xl">👥</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500">Activos</p>
        <div class="flex items-center justify-between mt-2">
            <h2 class="text-3xl font-bold text-green-700">{{ $activeUsers }}</h2>
            <span class="text-3xl">✅</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500">Roles</p>
        <div class="flex items-center justify-between mt-2">
            <h2 class="text-3xl font-bold text-indigo-700">{{ $rolesCount }}</h2>
            <span class="text-3xl">🛡️</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500">Permisos</p>
        <div class="flex items-center justify-between mt-2">
            <h2 class="text-3xl font-bold text-purple-700">{{ $permissionsCount }}</h2>
            <span class="text-3xl">🔐</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Estado de usuarios</h3>

        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Activos</span>
                    <span>{{ $activePercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $activePercent }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Inactivos</span>
                    <span>{{ $inactivePercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-red-500 h-3 rounded-full" style="width: {{ $inactivePercent }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actividad reciente</h3>

        <div class="space-y-3">
            @forelse ($logs as $log)
                <div class="flex items-start gap-3 border-b pb-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center">
                        📋
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">{{ $log->description }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $log->causer->name ?? 'Sistema' }} · {{ $log->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Sin actividad registrada.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    @can('users.view')
        <a href="{{ route('users.index') }}" class="bg-white rounded-xl shadow p-5 hover:shadow-md transition">
            <div class="text-3xl mb-2">👥</div>
            <h3 class="font-semibold text-gray-800">Gestionar usuarios</h3>
            <p class="text-sm text-gray-500">Crear, editar, activar y asignar roles.</p>
        </a>
    @endcan

    @can('roles.view')
        <a href="{{ route('roles.index') }}" class="bg-white rounded-xl shadow p-5 hover:shadow-md transition">
            <div class="text-3xl mb-2">🛡️</div>
            <h3 class="font-semibold text-gray-800">Gestionar roles</h3>
            <p class="text-sm text-gray-500">Administrar roles y permisos.</p>
        </a>
    @endcan

    @can('settings.view')
        <a href="{{ route('activity.index') }}" class="bg-white rounded-xl shadow p-5 hover:shadow-md transition">
            <div class="text-3xl mb-2">📋</div>
            <h3 class="font-semibold text-gray-800">Ver auditoría</h3>
            <p class="text-sm text-gray-500">Consultar historial de actividad.</p>
        </a>
    @endcan
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Usuarios activos vs inactivos</h3>
        <canvas id="usersStatusChart" height="140"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribución general</h3>
        <canvas id="systemSummaryChart" height="140"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const activeUsers = {{ $activeUsers }};
    const inactiveUsers = {{ $inactiveUsers }};
    const rolesCount = {{ $rolesCount }};
    const permissionsCount = {{ $permissionsCount }};

    new Chart(document.getElementById('usersStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Activos', 'Inactivos'],
            datasets: [{
                data: [activeUsers, inactiveUsers],
                backgroundColor: ['#22c55e', '#ef4444']
            }]
        }
    });

    new Chart(document.getElementById('systemSummaryChart'), {
        type: 'bar',
        data: {
            labels: ['Usuarios', 'Roles', 'Permisos'],
            datasets: [{
                label: 'Totales',
                data: [activeUsers + inactiveUsers, rolesCount, permissionsCount],
                backgroundColor: ['#6366f1', '#0ea5e9', '#a855f7']
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>


@endsection