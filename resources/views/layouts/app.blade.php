<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
        @php
            $systemName = DB::table('settings')->where('key', 'system_name')->value('value') ?? 'Sistema';
            $systemLogo = DB::table('settings')->where('key', 'system_logo')->value('value');
        @endphp
    <title>{{ $systemName }}</title>    
  
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }"
     x-init="$watch('sidebarOpen', value => localStorage.setItem('sidebarOpen', value))"
     class="flex min-h-screen">

    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
           class="bg-gray-900 text-white p-4 transition-all duration-300">

        <div class="flex items-center justify-between mb-6">

        <div class="mb-6 space-y-4">

        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3 min-w-0">
                @if ($systemLogo)
                    <img src="{{ asset('storage/' . $systemLogo) }}"
                        style="width:36px; height:36px; max-width:36px; max-height:36px; object-fit:contain;">
                @else
                    <div style="width:36px; height:36px;"
                        class="rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ substr($systemName, 0, 1) }}
                    </div>
                @endif

                <span x-show="sidebarOpen" class="font-bold text-white truncate">
                    {{ $systemName }}
                </span>
            </div>

            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded hover:bg-gray-800 text-white">
                ☰
            </button>
        </div>

</div>
        </div>

        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                <span>🏠</span>
                <span x-show="sidebarOpen">Dashboard</span>
            </a>

            @can('users.view')
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                    <span>👥</span>
                    <span x-show="sidebarOpen">Usuarios</span>
                </a>
            @endcan

            @can('roles.view')
                <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                    <span>🛡️</span>
                    <span x-show="sidebarOpen">Roles</span>
                </a>
            @endcan

            @can('settings.view')
                <a href="{{ route('activity.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                    <span>📋</span>
                    <span x-show="sidebarOpen">Auditoria</span>
                </a>
            @endcan

            @can('settings.view')
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                    <span>⚙️</span>
                    <span x-show="sidebarOpen">Configuración</span>
                </a>
            @endcan

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800">
                <span>👤</span>
                <span x-show="sidebarOpen">Mi perfil</span>
            </a>

        </nav>
    </aside>

    <main class="flex-1">
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <span class="font-semibold text-gray-700"> {{ $systemName }} </span>

            <div class="flex items-center gap-4">
                <div x-data="{ notificationsOpen: false }" class="relative">
                    @php
                        $notifications = \Spatie\Activitylog\Models\Activity::where('causer_id', auth()->id())
                            ->latest()
                            ->take(5)
                            ->get();

                        $unreadCount = \Spatie\Activitylog\Models\Activity::where('causer_id', auth()->id())
                            ->where('is_read', false)
                            ->count();
                    @endphp

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="relative text-gray-600 hover:text-gray-900 px-2 py-1">
                            🔔

                            @if ($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="open"
                            @click.outside="open = false"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50">

                            <div class="p-3 border-b flex justify-between items-center">
                                <span class="text-sm font-semibold">Notificaciones</span>

                                <form method="POST" action="{{ route('notifications.read') }}">
                                    @csrf
                                    <button class="text-xs text-indigo-600">Marcar todas</button>
                                </form>
                            </div>

                            <div class="max-h-80 overflow-y-auto">
                                @forelse ($notifications as $n)
                                    <div class="p-3 border-b {{ $n->is_read ? '' : 'bg-indigo-50' }}">
                                        <p class="text-sm">{{ $n->description }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ $n->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="p-4 text-sm text-gray-500">Sin notificaciones</p>
                                @endforelse
                            </div>
                        </div>
                    </div>                
                </div>

<div x-data="{ userMenuOpen: false }" class="relative">
    @php
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1));
    @endphp

    <button @click="userMenuOpen = !userMenuOpen"
            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
        @if ($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}"
                 style="width:40px; height:40px; max-width:40px; max-height:40px; border-radius:9999px; object-fit:cover; border:2px solid #6366f1;">
        @else
            <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                {{ $initials }}
            </div>
        @endif

        <div class="text-left">
            <div class="text-sm font-semibold text-gray-700">{{ $user->name }}</div>
            <div class="text-xs text-gray-400">{{ $user->email }}</div>
        </div>

        <span class="text-gray-400 text-xs">▼</span>
    </button>

                <div x-show="userMenuOpen"
                    @click.outside="userMenuOpen = false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border z-50 overflow-hidden">

                    <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        👤 Mi perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                            🚪 Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>       
            </div>
        </header>

        <section class="p-6">
            @yield('content')
        </section>
    </main>

</div>

</body>
</html>