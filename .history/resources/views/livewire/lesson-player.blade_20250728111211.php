<div class="lesson-player-container bg-white rounded-xl shadow-sm p-6 mb-6">
    @if($lesson)
        <div class="lesson-header mb-4">
            <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
            <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
        </div>

        <div class="video-container mb-4">
            @if($videoType === 'youtube' && $videoId)
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <iframe class="w-full h-96"
                            src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            @elseif($lesson->embed_code)
                <div class="aspect-w-16 aspect-h-9">
                    {!! $lesson->embed_code !!}
                </div>
            @else
                <div class="bg-gray-100 rounded-lg p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-3 text-gray-600">Bu ders için video bulunamadı</p>
                </div>
            @endif
        </div>

        <div class="lesson-content">
            <h4 class="font-semibold text-gray-900 mb-2">Ders Açıklaması</h4>
            <p class="text-gray-700">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-3 text-gray-600">Gösterilecek ders bulunamadı</p>
        </div>
    @endif
</div>
