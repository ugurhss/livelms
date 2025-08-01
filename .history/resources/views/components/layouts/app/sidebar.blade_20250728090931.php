<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Alpine JS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }

    .prose {
        max-width: 100%;
        line-height: 1.6;
    }

    .prose p {
        margin-bottom: 1rem;
    }

    /* Modal için özel stiller */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
    }

    .modal-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        background: white;
    }
        [x-cloak] { display: none !important; }
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .active-menu-item {
            background-color: #eef2ff;
            color: #4f46e5;
        }

               [x-cloak] { display: none !important; }
    .prose {
        max-width: 100%;
        line-height: 1.6;
    }
    .prose p {
        margin-bottom: 1rem;
    }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


</head>
<body class="min-h-screen bg-white" x-data="{ sidebarOpen: false, profileMenuOpen: false }">
    <!-- Mobile Header -->
    <header class="lg:hidden fixed top-0 left-0 right-0 z-30 bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between px-4 py-3">
            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-bars text-xl"></i>
            </button>

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
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Çıkış Yap
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
            <span class="text-xl font-bold text-gray-800">Eğitim Platformu</span>
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
                Genel
            </div>

            <a href="/" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                {{ request()->is('/') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-home mr-3"></i>
                Ana Sayfa
            </a>

            <!-- Role-based Navigation -->
            @if(auth()->user()->isAdmin())
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                    Yönetici Araçları
                </div>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.*') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Yönetici Paneli
                </a>

                <!-- Course Management -->
                <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.index') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-book mr-3"></i>
                    Kurs Yönetimi
                </a>

                <!-- User Management -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-users-cog mr-3"></i>
                    Kullanıcı Yönetimi
                </a>
            @endif

            @if(auth()->user()->isInstructor())
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                    Eğitmen Araçları
                </div>

                <a href="{{ route('instructor.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('instructor.*') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-chalkboard-teacher mr-3"></i>
                    Eğitmen Paneli
                </a>

                <!-- My Courses -->
                <a href="{{ route('instructor.courses') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('instructor.courses') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-book-open mr-3"></i>
                    Kurslarım
                </a>

                <!-- Create Course -->
                <a href="{{ route('courses.create') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.create') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Yeni Kurs Ekle
                </a>

                <!-- Quiz Management -->
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-2">
                    Sınav Yönetimi
                </div>

                <a href="{{ route('quizzes.create') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('quizzes.create') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-plus mr-3"></i>
                    Yeni Sınav Oluştur
                </a>

                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-list mr-3"></i>
                    Sınavlarım
                </a>
            @endif

            @if(auth()->user()->isStudent())
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                    Öğrenci Araçları
                </div>

                <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('student.*') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    Öğrenci Paneli
                </a>

                <!-- My Enrollments -->
                <a href="{{ route('my-courses') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('my-courses') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-list-ul mr-3"></i>
                    Kayıtlı Kurslarım
                </a>

                <!-- Course Catalog -->
                <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ request()->routeIs('courses.index') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-search mr-3"></i>
                    Kurs Kataloğu
                </a>

                <!-- Quiz Results -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-poll mr-3"></i>
                    Sınav Sonuçlarım
                </a>
            @endif

            <!-- Shared Resources -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">
                Ortak Menüler
            </div>

            <!-- Courses -->
            <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                {{ request()->routeIs('courses.index') ? 'active-menu-item' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-book mr-3"></i>
                Tüm Kurslar
            </a>

            <!-- Profile -->
            <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-user mr-3"></i>
                Profilim
            </a>

            <!-- Settings -->
            <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-cog mr-3"></i>
                Ayarlar
            </a>
        </nav>

        <!-- Bottom Menu -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Çıkış Yap
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


    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</html>
