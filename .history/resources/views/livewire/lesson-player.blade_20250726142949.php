<!-- Video Player Alanı -->
<div>

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
                @if(!$currentLesson)
                    Ders bilgisi yüklenemedi
                @elseif(empty($currentLesson->video_url))
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
        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $currentLesson->title ?? 'Ders başlığı bulunamadı' }}</h2>

        <div class="prose max-w-none text-gray-700">
            {!! nl2br(e($currentLesson->description ?? 'Ders açıklaması bulunmamaktadır.')) !!}
        </div>

        <div class="mt-6 flex items-center space-x-4 text-sm text-gray-500">
            @if($currentLesson)
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
            @endif
        </div>
    </div>
</div>

</div>
