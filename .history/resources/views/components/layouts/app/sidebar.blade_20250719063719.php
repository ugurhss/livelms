<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white flex">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-zinc-50 border-r border-zinc-200 dark:bg-zinc-900 dark:border-zinc-700 flex flex-col">
        <!-- Close button (visible only on mobile) -->
        <button id="sidebar-close-btn" class="lg:hidden self-end p-4 text-zinc-700 dark:text-zinc-300" aria-label="Close sidebar">
            <!-- X icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-4 space-x-2 rtl:space-x-reverse" >
            <x-app-logo />
            <span class="font-semibold text-lg text-zinc-900 dark:text-zinc-100">Dashboard</span>
        </a>

        <!-- Navigation Groups -->
        <nav class="flex-1 overflow-y-auto px-4">
            <div class="mt-6 mb-3 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wide text-xs">Platform</div>

            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center px-3 py-2 rounded-md hover:bg-zinc-200 dark:hover:bg-zinc-700 {{ request()->routeIs('dashboard') ? 'bg-zinc-300 dark:bg-zinc-800 font-semibold' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                        </svg>
                        {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <div class="mt-10 mb-3 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wide text-xs">Resources</div>

            <ul class="space-y-1">
                <li>
                    <a href="https://github.com/laravel/livewire-starter-kit" target="_blank"
                       class="flex items-center px-3 py-2 rounded-md hover:bg-zinc-200 dark:hover:bg-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 0C5.37 0 0 5.37 0 12a12 12 0 008.21 11.44c.6.11.82-.26.82-.58 0-.29-.01-1.04-.02-2.04-3.34.73-4.04-1.61-4.04-1.61-.55-1.39-1.34-1.76-1.34-1.76-1.1-.75.08-.74.08-.74 1.22.09 1.87 1.25 1.87 1.25 1.08 1.85 2.84 1.32 3.53 1.01.11-.79.42-1.32.76-1.62-2.67-.3-5.47-1.33-5.47-5.91 0-1.3.47-2.36 1.24-3.19-.13-.3-.54-1.52.12-3.17 0 0 1.01-.32 3.3 1.23a11.5 11.5 0 013.01-.4 11.5 11.5 0 013.01.4c2.28-1.55 3.3-1.23 3.3-1.23.66 1.65.25 2.87.12 3.17.77.83 1.24 1.9 1.24 3.19 0 4.59-2.81 5.61-5.48 5.9.43.38.82 1.14.82 2.3 0 1.66-.02 3-.02 3.41 0 .32.22.69.82.58A12 12 0 0024 12c0-6.63-5.37-12-12-12z" />
                        </svg>
                        {{ __('Repository') }}
                    </a>
                </li>

                <li>
                    <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank"
                       class="flex items-center px-3 py-2 rounded-md hover:bg-zinc-200 dark:hover:bg-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 10l-7 7-7-7" />
                        </svg>
                        {{ __('Documentation') }}
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto px-4 py-6 border-t border-zinc-200 dark:border-zinc-700">
            <!-- User Info -->
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-neutral-200 dark:bg-neutral-700 text-black dark:text-white font-semibold">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="font-semibold truncate">{{ auth()->user()->name }}</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</span>
                </div>
            </div>

            <!-- Settings and Logout -->
            <div class="mt-4 space-y-2">
                <a href="{{ route('settings.profile') }}" class="block text-sm text-zinc-700 dark:text-zinc-300 hover:underline">
                    {{ __('Settings') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-sm text-red-600 hover:underline w-full text-left">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Page Content -->
    <main class="flex-1 ml-0 lg:ml-64 p-6">
        {{ $slot }}
    </main>

    <script>
        // Mobile sidebar toggle logic
        const sidebar = document.getElementById('sidebar');
        const closeBtn = document.getElementById('sidebar-close-btn');

        // Hide sidebar by default on mobile
        if(window.innerWidth < 1024){
            sidebar.classList.add('-translate-x-full', 'fixed', 'z-50', 'transition-transform', 'duration-200', 'ease-in-out');
        }

        // Close button toggles sidebar on mobile
        closeBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>

</body>
</html>
