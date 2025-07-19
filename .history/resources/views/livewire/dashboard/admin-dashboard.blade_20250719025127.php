<div>
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Başlık ve Filtreleme -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Yönetici Paneli</h1>
            <p class="text-gray-600">Sistem genelinde istatistikleri ve yönetim araçlarını görüntüleyin</p>
        </div>
        <div class="flex items-center space-x-2">
            <select class="bg-white border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Son 7 Gün</option>
                <option selected>Son 30 Gün</option>
                <option>Bu Yıl</option>
            </select>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Rapor Al
            </button>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Toplam Kullanıcı -->
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Toplam Kullanıcı</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">3</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            %12 artış
                        </span>
                        <span class="text-xs text-gray-500 ml-2">Geçen aya göre</span>
                    </div>
                </div>
                <div class="bg-blue-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Toplam Kurs -->
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Toplam Kurs</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCourses ?? 0}}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            %8 artış
                        </span>
                        <span class="text-xs text-gray-500 ml-2">Geçen aya göre</span>
                    </div>
                </div>
                <div class="bg-green-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Aktif Kayıtlar -->
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Aktif Kayıtlar</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $activeEnrollments ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            %15 artış
                        </span>
                        <span class="text-xs text-gray-500 ml-2">Geçen aya göre</span>
                    </div>
                </div>
                <div class="bg-yellow-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Aylık Gelir -->
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Aylık Gelir</p>
                    {{-- <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($monthlyRevenue, 2) }}</p> --}}
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            %22 artış
                        </span>
                        <span class="text-xs text-gray-500 ml-2">Geçen aya göre</span>
                    </div>
                </div>
                <div class="bg-purple-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik ve Tablolar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sol Taraf (Geniş) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Kayıt Trendleri Grafiği -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Kayıt Trendleri</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-md">Aylık</button>
                        <button class="px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-md">Haftalık</button>
                        <button class="px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-md">Yıllık</button>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="enrollmentChart" height="250"></canvas>
                </div>
            </div>

            <!-- Son Kullanıcılar Tablosu -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Son Kayıt Olan Kullanıcılar</h3>
                    {{-- <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-800">Tümünü Görüntüle</a> --}}
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">İşlemler</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- @foreach($recentUsers as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $user['avatar'] ?? asset('images/default-avatar.png') }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user['name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $user['email'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' :
                                           ($user['role'] === 'instructor' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $user['role'] === 'admin' ? 'Yönetici' :
                                           ($user['role'] === 'instructor' ? 'Eğitmen' : 'Öğrenci') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user['joined_at'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Düzenle</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Sil</a>
                                </td>
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf (Dar) -->
        <div class="space-y-6">
            <!-- Sistem Durumu -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Sistem Durumu</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Sunucu Yükü -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Sunucu Yükü</span>
                                <span class="text-sm font-medium text-gray-700">%32</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 32%"></div>
                            </div>
                        </div>

                        <!-- Depolama Alanı -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Depolama Alanı</span>
                                <span class="text-sm font-medium text-gray-700">%78</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">45.8 GB / 58.6 GB kullanılıyor</p>
                        </div>

                        <!-- Veritabanı -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Veritabanı</span>
                                <span class="text-sm font-medium text-gray-700">%56</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 56%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Sistem Bilgileri -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">PHP Versiyon</p>
                                <p class="font-medium">8.1.12</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Laravel</p>
                                <p class="font-medium">v9.43.0</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Sunucu</p>
                                <p class="font-medium">nginx/1.22.0</p>
                            </div>
                            <div>
                                <p class="text-gray-500">MySQL</p>
                                <p class="font-medium">8.0.31</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hızlı İşlemler -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Hızlı İşlemler</h3>
                </div>
                <div class="p-6 grid grid-cols-2 gap-3">
                    <a href="#" class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex flex-col items-center">
                        <div class="bg-blue-100 p-3 rounded-full mb-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-center">Kullanıcı Ekle</span>
                    </a>
                    <a href="#" class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex flex-col items-center">
                        <div class="bg-green-100 p-3 rounded-full mb-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-center">Kurs Oluştur</span>
                    </a>
                    <a href="#" class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex flex-col items-center">
                        <div class="bg-purple-100 p-3 rounded-full mb-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-center">İçerik Düzenle</span>
                    </a>
                    <a href="#" class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex flex-col items-center">
                        <div class="bg-yellow-100 p-3 rounded-full mb-2">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-center">Ayarlar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kayıt Trendleri Grafiği
        const ctx = document.getElementById('enrollmentChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json(array_column($enrollmentTrends, 'month')),
                datasets: [{
                    label: 'Kayıt Sayısı',
                    data: @json(array_column($enrollmentTrends, 'enrollments')),
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                }
            }
        });
    });
</script> --}}
@endpush
