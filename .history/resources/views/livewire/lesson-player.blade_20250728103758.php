<div x-data="{ isOpen: @entangle('isOpen') }"
     x-show="isOpen"
     class="fixed inset-0 z-50 overflow-y-auto"
     x-cloak>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
         x-on:click="isOpen = false"></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-6xl max-h-[90vh] flex flex-col"
             x-on:click.away="isOpen = false"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <!-- Header -->
            <div class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold">{{ $lesson->title ?? 'Ders İzleyici' }}</h3>
                <button x-on:click="isOpen = false" class="text-white hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body - Two Column Layout -->
            <div class="flex flex-1 overflow-hidden">
                <!-- Left Column - Video Player (70%) -->
                <div class="w-full lg:w-7/12 bg-black flex flex-col">
                    @if($lesson)
                        <div class="flex-1 flex items-center justify-center">
                            @if($videoType === 'youtube')
                                <iframe class="w-full aspect-video"
                                        src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            @elseif($videoType === 'vimeo')
                                <iframe class="w-full aspect-video"
                                        src="https://player.vimeo.com/video/{{ $videoId }}?autoplay=1"
                                        frameborder="0"
                                        allow="autoplay; fullscreen"
                                        allowfullscreen></iframe>
                            @else
                                <div class="text-white p-4 text-center">
                                    <p>Bu video türü desteklenmiyor</p>
                                </div>
                            @endif
                        </div>

                        <!-- Video Controls/Info -->
                        <div class="bg-gray-900 text-white p-4">
                            <h4 class="font-bold text-lg">{{ $lesson->title }}</h4>
                            <p class="text-gray-300 text-sm mt-1">{{ $lesson->description }}</p>
                            <div class="mt-3 flex justify-between items-center">
                                <div class="flex space-x-3">
                                    <button class="p-2 rounded-full hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                    </button>
                                    <button class="p-2 rounded-full hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                    </button>
                                </div>
                                <button wire:click="markAsCompleted"
                                        class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 rounded text-sm">
                                    Tamamlandı olarak işaretle
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center text-white">
                            <p>Ders seçilmedi</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Curriculum (30%) -->
                <div class="hidden lg:block w-5/12 bg-white border-l border-gray-200 overflow-y-auto">
                    <div class="p-4 sticky top-0 bg-white border-b border-gray-200 z-10">
                        <h4 class="font-bold text-gray-800">Kurs Müfredatı</h4>
                        <div class="mt-1 flex items-center text-sm text-gray-500">
                            <span>{{ $course->completed_lessons_count }}/{{ $course->lessons_count }} tamamlandı</span>
                            <span class="mx-2">•</span>
                            <span>{{ $course->progress_percentage }}%</span>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach($course->lessons as $index => $courseLesson)
                            <div class="p-4 hover:bg-gray-50 cursor-pointer transition-colors
                                    {{ $lesson && $lesson->id === $courseLesson->id ? 'bg-indigo-50' : '' }}"
                                 wire:click="loadLesson({{ $courseLesson->id }})">
                                <div class="flex items-start">
                                    <div class="mr-3 pt-0.5">
                                        @if(auth()->user()->hasCompletedLesson($courseLesson))
                                            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center text-gray-400">
                                                {{ $index + 1 }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="font-medium text-gray-900">{{ $courseLesson->title }}</h5>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <span>{{ $courseLesson->duration }} dakika</span>
                                            @if($courseLesson->is_free)
                                                <span class="ml-2 px-1.5 py-0.5 text-xs bg-green-100 text-green-800 rounded">Ücretsiz</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($lesson && $lesson->id === $courseLesson->id)
                                        <svg class="w-5 h-5 text-indigo-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
