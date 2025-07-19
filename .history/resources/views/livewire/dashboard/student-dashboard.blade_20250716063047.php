<x-layouts.app>
    <div class="space-y-6">
        <!-- Hoşgeldin Mesajı -->
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800">Hoş Geldin, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Öğrenme yolculuğunuzu takip edin</p>
        </div>

        <!-- İstatistik Kartları -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Kayıtlı Kurslar -->
            <div class="bg-blue-50 rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700">Kayıtlı Kurslar</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['enrolled_courses'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tamamlanan Kurslar -->
            <div class="bg-green-50 rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700">Tamamlanan Kurslar</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['completed_courses'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Devam Eden Kurslar -->
            <div class="bg-yellow-50 rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700">Devam Eden Kurslar</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['in_progress_courses'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tamamlama Oranı -->
            <div class="bg-purple-50 rounded-lg shadow p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-700">Tamamlama Oranı</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['completion_rate'] ?? 0 }}%</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- İki Sütunlu Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sol Taraf (Geniş) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Kurslarım -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Kurslarım</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($courses as $course)
                        <div class="p-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-16 w-16 rounded-md bg-gray-200 overflow-hidden">
                                    <img class="h-full w-full object-cover" src="{{ $course['thumbnail'] ?? asset('images/default-course.png') }}" alt="{{ $course['title'] }}">
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $course['title'] }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $course['progress'] }}% Tamamlandı
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ $course['instructor'] }}</p>
                                    <div class="mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $course['progress'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-gray-500">
                            Henüz kayıtlı olduğunuz bir kurs bulunmamaktadır.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Bekleyen Ödevler -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Bekleyen Ödevler</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($pendingAssignments as $assignment)
                        <div class="p-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $assignment['title'] }}</h4>
                                    <p class="mt-1 text-sm text-gray-500">{{ $assignment['course']['title'] }}</p>
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Son teslim: {{ \Carbon\Carbon::parse($assignment['due_date'])->format('d M Y, H:i') }}
                                    </div>
                                </div>
                                <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Teslim Et
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-gray-500">
                            Bekleyen ödev bulunmamaktadır.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf (Dar) -->
            <div class="space-y-6">
                <!-- Yaklaşan Son Tarihler -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Yaklaşan Son Tarihler</h3>
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
                                    <p class="mt-1 text-sm text-gray-500">{{ $deadline['course'] }}</p>
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
                            Yaklaşan son tarih bulunmamaktadır.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Son Aktivite -->
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
                            Son aktivite bulunmamaktadır.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
