<div x-data="{ open: @entangle('showModal') }">
    <!-- Ders Listesi -->
    <div class="space-y-3 mb-8">
        @foreach($lessons as $lesson)
            <button
                wire:click="openModal({{ $lesson->id }})"
                @click="open = true"
                class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-xs"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $loop->iteration }}. {{ $lesson->title }}</h3>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $lesson->duration_minutes }} dakika
                            </span>
                            @if($lesson->is_free)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                    Ücretsiz
                                </span>
                            @endif
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </button>
        @endforeach
    </div>

    <!-- Tam Ekran Modal -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-[9999] bg-white flex flex-col">

        <div class="flex flex-1 overflow-hidden">
            <!-- Sol Taraf - Ders İçerikleri (Müfredat) -->
            <div class="w-80 border-r border-gray-200 bg-gray-50 flex flex-col">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Kurs Müfredatı</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $lessons->count() }} ders</p>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <div class="space-y-1 p-2">
                        @foreach($lessons as $lesson)
                            <button
                                wire:click="openModal({{ $lesson->id }})"
                                class="w-full text-left p-3 rounded-lg transition-colors flex items-start {{ $currentLesson && $currentLesson->id === $lesson->id ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-100' }}"
                            >
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-3 mt-0.5 text-sm">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <p class="font-medium">{{ $lesson->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $lesson->duration_minutes }} dakika</p>
                                </div>
                                @if($currentLesson && $currentLesson->id === $lesson->id)
                                    <svg class="w-5 h-5 ml-auto text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf - Ana İçerik -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Video Player Alanı -->
                <div class="flex-1 bg-black flex items-center justify-center relative">
                    @if($currentLesson && $currentLesson->video_url)
                        @php
                            // YouTube ID'sini çıkarma
                            $isYouTube = str_contains($currentLesson->video_url, 'youtube.com') || str_contains($currentLesson->video_url, 'youtu.be');
                            $videoId = $isYouTube ? $currentLesson->extractYouTubeId($currentLesson->video_url) : null;
                        @endphp

                        @if($isYouTube && $videoId)
                            <iframe
                                src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&modestbranding=1&rel=0"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        @elseif(preg_match('/\.(mp4|webm|ogg)$/i', $currentLesson->video_url))
                            <video controls autoplay class="w-full h-full object-contain">
                                <source src="{{ $currentLesson->video_url }}" type="video/{{ pathinfo($currentLesson->video_url, PATHINFO_EXTENSION) }}">
                                Tarayıcınız video oynatmayı desteklemiyor.
                            </video>
                        @else
                            <div class="text-center p-8 text-white">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="text-xl font-medium mb-2">Desteklenmeyen Video Formatı</h3>
                                <p class="text-gray-300">URL: {{ Str::limit($currentLesson->video_url, 50) }}</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center p-8 text-white">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-xl font-medium mb-2">Video Bulunamadı</h3>
                            <p class="text-gray-300">Bu dersin videosu henüz eklenmemiş</p>
                        </div>
                    @endif
                </div>

                <!-- Ders Detayları -->
                <div class="border-t border-gray-200 bg-white">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $currentLesson->title ?? '' }}</h2>

                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($currentLesson->description ?? 'Ders açıklaması bulunmamaktadır.')) !!}
                        </div>

                        <div class="mt-6 flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $currentLesson->duration_minutes ?? 0 }} dakika
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigasyon ve Kapatma Butonu -->
        <div class="border-t border-gray-200 bg-gray-50 p-4 flex justify-between items-center">
            <div class="flex space-x-3">
                <button
                    wire:click="openModal({{ $currentLesson ? $currentLesson->id - 1 : 0 }})"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if(!$currentLesson || $currentLesson->order === 1) disabled @endif
                >
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Önceki
                </button>

                <button
                    wire:click="openModal({{ $currentLesson ? $currentLesson->id + 1 : 0 }})"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if(!$currentLesson || $currentLesson->order === $lessons->count()) disabled @endif
                >
                    Sonraki
                    <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <button @click="open = false"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Dersi Kapat
            </button>
        </div>
    </div>
</div>
