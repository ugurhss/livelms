<!-- Lesson Player Modal -->
<div x-data="{ isOpen: false }" x-init="
    $wire.on('loadLesson', () => {
        isOpen = true;
        document.body.classList.add('overflow-hidden');
    });
    $watch('isOpen', value => {
        if (!value) document.body.classList.remove('overflow-hidden');
    })"
    class="lesson-player-container">

    <!-- Modal Overlay -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4"
         @keydown.window.escape="isOpen = false">

        <!-- Modal Content -->
        <div @click.away="isOpen = false"
             class="bg-white rounded-xl shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col"
             x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <!-- Modal Header -->
            <div class="border-b p-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Ders İçeriği</h3>
                <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 flex overflow-hidden">
                <!-- Left Sidebar - Curriculum -->
                <div class="w-80 border-r overflow-y-auto bg-gray-50">
                    <div class="p-4">
                        <h4 class="font-medium text-gray-900 mb-3">{{ $course->title }}</h4>
                        <div class="space-y-2">
                            @foreach($course->lessons as $index => $courseLesson)
                                <div class="p-3 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors
                                    {{ $lesson && $lesson->id === $courseLesson->id ? 'bg-indigo-50 border border-indigo-100' : '' }}"
                                    wire:click="$emit('loadLesson', {{ $courseLesson->id }})">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center mr-3">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $courseLesson->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $courseLesson->duration_minutes }} dakika</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Content - Lesson -->
                <div class="flex-1 overflow-y-auto p-6">
                    @if($lesson)
                        <div class="lesson-header mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                            <p class="text-gray-600 mt-1">{{ $lesson->duration_minutes }} dakika</p>
                        </div>

                        <div class="video-container mb-6 rounded-lg overflow-hidden shadow">
                            @if($videoType === 'youtube' && $videoId)
                                <div class="aspect-w-16 aspect-h-9">
                                    <iframe class="w-full h-full min-h-[400px]"
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
                                <div class="bg-gray-100 h-96 flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-3 text-gray-600">Bu ders için video bulunamadı</p>
                                </div>
                            @endif
                        </div>

                        <div class="lesson-content bg-gray-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Ders Açıklaması</h4>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($lesson->description ?: 'Açıklama bulunmamaktadır.')) !!}
                            </div>
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
            </div>

            <!-- Modal Footer -->
            <div class="border-t p-4 flex justify-end">
                <button @click="isOpen = false" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                    Kapat
                </button>
            </div>
        </div>
    </div>
</div>
