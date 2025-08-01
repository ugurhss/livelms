<div class="lesson-player-container bg-white rounded-xl shadow-sm p-6 mb-6">
    @if($lesson)
        <!-- İlerleme Çubuğu -->
        @if(Auth::check())
            <div class="progress-bar mb-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700">Kurs İlerlemesi</span>
                    <span class="text-sm font-medium text-gray-700">{{ $userProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $userProgress }}%"></div>
                </div>
            </div>
        @endif

        <!-- Ders Başlık ve Tamamlama Butonu -->
        <div class="lesson-header mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                    <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
                </div>
                @if(Auth::check())
                    <button wire:click="markAsCompleted"
                            class="flex items-center px-3 py-1 rounded-md text-sm font-medium
                                   {{ $isLessonCompleted ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                        @if($isLessonCompleted)
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Tamamlandı
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tamamla
                        @endif
                    </button>
                @endif
            </div>
        </div>

        <!-- Video Thumbnail (Modal Tetikleyici) -->
        <div class="video-container mb-4">
            @if($videoType === 'youtube' && $videoId)
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden cursor-pointer"
                     wire:click="showLessonModal">
                    <div class="w-full h-96 bg-gray-800 relative">
                        <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg"
                             alt="Video thumbnail"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-20 h-20 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                            </svg>
                        </div>
                        @if($isLessonCompleted)
                            <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-md flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Tamamlandı
                            </div>
                        @endif
                    </div>
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

        <!-- Ders Açıklaması -->
        <div class="lesson-content">
            <h4 class="font-semibold text-gray-900 mb-2">Ders Açıklaması</h4>
            <p class="text-gray-700 whitespace-pre-line">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
        </div>

        <!-- Video Modal -->
      <div x-data="{ showModal: @entangle('showModal') }"
     x-show="showModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     @keydown.escape.window="showModal = false">

    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div @click="showModal = false" class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>

        <!-- Modal Penceresi -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <!-- Modal Başlık ve Tamamlama Butonu -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ $lesson->title }}
                            </h3>
                            @if(Auth::check())
                                <button wire:click="markAsCompleted"
                                        class="flex items-center px-3 py-1 rounded-md text-sm font-medium
                                               {{ $isLessonCompleted ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                    @if($isLessonCompleted)
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Tamamlandı
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tamamla
                                    @endif
                                </button>
                            @endif
                        </div>

                        <!-- Video İçeriği -->
                        <div class="video-container">
                            @if($videoType === 'youtube' && $videoId)
                                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                                    <iframe class="w-full h-96"
                                            src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&autoplay=1&enablejsapi=1"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                </div>
                            @elseif($lesson->embed_code)
                                <div class="aspect-w-16 aspect-h-9">
                                    {!! $lesson->embed_code !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function() {
        // Modal göster/gizle kontrolleri
        Livewire.on('showModal', () => {
            const modal = document.querySelector('[x-data]');
            modal.style.display = 'block';
        });

        Livewire.on('modalClosed', () => {
            const modal = document.querySelector('[x-data]');
            modal.style.display = 'none';
        });

        // ESC tuşu ile kapatma
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                Livewire.emit('closeModal');
            }
        });
    });
</script>
    @else
        <!-- Ders yoksa gösterilecek içerik -->
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-3 text-gray-600">Gösterilecek ders bulunamadı</p>
        </div>
    @endif
</div>
