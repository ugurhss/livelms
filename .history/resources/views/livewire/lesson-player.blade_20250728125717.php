<div class="lesson-player-container bg-white rounded-xl shadow-sm p-6 mb-6">
    @if($lesson)
        <!-- Header with improved responsive design -->
        <div class="lesson-header mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                    <span>{{ $lesson->duration_minutes }} dakika</span>
                    @if($lesson->is_free)
                        <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs">Ücretsiz</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="openLessonModal()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors w-full sm:w-auto justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    Tam Ekran
                </button>
                @if(auth()->check())
                    <button wire:click="toggleCompletion" class="flex items-center gap-2 {{ $isCompleted ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-200 hover:bg-gray-300 text-gray-800' }} px-4 py-2 rounded-lg transition-colors w-full sm:w-auto justify-center">
                        @if($isCompleted)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Tamamlandı
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tamamla
                        @endif
                    </button>
                @endif
            </div>
        </div>

        <!-- Full-screen Modal with improved transitions -->
        <div id="lessonModal" class="hidden fixed inset-0 bg-white z-50 h-screen w-screen overflow-auto transition-opacity duration-300 opacity-0">
            <!-- Sticky header with progress indicator -->
            <div class="sticky top-0 bg-white z-10 shadow-sm p-4 flex justify-between items-center border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <button onclick="closeLessonModal()" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <h3 class="text-lg font-bold text-gray-900 truncate max-w-md">{{ $lesson->title }}</h3>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <button onclick="closeLessonModal()" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Main Content with improved layout -->
            <div class="flex flex-col lg:flex-row h-[calc(100vh-56px)]">
                <!-- Left Side - Curriculum and Description -->
                <div class="w-full lg:w-1/3 xl:w-1/4 p-6 overflow-y-auto border-r border-gray-200">
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-3 text-lg">Müfredat</h4>
                        <div class="space-y-2">
                            @foreach($course->lessons as $courseLesson)
                                <a href="{{ route('courses.lessons.show', [$course->slug, $courseLesson->id]) }}"
                                   class="block p-3 rounded-lg transition-colors {{ $lesson->id === $courseLesson->id ? 'bg-blue-50 border border-blue-200' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-5 w-5 mt-1 {{ $lesson->id === $courseLesson->id ? 'text-blue-500' : ($courseLesson->completedBy->contains(auth()->id()) ? 'text-green-500' : 'text-gray-400' }}">
                                            @if($lesson->id === $courseLesson->id)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.99 0C4.47 0 0 4.48 0 10s4.47 10 9.99 10C15.52 20 20 15.52 20 10S15.52 0 9.99 0zM10 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                                                    <path d="M10 5c-.28 0-.5.22-.5.5v5.59l-2.79-2.79c-.19-.19-.51-.19-.7 0-.19.19-.19.51 0 .7l3.5 3.5c.19.19.51.19.7 0l3.5-3.5c.19-.19.19-.51 0-.7-.19-.19-.51-.19-.7 0l-2.79 2.79V5.5c0-.28-.22-.5-.5-.5z" />
                                                </svg>
                                            @elseif($courseLesson->completedBy->contains(auth()->id()))
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium {{ $lesson->id === $courseLesson->id ? 'text-blue-700' : 'text-gray-700' }}">{{ $courseLesson->title }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $courseLesson->duration_minutes }} dakika</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 text-lg">Ders Açıklaması</h4>
                        <div class="prose prose-sm max-w-none">
                            {!! Str::markdown($lesson->description ?: 'Açıklama bulunmamaktadır.') !!}
                        </div>
                    </div>
                </div>

                <!-- Right Side - Video and Resources -->
                <div class="w-full lg:w-2/3 xl:w-3/4 flex flex-col h-full">
                    <!-- Video Player Section -->
                    <div class="flex-1 bg-gray-900 flex items-center justify-center p-4">
                        @if($videoType === 'youtube' && $videoId)
                            <div class="w-full h-full max-w-4xl mx-auto">
                                <div class="aspect-w-16 aspect-h-9 h-full">
                                    <iframe class="w-full h-full"
                                            src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1&showinfo=0"
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
                            <div class="text-center text-white p-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-3 text-lg">Bu ders için video bulunamadı</p>
                                @if($lesson->attachments->count() > 0)
                                    <div class="mt-6">
                                        <a href="{{ $lesson->attachments->first()->url }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                            </svg>
                                            Ders Notlarını İndir
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Resources Tab -->
                    <div class="border-t border-gray-200 bg-white">
                        <div class="px-6 py-4">
                            <h4 class="font-semibold text-gray-900 mb-3 text-lg">Ders Kaynakları</h4>
                            @if($lesson->attachments->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($lesson->attachments as $attachment)
                                        <a href="{{ $attachment->url }}" target="_blank" class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors group">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 p-2 bg-gray-100 rounded-lg group-hover:bg-blue-50 transition-colors">
                                                    @if(Str::contains($attachment->mime_type, 'pdf'))
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @elseif(Str::contains($attachment->mime_type, 'word'))
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $attachment->size_for_humans }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 bg-gray-50 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-gray-600">Bu ders için ek kaynak bulunmamaktadır</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Original content with improved layout -->
        <div id="originalContent">
            <div class="video-container mb-6 rounded-lg overflow-hidden shadow-sm">
                @if($videoType === 'youtube' && $videoId)
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe class="w-full h-96"
                                src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1"
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
                        @if($lesson->attachments->count() > 0)
                            <div class="mt-4">
                                <a href="{{ $lesson->attachments->first()->url }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    Ders Notlarını İndir
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="lesson-content grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3 text-lg">Ders Açıklaması</h4>
                        <div class="prose prose-sm max-w-none">
                            {!! Str::markdown($lesson->description ?: 'Açıklama bulunmamaktadır.') !!}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3 text-lg">Ders Bilgileri</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500">Ders Süresi</p>
                                <p class="text-sm font-medium">{{ $lesson->duration_minutes }} dakika</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Eklenme Tarihi</p>
                                <p class="text-sm font-medium">{{ $lesson->created_at->format('d.m.Y') }}</p>
                            </div>
                            @if($lesson->attachments->count() > 0)
                                <div>
                                    <p class="text-xs text-gray-500">Dosya Eki</p>
                                    <p class="text-sm font-medium">{{ $lesson->attachments->count() }} dosya</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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
        const modal = document.getElementById('lessonModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';

        // Add event listener for video play/pause when in fullscreen
        const iframe = modal.querySelector('iframe');
        if (iframe) {
            iframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        }
    }

    function closeLessonModal() {
        const modal = document.getElementById('lessonModal');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';

        // Pause video when closing modal
        const iframe = modal.querySelector('iframe');
        if (iframe) {
            iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        }
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLessonModal();
        }
    });

    // YouTube API ready function
    function onYouTubeIframeAPIReady() {
        // This function is required by YouTube API
    }
</script>
