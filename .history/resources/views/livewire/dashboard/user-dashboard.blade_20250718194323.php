</x-layouts.app>
<div class="space-y-6">
    <!-- Hoşgeldin Mesajı -->
    <div class="bg-white rounded-lg shadow p-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hoş Geldin, {{ $user->name }}!</h1>
            <p class="text-gray-600">{{ $welcomeMessages[$user->role] ?? 'Sistem paneline hoş geldiniz' }}</p>
        </div>
        <button wire:click="refreshData" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg wire:loading wire:target="refreshData" class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Yenile
        </button>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($stats as $key => $value)
            <x-dashboard.stat-card
                :title="$statTitles[$key] ?? ucfirst(str_replace('_', ' ', $key))"
                :value="$value"
                :icon="$statIcons[$key] ?? 'default'"
                :color="$statColors[$key] ?? 'gray'" />
        @endforeach
    </div>

    <!-- Rol Özel İçerik -->
    @if(view()->exists("livewire.dashboard.{$user->role}-dashboard-content"))
        @include("livewire.dashboard.{$user->role}-dashboard-content")
    @endif

    <!-- Ortak İçerikler -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Yaklaşan Son Tarihler -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Yaklaşan Son Tarihler</h3>
                    <span class="text-sm text-gray-500">{{ count($upcomingDeadlines) }} aktif</span>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($upcomingDeadlines as $deadline)
                        <div class="p-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-md bg-{{ $deadline['type'] === 'quiz' ? 'purple' : 'indigo' }}-500 text-white">
                                        @if($deadline['type'] === 'quiz')
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        @else
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-md font-medium text-gray-900">{{ $deadline['title'] }}</h4>
                                    <p class="mt-1 text-sm text-gray-500">{{ $deadline['course'] ?? 'Genel' }}</p>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($deadline['due_date'])->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Yaklaşan son tarih bulunmamaktadır
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Son Aktivite -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Son Aktivite</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentActivities as $activity)
                        <div class="p-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-800">{{ $activity['message'] }}</p>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Henüz aktivite kaydı bulunmamaktadır
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Rol Özel Yan İçerik -->
        @if(view()->exists("livewire.dashboard.{$user->role}-dashboard-sidebar"))
            <div class="lg:col-span-1">
                @include("livewire.dashboard.{$user->role}-dashboard-sidebar")
            </div>
        @endif
    </div>
</div>

@php
    // Statik veriler (gerçek projede config veya servisten alınmalı)
    $welcomeMessages = [
        'student' => 'Öğrenme yolculuğunu takip et',
        'instructor' => 'Kurs istatistiklerini görüntüle',
        'admin' => 'Sistem yönetim paneline hoş geldiniz'
    ];

    $statTitles = [
        'enrolled_courses' => 'Kayıtlı Kurslar',
        'completed_courses' => 'Tamamlananlar',
        'in_progress_courses' => 'Devam Edenler',
        'completion_rate' => 'Tamamlama Oranı',
        'total_courses' => 'Toplam Kurs',
        'total_students' => 'Toplam Öğrenci',
        'average_rating' => 'Ortalama Puan',
        'total_earnings' => 'Toplam Kazanç'
    ];

    $statIcons = [
        'enrolled_courses' => 'bookmark',
        'completed_courses' => 'check-circle',
        'in_progress_courses' => 'clock',
        'completion_rate' => 'percent',
        'total_courses' => 'academic-cap',
        'total_students' => 'users',
        'average_rating' => 'star',
        'total_earnings' => 'currency-dollar'
    ];

    $statColors = [
        'enrolled_courses' => 'blue',
        'completed_courses' => 'green',
        'in_progress_courses' => 'yellow',
        'completion_rate' => 'purple',
        'total_courses' => 'indigo',
        'total_students' => 'green',
        'average_rating' => 'yellow',
        'total_earnings' => 'purple'
    ];
@endphp

@push('styles')
<style>
    .animate-bounce {
        animation: bounce 1s infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
</style>
@endpush
</x-layouts.app>
