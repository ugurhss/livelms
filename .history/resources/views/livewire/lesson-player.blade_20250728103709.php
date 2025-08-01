<div>
    @if($lesson)
        <div class="mb-4">
            <h3 class="text-xl font-bold">{{ $lesson->title }}</h3>
            <p class="text-gray-600">{{ $lesson->description }}</p>
        </div>

        @if($videoType === 'youtube')
            <iframe class="w-full aspect-video"
                    src="https://www.youtube.com/embed/{{ $videoId }}"
                    frameborder="0"
                    allowfullscreen></iframe>
        @elseif($videoType === 'vimeo')
            <iframe class="w-full aspect-video"
                    src="https://player.vimeo.com/video/{{ $videoId }}"
                    frameborder="0"
                    allowfullscreen></iframe>
        @else
            <div class="bg-gray-100 p-4 rounded">
                Video player bu video türünü desteklemiyor.
            </div>
        @endif

        <div class="mt-4 flex justify-between">
            @if($previousLesson = $this->getPreviousLesson())
                <button wire:click="loadLesson({{ $previousLesson->id }})"
                        class="px-4 py-2 bg-gray-200 rounded">
                    Önceki Ders
                </button>
            @endif

            @if($nextLesson = $this->getNextLesson())
                <button wire:click="loadLesson({{ $nextLesson->id }})"
                        class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Sonraki Ders
                </button>
            @endif
        </div>
    @else
        <div class="bg-gray-100 p-4 rounded">
            Lütfen bir ders seçin.
        </div>
    @endif
</div>
