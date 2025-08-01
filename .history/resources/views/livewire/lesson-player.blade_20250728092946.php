<div class="fixed inset-0 z-50 hidden" id="lesson-modal">
    <div class="absolute inset-0 bg-black bg-opacity-75" onclick="closeModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl max-w-6xl mx-auto my-8 h-[90vh] overflow-y-auto">
        <!-- Kapatma Butonu -->
        <button onclick="closeModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Ders İçeriği (Mevcut Blade Yapınız) -->
        <div class="p-6">
            <div class="lesson-header mb-4">
                <h3 class="text-xl font-bold text-gray-900" id="modal-lesson-title">{{ $lesson->title ?? '' }}</h3>
                <p class="text-gray-600 text-sm" id="modal-lesson-duration">{{ $lesson->duration_minutes ?? '' }} dakika</p>
            </div>

            <div class="video-container mb-4">
                <div id="modal-video-content">
                    @if($videoType === 'youtube' && $videoId)
                        <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                            <iframe class="w-full h-96"
                                    src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    @elseif($lesson->embed_code ?? false)
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
            </div>

            <div class="lesson-content">
                <h4 class="font-semibold text-gray-900 mb-2">Ders Açıklaması</h4>
                <p class="text-gray-700" id="modal-lesson-description">{{ $lesson->description ?? 'Açıklama bulunmamaktadır.' }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Modalı açma fonksiyonu
    function openLessonModal(lessonId) {
        // Backend'den ders verilerini çek
        fetch(`/get-lesson/${lessonId}`)
            .then(response => response.json())
            .then(data => {
                // Verileri modalda göster
                document.getElementById('modal-lesson-title').textContent = data.title;
                document.getElementById('modal-lesson-duration').textContent = data.duration_minutes + ' dakika';
                document.getElementById('modal-lesson-description').textContent = data.description || 'Açıklama bulunmamaktadır.';

                // Video içeriğini güncelle
                const videoContent = document.getElementById('modal-video-content');
                if (data.videoType === 'youtube' && data.videoId) {
                    videoContent.innerHTML = `
                        <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                            <iframe class="w-full h-96"
                                    src="https://www.youtube.com/embed/${data.videoId}?rel=0"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    `;
                } else if (data.embed_code) {
                    videoContent.innerHTML = `
                        <div class="aspect-w-16 aspect-h-9">
                            ${data.embed_code}
                        </div>
                    `;
                } else {
                    videoContent.innerHTML = `
                        <div class="bg-gray-100 rounded-lg p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-3 text-gray-600">Bu ders için video bulunamadı</p>
                        </div>
                    `;
                }

                // Modalı göster
                document.getElementById('lesson-modal').classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Modalı kapatma fonksiyonu
    function closeModal() {
        document.getElementById('lesson-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // ESC tuşu ile kapatma
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
