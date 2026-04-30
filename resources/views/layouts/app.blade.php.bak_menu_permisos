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
            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-800">Usuarios</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-800">Roles</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-800">Configuración</a>
        </nav>
    </aside>

    <main class="flex-1">
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <span class="font-semibold text-gray-700">Panel Administrativo</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-red-600 hover:underline">
                    Cerrar sesión
                </button>
            </form>
        </header>

        <section class="p-6">
            @yield('content')
        </section>
    </main>

</div>

</body>
</html>