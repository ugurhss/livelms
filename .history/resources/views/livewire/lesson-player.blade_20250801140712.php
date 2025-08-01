<div>
    @if($showModal)
        <!-- Modal yapısı -->
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ open: true }" x-show="open" @keydown.window.escape="open = false">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-900 opacity-75" @click="open = false; $wire.closeModal()"></div>

            <!-- Modal içeriği -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                <!-- Kapatma butonu -->
                <button @click="open = false; $wire.closeModal()" class="absolute top-4 right-4 z-10">
                    <svg class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Video container (önemli kısım) -->
                <div class="video-container" wire:key="video-{{ $lesson->id }}">
                    @if($videoType === 'youtube' && $videoId)
                        <iframe
                            class="w-full h-[500px]"
                            src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&enablejsapi=1&autoplay=1"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            id="ytplayer-{{ $lesson->id }}"></iframe>
                    @endif
                </div>

                        <!-- Lesson content and curriculum -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Lesson description -->
                            <div class="lg:col-span-2">
                                <h4 class="font-semibold text-gray-900 mb-3 text-lg">Ders Açıklaması</h4>
                                <div class="prose max-w-none text-gray-700">
                                    {{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}
                                </div>
                            </div>

                            <!-- Course curriculum -->
                            <div class="lg:col-span-1">
                                <h4 class="font-semibold text-gray-900 mb-3 text-lg">Kurs Müfredatı</h4>
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    @foreach($course->lessons as $index => $courseLesson)
                                        <div class="border-b border-gray-200 last:border-b-0">
                                            <div class="p-3 hover:bg-gray-50 flex items-center justify-between @if($lesson->id === $courseLesson->id) bg-indigo-50 @endif">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="@if($lesson->id === $courseLesson->id) font-medium text-indigo-600 @endif">
                                                        {{ $index + 1 }}. {{ $courseLesson->title }}
                                                    </span>
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $courseLesson->duration_minutes }} dakika</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
