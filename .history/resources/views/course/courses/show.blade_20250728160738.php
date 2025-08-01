<div class="lesson-player-container bg-white rounded-xl shadow-sm p-6 mb-6">
    @if($lesson)
        <div class="lesson-header mb-4">
            <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
            <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
        </div>

        <div class="video-container mb-4 cursor-pointer" onclick="openLessonModal('{{ $videoType }}', '{{ $videoId }}', `{!! $lesson->embed_code !!}`)">
            @if($videoType === 'youtube' && $videoId)
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <div class="w-full h-96 bg-gray-800 relative">
                        <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg"
                             alt="Video thumbnail"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-20 h-20 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @elseif($lesson->embed_code)
                <div class="aspect-w-16 aspect-h-9 bg-gray-800 flex items-center justify-center">
                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
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

        <!-- Modal -->
        <div id="lessonModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal content -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">
                                    {{ $lesson->title }}
                                </h3>
                                <div class="video-container" id="modalVideoContainer">
                                    <!-- Video buraya dinamik olarak yüklenecek -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="closeLessonModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentVideoPlayer = null;

            function openLessonModal(videoType, videoId, embedCode) {
                const modal = document.getElementById('lessonModal');
                const videoContainer = document.getElementById('modalVideoContainer');

                // Önceki videoyu temizle
                videoContainer.innerHTML = '';

                // Yeni videoyu yükle
                if (videoType === 'youtube' && videoId) {
                    videoContainer.innerHTML = `
                        <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                            <iframe class="w-full h-96"
                                    src="https://www.youtube.com/embed/${videoId}?rel=0&autoplay=1"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    `;
                } else if (embedCode) {
                    videoContainer.innerHTML = embedCode;
                }

                // Modalı göster
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');

                // Videoyu oynat
                currentVideoPlayer = videoContainer.querySelector('iframe, video');
            }

            function closeLessonModal() {
                const modal = document.getElementById('lessonModal');
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');

                // Videoyu durdur
                if (currentVideoPlayer) {
                    if (currentVideoPlayer.tagName === 'IFRAME') {
                        // YouTube iframe'i için
                        const src = currentVideoPlayer.src;
                        currentVideoPlayer.src = src.replace('&autoplay=1', '');
                    } else if (currentVideoPlayer.tagName === 'VIDEO') {
                        // HTML5 video elementi için
                        currentVideoPlayer.pause();
                    }
                }
            }

            // Close modal when clicking outside
            document.getElementById('lessonModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeLessonModal();
                }
            });

            // Close modal with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('lessonModal').classList.contains('hidden')) {
                    closeLessonModal();
                }
            });
        </script>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-3 text-gray-600">Gösterilecek ders bulunamadı</p>
        </div>
    @endif
</div>
