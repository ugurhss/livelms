<div>
<div class="lesson-player-container">
    @if($lesson)
        <div class="lesson-header mb-4">
            <h3 class="text-xl font-bold">{{ $lesson->title }}</h3>
            <p class="text-gray-600">{{ $lesson->duration_minutes }} dakika</p>
        </div>

        <div class="video-container mb-4">
            @if($videoType === 'youtube' && $videoId)
                <div class="aspect-w-16 aspect-h-9">
                    <iframe class="w-full h-96"
                            src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            @elseif($lesson->embed_code)
                {!! $lesson->embed_code !!}
            @else
                <div class="alert alert-warning">Bu ders için video bulunamadı.</div>
            @endif
        </div>

        <div class="lesson-content">
            <h4 class="font-semibold mb-2">Ders Açıklaması</h4>
            <p class="text-gray-700">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
        </div>
    @endif
</div></div>
