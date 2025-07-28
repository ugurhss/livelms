<!-- Video Player Alanı -->
<div>

<div class="flex-1 bg-black flex items-center justify-center relative">
    @if($currentLesson?->video_url)
        @if($currentLesson->isYoutubeVideo())
            <iframe src="https://www.youtube.com/embed/{{ $currentLesson->youtubeId() }}?autoplay=1"
                   class="w-full h-full" frameborder="0" allowfullscreen>
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
                    Ders bilgisi yüklenemedi (currentLesson null)
                @elseif(empty($currentLesson->video_url))
                    Bu dersin videosu bulunmamaktadır
                @else
                    Video yüklenirken bir hata oluştu
                @endif
            </p>
        </div>
    @endif
</div>

</div>
