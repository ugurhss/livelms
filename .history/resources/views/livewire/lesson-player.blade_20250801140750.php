<div>
    <!-- Modal Overlay -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75" @click="closeModal"></div>
                </div>

                <!-- Modal container -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Modal header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                                <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
                            </div>
                            <button @click="closeModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Video player -->
                        <div class="video-container mb-6">
                            @if($videoType === 'youtube' && $videoId)
                                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                                    <iframe class="w-full h-[500px]"
                                            src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&autoplay=1"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                </div>
                            @elseif($lesson->embed_code)
                                <div class="aspect-w-16 aspect-h-9">
                                    {!! $lesson->embed_code !!}
                                </div>
                            @else
                                <div class="bg-gray-100 rounded-lg p-8 text-center h-64 flex items-center justify-center">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-3 text-gray-600">Bu ders için video bulunamadı</p>
                                    </div>
                                </div>
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
