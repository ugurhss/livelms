<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white flex">

    <!-- Sidebar -->
 <div class="sidebar bg-gradient-to-b from-indigo-900 to-indigo-800 text-white w-64 min-h-screen fixed transition-all duration-300 ease-in-out z-50 shadow-xl">
    <!-- Logo Section -->
    <div class="p-4 border-b border-indigo-700 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span class="text-xl font-bold tracking-wider">Lara<span class="text-indigo-300">Kit</span></span>
        </a>
        <button class="sidebar-toggle lg:hidden text-indigo-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
    </div>

    <!-- User Profile -->
    <div class="p-4 flex items-center space-x-3 border-b border-indigo-700">
        <div class="relative">
            <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}"
                 alt="User"
                 class="w-10 h-10 rounded-full object-cover border-2 border-indigo-400">
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-indigo-800"></span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-indigo-300 truncate">{{ Auth::user()->email }}</p>
        </div>
        <button class="text-indigo-300 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-2 space-y-1">
        @foreach($menuItems as $item)
            @if(isset($item['header']))
                <div class="px-4 py-2 text-xs font-semibold text-indigo-400 uppercase tracking-wider">
                    {{ $item['header'] }}
                </div>
            @else
                <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs($item['active'] ?? '') ? 'bg-indigo-700 text-white shadow-md' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                    <span class="mr-3">
                        {!! $item['icon'] !!}
                    </span>
                    <span>{{ $item['title'] }}</span>
                    @if(isset($item['badge']))
                        <span class="ml-auto bg-indigo-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <!-- Bottom Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-indigo-700">
        <a href="#" class="flex items-center space-x-2 text-indigo-300 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Settings</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="flex items-center space-x-2 text-indigo-300 hover:text-white w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

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
