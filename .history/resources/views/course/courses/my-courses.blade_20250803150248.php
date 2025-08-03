<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Başlık ve Filtreleme -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kayıtlı Kurslarım</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Öğrenme yolculuğunuzu takip edin</p>
            </div>

            @if($courses->isNotEmpty())
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-sm font-medium">
                        Toplam {{ $courses->total() }} kurs
                    </div>
                    <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Boş Kurs Durumu -->
        @if($courses->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center max-w-md mx-auto">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Henüz kursa kayıtlı değilsiniz</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Becerilerinizi geliştirmek için yeni kurslar keşfedin</p>
                <div class="mt-6">
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Kursları Keşfet
                    </a>
                </div>
            </div>
        @else
            <!-- Kurs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <!-- Kurs Resmi -->
                        <div class="relative">
                            <img src="{{ $course->thumbnail ?? asset('images/default-course.jpg') }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $course->level === 'beginner' ? 'bg-green-100 text-green-800' :
                                       ($course->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $course->level === 'beginner' ? 'Başlangıç' :
                                      ($course->level === 'intermediate' ? 'Orta Seviye' : 'İleri Seviye') }}
                                </span>
                            </div>
                        </div>

                        <!-- Kurs Detayları -->
                        <div class="p-5">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h3>
                                <button class="text-gray-400 hover:text-indigo-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                </button>
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">{{ $course->description }}</p>

                            <!-- İlerleme Çubuğu -->
                            @php
                                $enrollment = $course->enrollments->firstWhere('user_id', auth()->id());
                                $progress = $enrollment->progress ?? 0;
                            @endphp
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-500 dark:text-gray-400">İlerleme</span>
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full"
                                         style="width: {{ $progress }}%"
                                         x-data="{ progress: 0 }"
                                         x-init="setTimeout(() => progress = {{ $progress }}, 100)"></div>
                                </div>
                            </div>

                            <!-- Kurs İstatistikleri -->
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mb-5">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $course->lessons_count }} Ders
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $course->students_count }} Öğrenci
                                </div>
                            </div>

                            <!-- Aksiyon Butonları -->
                            <div class="flex space-x-2">
                                <a href="{{ route('courses.learn', $course->id) }}" class="flex-1 flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Devam Et
                                </a>
                                <a href="{{ route('courses.show', $course->id) }}" class="p-2 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sayfalama -->
            @if($courses->hasPages())
                <div class="mt-8">
                    {{ $courses->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @endif
    </div>

    <!-- Alpine.js Animasyonları -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // İlerleme çubuklarına animasyon ekle
            const progressBars = document.querySelectorAll('[x-data]');
            progressBars.forEach(bar => {
                bar.style.transition = 'width 1s ease-in-out';
            });
        });
    </script>
</x-layouts.app>
