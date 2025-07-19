<div class="container mx-auto px-4 py-6">
    <!-- Başlık -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Hoş Geldiniz, {{ Auth::user()->name }}</h1>
        <p class="text-gray-600">Öğrenme yolculuğunuzun özeti</p>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Kayıtlı Kurslar -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Kayıtlı Kurslar</h3>
            <p class="text-2xl font-bold text-indigo-600">{{ $istatistikler['enrolled_courses'] ?? 0 }}</p>
        </div>

        <!-- Tamamlanan Kurslar -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Tamamlanan Kurslar</h3>
            <p class="text-2xl font-bold text-indigo-600">{{ $istatistikler['completed_courses'] ?? 0 }}</p>
        </div>

        <!-- Devam Eden Kurslar -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Devam Eden Kurslar</h3>
            <p class="text-2xl font-bold text-indigo-600">{{ $istatistikler['in_progress_courses'] ?? 0 }}</p>
        </div>

        <!-- Tamamlama Oranı -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Tamamlama Oranı</h3>
            <p class="text-2xl font-bold text-indigo-600">%{{ number_format($istatistikler['completion_rate'] ?? 0, 1) }}</p>
        </div>
    </div>

    <!-- Kurslarım Bölümü -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Kurslarım</h2>
            {{-- <a href="{{ route('kurslarim') }}" class="text-indigo-600 hover:text-indigo-800">Tümünü Gör</a> --}}
        </div>

        @if(count($kurslar) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($kurslar as $kurs)
                    <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <div class="h-40 bg-gray-200 overflow-hidden">
                            <img src="{{ $kurs['thumbnail'] }}" alt="{{ $kurs['title'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">{{ $kurs['title'] }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $kurs['instructor'] }}</p>

                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $kurs['progress'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>İlerleme: {{ $kurs['progress'] }}%</span>
                                <span>{{ $kurs['completed'] ? 'Tamamlandı' : 'Devam Ediyor' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>Henüz kayıtlı olduğunuz bir kurs bulunmamaktadır.</p>
                <a href="{{ route('kurslar') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Kurslara Göz Atın</a>
            </div>
        @endif
    </div>

    <!-- Diğer bölümler (ödevler, etkinlikler vb.) aynı şekilde kalabilir -->
    <!-- ... -->
</div>
