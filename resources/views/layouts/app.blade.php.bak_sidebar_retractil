<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EDIFICAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <aside class="w-64 bg-gray-900 text-white p-4">
        <h1 class="text-xl font-bold mb-6">EDIFICAR</h1>

        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-800">Dashboard</a>

            @can('users.view')
                <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">Usuarios</a>
            @endcan

            @can('roles.view')
                <a href="{{ route('roles.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">Roles</a>
            @endcan

            @can('settings.view')
                <a href="{{ route('activity.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">Auditoria</a>
            @endcan

            @can('settings.view')
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-800">Configuracion</a>
            @endcan
        </nav>
    </aside>

    <main class="flex-1">
 	<header class="bg-white shadow px-6 py-4 flex justify-between items-center">
    	<span class="font-semibold text-gray-700">Panel Administrativo</span>

    	<div class="flex items-center gap-4">

        	<!-- Usuario -->
        <div class="flex items-center gap-3">
            	@php
               	 $user = auth()->user();
                	$initials = strtoupper(substr($user->name, 0, 1));
            @endphp

            @if ($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}"
     style="width:40px; height:40px; max-width:40px; max-height:40px; border-radius:9999px; object-fit:cover; border:2px solid #6366f1;">
            @else
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                    {{ $initials }}
                </div>
            @endif

            <span class="text-sm font-medium text-gray-700">
                {{ $user->name }}
            </span>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-red-600 hover:underline">
                Cerrar sesión
            </button>
        </form>

    </div>
</header>

        <section class="p-6">
            @yield('content')
        </section>
    </main>

</div>

</body>
</html>