<div class="lesson-player-container bg-white rounded-xl shadow-sm p-6 mb-6">
    @if($lesson)
        <div class="lesson-header mb-4 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
            </div>
            <button onclick="openLessonModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Tam Ekran Aç
            </button>
        </div>

        <!-- Full-screen Modal -->
        <div id="lessonModal" class="hidden fixed inset-0 bg-white z-50 h-screen w-screen overflow-auto">
            <!-- Header -->
            <div class="sticky top-0 bg-white z-10 shadow-sm p-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                <button onclick="closeLessonModal()" class="text-gray-500 hover:text-gray-700 p-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="sr-only">Kapat</span>
                </button>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row h-[calc(100vh-56px)]">
                <!-- Left Side - Curriculum and Description -->
                <div class="w-full lg:w-1/2 p-6 overflow-y-auto">
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-3 text-lg">Müfredat</h4>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <!-- Sample curriculum items -->
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-5 w-5 text-blue-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-gray-700">Giriş ve Temel Kavramlar</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-5 w-5 text-blue-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-gray-700">Örnek Uygulamalar</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-5 w-5 text-gray-400 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-gray-700">İleri Konular (Bu ders)</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-5 w-5 text-gray-300 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-gray-400">Uygulama Projesi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 text-lg">Ders Açıklaması</h4>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-line">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Video -->
                <div class="w-full lg:w-1/2 bg-gray-900 flex items-center justify-center p-4">
                    @if($videoType === 'youtube' && $videoId)
                        <div class="w-full h-full max-w-4xl mx-auto">
                            <div class="aspect-w-16 aspect-h-9 h-full">
                                <iframe class="w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                    @elseif($lesson->embed_code)
                        <div class="w-full h-full">
                            {!! $lesson->embed_code !!}
                        </div>
                    @else
                        <div class="text-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-3">Bu ders için video bulunamadı</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Original content -->
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
        document.body.style.overflow = 'hidden'; // Prevent scrolling on background
    }

    function closeLessonModal() {
        document.getElementById('lessonModal').classList.add('hidden');
        document.body.style.overflow = ''; // Re-enable scrolling
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLessonModal();
        }
    });
</script>
