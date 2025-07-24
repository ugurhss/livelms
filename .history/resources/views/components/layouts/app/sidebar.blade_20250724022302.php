<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Alpine JS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .active-menu-item {
            background-color: #eef2ff;
            color: #4f46e5;
        }
    </style>
</head>
<body class="min-h-screen bg-white" x-data="{ sidebarOpen: false, profileMenuOpen: false }">
    <!-- Mobile Header -->
    <header class="lg:hidden fixed top-0 left-0 right-0 z-30 bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between px-4 py-3">
            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-bars text-xl"></i>
            </button>

            {{-- <a href="{{ route('dashboard') }}" class="flex items-center">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <i class="fas fa-bolt text-white"></i>
                </div>
            </a> --}}

            <div class="relative">
                <button @click="profileMenuOpen = !profileMenuOpen" class="flex items-center space-x-1">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                        {{ auth()->user()->initials() }}
                    </div>
                </button>

                <div x-show="profileMenuOpen" @click.away="profileMenuOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                    <div class="px-4 py-2 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i> {{ __('Settings') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-40 w-64 sidebar-transition transform -translate-x-full lg:translate-x-0 bg-gradient-to-b from-white to-gray-50 border-r border-gray-200 shadow-xl"
         :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <i class="fas fa-bolt text-white"></i>
                </div>
                <span class="text-xl font-bold text-gray-800">Lara<span class="text-indigo-600">Kit</span></span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- User Profile -->
        <div class="p-4 flex items-center space-x-3 border-b border-gray-200">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                    {{ auth()->user()->initials() }}
                </div>
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-2 space-y-1 overflow-y-auto h-[calc(100vh-180px)]">
            <!-- Dashboard Link -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Platform
            </div>
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                {{ request()->routeIs('dashboard') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-home mr-3"></i>
                {{ __('Dashboard') }}
            </a>

            <!-- Role-based Navigation -->
            @if(auth()->user()->isAdmin())
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                    Admin Tools
                </div>
                {{-- <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.*') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-shield-alt mr-3"></i>
                    {{ __('Admin Dashboard') }}
                </a> --}}

                <!-- Admin Course Management -->
                {{-- <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.index') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-book mr-3"></i>
                    {{ __('Manage Courses') }}
                </a> --}}

                <!-- User Management -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-users mr-3"></i>
                    {{ __('User Management') }}
                </a>

                <!-- System Settings -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cogs mr-3"></i>
                    {{ __('System Settings') }}
                </a>
            @endif

            @if(auth()->user()->isInstructor())
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                    Instructor Tools
                </div>
                <a href="{{ route('instructor.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('instructor.*') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-chalkboard-teacher mr-3"></i>
                    {{ __('Instructor Dashboard') }}
                </a>

                <!-- My Courses -->
                {{-- <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.index') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-book-open mr-3"></i>
                    {{ __('My Courses') }}
                </a> --}}

                <!-- Create Course -->
                {{-- <a href="{{ route('courses.create') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.create') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-plus-circle mr-3"></i>
                    {{ __('Create Course') }}
                </a> --}}

                <!-- Quiz Management -->
                {{-- <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-question-circle mr-3"></i>
                    {{ __('Quiz Management') }}
                </a> --}}
            @endif

            @if(auth()->user()->isStudent())
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                    Student Tools
                </div>
                <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('student.*') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    {{ __('Student Dashboard') }}
                </a>

                <!-- My Enrollments -->
                {{-- <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.index') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-list-ul mr-3"></i>
                    {{ __('My Enrollments') }}
                </a> --}}

                <!-- Course Catalog -->
                {{-- <a href="{{ route('courses.public.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.public.index') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-search mr-3"></i>
                    {{ __('Course Catalog') }}
                </a> --}}

                <!-- Quiz Results -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-poll mr-3"></i>
                    {{ __('Quiz Results') }}
                </a>
            @endif

            <!-- Shared Resources -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                Resources
            </div>
            <a href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                <i class="fab fa-github mr-3"></i>
                {{ __('Repository') }}
            </a>
            <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-book-open mr-3"></i>
                {{ __('Documentation') }}
            </a>
        </nav>

        <!-- Bottom Menu -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
            <a href="{{ route('settings.profile') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-cog mr-3"></i>
                {{ __('Settings') }}
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden" x-cloak></div>

    <!-- Main Content -->
    <main class="lg:ml-64 pt-16 lg:pt-0">
        {{ $slot }}
    </main>

    <script>
        // Auto-close flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const flashes = document.querySelectorAll('[class*="fixed bottom-4 right-4"]');
                flashes.forEach(flash => flash.remove());
            }, 5000);
        });
    </script>
</body>
</html>
