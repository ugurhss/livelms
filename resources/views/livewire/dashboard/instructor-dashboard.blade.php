<x-layouts.app>

<div class="space-y-6">
    <!-- Başlık -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800">Eğitmen Paneli</h1>
        <p class="text-gray-600">Kurs performansınızı ve istatistiklerinizi görüntüleyin</p>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Toplam Kurs -->
        <div class="bg-indigo-50 rounded-lg shadow p-4 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-700">Toplam Kurs</p>
                    {{-- <p class="text-2xl font-bold text-gray-800">{{ $stats['total_courses'] }}</p> --}}
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Toplam Öğrenci -->
        <div class="bg-green-50 rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700">Toplam Öğrenci</p>
                    {{-- <p class="text-2xl font-bold text-gray-800">{{ $stats['total_students'] }}</p> --}}
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ortalama Puan -->
        <div class="bg-yellow-50 rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-700">Ortalama Puan</p>
                    {{-- <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['average_rating'], 1) }}/5</p> --}}
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Toplam Kazanç -->
        <div class="bg-purple-50 rounded-lg shadow p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-700">Toplam Kazanç</p>
                    {{-- <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_earnings'], 2) }}</p> --}}
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- İki Sütunlu Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sol Taraf (Geniş) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Aylık Kazanç Grafiği -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aylık Kazanç</h3>
                </div>
                <div class="p-6">
                    <canvas id="earningsChart" height="300"></canvas>
                </div>
            </div>

            <!-- Kurslarım -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Kurslarım</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($courses as $course)
                    <div class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-16 w-16 rounded-md bg-gray-200 overflow-hidden">
                                <img class="h-full w-full object-cover" src="{{ $course['thumbnail'] ?? asset('images/default-course.png') }}" alt="{{ $course['title'] }}">
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $course['title'] }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $course['status'] === 'published' ? 'Yayında' : 'Taslak' }}
                                    </span>
                                </div>
                                <div class="mt-2 grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Öğrenci</p>
                                        <p class="text-lg font-medium">{{ $course['students_count'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Puan</p>
                                        <p class="text-lg font-medium">{{ number_format($course['rating'], 1) }}/5</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Kazanç</p>
                                        <p class="text-lg font-medium">${{ number_format($course['price'] * $course['students_count'] * 0.7, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sağ Taraf (Dar) -->
        <div class="space-y-6">
            <!-- Son Kayıtlar -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Son Kayıtlar</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentEnrollments as $enrollment)
                    <div class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 overflow-hidden">
                                <img class="h-full w-full object-cover" src="{{ $enrollment['user']['avatar'] ?? asset('images/default-avatar.png') }}" alt="{{ $enrollment['user']['name'] }}">
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-medium text-gray-900">{{ $enrollment['user']['name'] }}</h4>
                                <p class="mt-1 text-sm text-gray-500">{{ $enrollment['course']['title'] }}</p>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($enrollment['created_at'])->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Son Aktivite -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Son Aktivite</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($activities as $activity)
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
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
{{-- @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function() {
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($earningsData['labels']),
                datasets: [{
                    label: 'Kazanç ($)',
                    data: @json($earningsData['data']),
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush --}}
