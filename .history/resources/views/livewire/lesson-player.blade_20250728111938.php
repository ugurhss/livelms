<div class="lesson-player-container bg-white rounded-xl shadow-sm p-6 mb-6">
    @if($lesson)
        <div class="lesson-header mb-4 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
            </div>
            <button onclick="openLessonModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Dersi Aç
            </button>
        </div>

        <!-- Modal Structure -->
        <div id="lessonModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white max-w-6xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                    <button onclick="closeLessonModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Left Side - Curriculum and Description -->
                    <div class="w-full md:w-1/2">
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-2">Müfredat</h4>
                            <!-- Curriculum content would go here -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">Müfredat içeriği burada gösterilecek</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Ders Açıklaması</h4>
                            <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
                        </div>
                    </div>

                    <!-- Right Side - Video -->
                    <div class="w-full md:w-1/2">
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
                            <div class="bg-gray-100 rounded-lg p-8 text-center h-96 flex items-center justify-center">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-3 text-gray-600">Bu ders için video bulunamadı</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Original content (hidden when modal is open) -->
        <div id="originalContent">
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

<script>
    function openLessonModal() {
        document.getElementById('lessonModal').classList.remove('hidden');
        document.getElementById('originalContent').classList.add('hidden');
    }

    function closeLessonModal() {
        document.getElementById('lessonModal').classList.add('hidden');
        document.getElementById('originalContent').classList.remove('hidden');
    }
</script>
