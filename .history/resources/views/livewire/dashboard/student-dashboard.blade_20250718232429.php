<div class="container mx-auto px-4 py-6">
    <!-- Başlık -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Hoş Geldiniz, {{ Auth::user()->name }}</h1>
        <p class="text-gray-600">Öğrenme yolculuğunuzun özeti</p>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($istatistikler as $key => $value)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    @if($key == 'enrolled_courses') Kayıtlı Kurslar
                    @elseif($key == 'completed_courses') Tamamlanan Kurslar
                    @elseif($key == 'in_progress_courses') Devam Eden Kurslar
                    @elseif($key == 'completion_rate') Tamamlama Oranı
                    @else {{ $key }}
                    @endif
                </h3>
                <p class="text-2xl font-bold text-indigo-600">
                    @if($key == 'completion_rate') %{{ number_format($value, 1) }}
                    @else {{ $value }}
                    @endif
                </p>
            </div>
        @endforeach
    </div>

    <!-- Kurslarım Bölümü -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Kurslarım</h2>
            <a href="{{ route('kurslarim') }}" class="text-indigo-600 hover:text-indigo-800">Tümünü Gör</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($kurslar as $kurs)
                <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    <div class="h-40 bg-gray-200 overflow-hidden">
                        <img src="{{ $kurs['thumbnail'] ?? '/images/default-course.jpg' }}" alt="{{ $kurs['title'] }}" class="w-full h-full object-cover">
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
    </div>

    <!-- Yaklaşan Teslimler ve Son Etkinlikler -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Yaklaşan Teslimler -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Yaklaşan Teslimler</h2>

            @if(count($yaklasan_teslimler) > 0)
                <div class="space-y-4">
                    @foreach($yaklasan_teslimler as $teslim)
                        <div class="border-l-4 border-indigo-500 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold">{{ $teslim['title'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $teslim['course'] }}</p>
                                </div>
                                <span class="text-sm bg-indigo-100 text-indigo-800 px-2 py-1 rounded">
                                    {{ \Carbon\Carbon::parse($teslim['due_date'])->diffForHumans() }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span class="mr-2">Teslim Tarihi:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($teslim['due_date'])->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Yaklaşan teslimi bulunmuyor.</p>
            @endif
        </div>

        <!-- Son Etkinlikler -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Son Etkinlikler</h2>

            @if(count($son_etkinlikler) > 0)
                <div class="space-y-4">
                    @foreach($son_etkinlikler as $etkinlik)
                        <div class="flex items-start">
                            <div class="mr-3 mt-1">
                                @if($etkinlik['icon'] == 'bookmark')
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-gray-800">{{ $etkinlik['message'] }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($etkinlik['date'])->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Son etkinlik bulunmuyor.</p>
            @endif
        </div>
    </div>

    <!-- Ödevler Bölümü -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Ödevlerim</h2>

        <div class="mb-6">
            <h3 class="font-semibold text-lg mb-3 text-gray-700">Bekleyen Ödevler</h3>
            @if(count($bekleyen_odevler) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($bekleyen_odevler as $odev)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium">{{ $odev['title'] }}</h4>
                                <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Bekliyor</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $odev['course'] }}</p>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Teslim Tarihi: {{ $odev['due_date'] }}</span>
                                <a href="#" class="text-indigo-600 hover:text-indigo-800">Detay</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Bekleyen ödeviniz bulunmuyor.</p>
            @endif
        </div>

        <div>
            <h3 class="font-semibold text-lg mb-3 text-gray-700">Tamamlanan Ödevler</h3>
            @if(count($tamamlanan_odevler) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tamamlanan_odevler as $odev)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium">{{ $odev['title'] }}</h4>
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">Tamamlandı</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $odev['course'] }}</p>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Not: {{ $odev['grade'] ?? 'Değerlendirilmedi' }}</span>
                                <a href="#" class="text-indigo-600 hover:text-indigo-800">Detay</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tamamlanan ödeviniz bulunmuyor.</p>
            @endif
        </div>
    </div>
</div>
