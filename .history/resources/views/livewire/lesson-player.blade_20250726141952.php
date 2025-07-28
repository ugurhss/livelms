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

    <!-- Debug Bilgileri (Sadece local ortamda) -->
    @if(app()->environment('local'))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Debug Bilgileri</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($debugInfo as $key => $value)
                            <li><strong>{{ $key }}:</strong> {{ is_bool($value) ? ($value ? 'true' : 'false') : $value }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

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
            <!-- Sol Taraf - Ders İçerikleri -->
            <div class="w-80 border-r border-gray-200 bg-gray-50 flex flex-col">
                <!-- ... Müfredat listesi aynı ... -->
            </div>

            <!-- Sağ Taraf - Ana İçerik -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Video Player Alanı -->
                <div class="flex-1 bg-black flex items-center justify-center relative">
                    @if($currentLesson && $currentLesson->video_url && $currentLesson->hasValidVideo)
                        @if($currentLesson->isYoutubeVideo())
                            <iframe
                                src="https://www.youtube.com/embed/{{ $currentLesson->youtubeId() }}?autoplay=1"
                                class="w-full h-full"
                                frameborder="0"
                                allowfullscreen>
                            </iframe>
                        @else
                            <video controls class="w-full h-full">
                                <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                Tarayıcınız video oynatmayı desteklemiyor.
                            </video>
                        @endif
                    @else
                        <div class="text-center p-8 text-white">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-xl font-medium mb-2">Video Bulunamadı</h3>
                            <p class="text-gray-300">
                                @if(empty($currentLesson->video_url))
                                    Video URL'si boş
                                @elseif(!$currentLesson->hasValidVideo)
                                    Geçersiz video formatı: {{ Str::limit($currentLesson->video_url, 50) }}
                                @else
                                    Bilinmeyen hata
                                @endif
                            </p>
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
                            <span>•</span>
                            <span>
                                @if($currentLesson->video_url)
                                    @if($currentLesson->isYoutubeVideo())
                                        YouTube Video
                                    @else
                                        Direkt Video
                                    @endif
                                @else
                                    Video Yok
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigasyon ve Kapatma Butonu -->
        <div class="border-t border-gray-200 bg-gray-50 p-4 flex justify-between items-center">
            <!-- ... Navigasyon butonları aynı ... -->
        </div>
    </div>
</div>
