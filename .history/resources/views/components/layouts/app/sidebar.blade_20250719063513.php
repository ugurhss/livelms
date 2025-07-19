<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Dashboard' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.x/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="min-h-screen bg-white flex flex-col">

    <!-- Header -->
    <header class="bg-gray-100 dark:bg-gray-900 p-4 flex items-center justify-between shadow">
        <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700" aria-label="Toggle sidebar">
            <!-- Hamburger icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <a href="{{ route('dashboard') }}" class="text-lg font-bold">
            <x-app-logo /> <!-- Varsa kendi logo component -->
            LaravelApp
        </a>

        <div class="hidden lg:flex items-center space-x-4">
            <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Logout</button>
            </form>
        </div>
    </header>

    <div class="flex flex-1 min-h-0">

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out overflow-y-auto z-30">
            <nav class="mt-6 px-4">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-300 dark:bg-gray-600 font-bold' : '' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="flex items-center mt-3 px-3 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.37 0 0 5.37 0 12a12 12 0 008.21 11.44c.6.11.82-.26.82-.58 0-.29-.01-1.04-.02-2.04-3.34.73-4.04-1.61-4.04-1.61-.55-1.39-1.34-1.76-1.34-1.76-1.1-.75.08-.74.08-.74 1.22.09 1.87 1.25 1.87 1.25 1.08 1.85 2.84 1.32 3.53 1.01.11-.79.42-1.32.76-1.62-2.67-.3-5.47-1.33-5.47-5.91 0-1.3.47-2.36 1.24-3.19-.13-.3-.54-1.52.12-3.17 0 0 1.01-.32 3.3 1.23a11.5 11.5 0 013.01-.4 11.5 11.5 0 013.01.4c2.28-1.55 3.3-1.23 3.3-1.23.66 1.65.25 2.87.12 3.17.77.83 1.24 1.9 1.24 3.19 0 4.59-2.81 5.61-5.48 5.9.43.38.82 1.14.82 2.3 0 1.66-.02 3-.02 3.41 0 .32.22.69.82.58A12 12 0 0024 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                    Repository
                </a>

                <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="flex items-center mt-3 px-3 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 10l-7 7-7-7" />
                    </svg>
                    Documentation
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-auto ml-0 lg:ml-64">
            {{ $slot }}
        </main>

    </div>

    <script>
        // Sidebar toggle for mobile
        const btn = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');

        btn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>

</body>
</html>
