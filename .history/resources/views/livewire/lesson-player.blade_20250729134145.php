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
     @keydown.escape.window="$wire.closeModal()">

    <div class="flex min-h-screen">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div @click="$wire.closeModal()" class="absolute inset-0 bg-gray-900 opacity-90"></div>
        </div>

        <!-- Modal İçeriği - Tam Ekran Layout -->
        <div class="relative z-10 w-full max-w-none mx-auto bg-white shadow-xl flex"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <!-- Sol Taraf - Video Player -->
            <div class="flex-1 bg-black relative">
                @if($lesson)
                    <div class="w-full h-screen flex items-center justify-center">
                        @if($videoType === 'youtube' && $videoId)
                            <iframe class="w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&autoplay=1&enablejsapi=1"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        @elseif($lesson->embed_code)
                            <div class="w-full h-full">
                                {!! $lesson->embed_code !!}
                            </div>
                        @else
                            <div class="text-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xl">Bu ders için video bulunamadı</p>
                            </div>
                        @endif
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $lesson->title }}</h3>
                                <p class="text-sm text-gray-300">{{ $lesson->duration_minutes ?: '15' }} dakika</p>
                            </div>

                            @if(Auth::check())
                                <button wire:click="markAsCompleted"
                                        class="flex items-center px-4 py-2 rounded-md text-sm font-medium transition-colors
                                               {{ $isLessonCompleted ? 'bg-green-600 text-white' : 'bg-white/20 text-white hover:bg-white/30' }}">
                                    @if($isLessonCompleted)
                                        <svg class="w-4 h-4 mr-2 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Tamamlandı
                                    @else
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tamamla
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sağ Taraf - Müfredat -->
            <div class="w-80 bg-white border-l border-gray-200 overflow-y-auto">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Kurs Müfredatı</h2>
                        <button @click="$wire.closeModal()"
                                class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @if(Auth::check())
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-medium text-gray-600">İlerleme</span>
                                <span class="text-xs font-medium text-gray-600">{{ $userProgress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-300"
                                     style="width: {{ $userProgress }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="divide-y divide-gray-200">
                    @if($course && $course->lessons)
                        @foreach($course->lessons as $index => $courseLesson)
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer
                                        {{ $lesson && $lesson->id === $courseLesson->id ? 'bg-indigo-50 border-r-2 border-indigo-500' : '' }}"
                                 wire:click="loadLesson({{ $courseLesson->id }})">

                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center mb-1">
                                            <span class="text-xs font-medium text-gray-500 mr-2">{{ $index + 1 }}.</span>
                                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                                {{ $courseLesson->title }}
                                            </h4>
                                        </div>

                                        <p class="text-xs text-gray-500 mb-2">
                                            {{ $courseLesson->duration_minutes ?: '15' }} dakika
                                        </p>

                                        @if($courseLesson->description)
                                            <p class="text-xs text-gray-600 line-clamp-2">
                                                {{ Str::limit($courseLesson->description, 80) }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center ml-2">
                                        @if(Auth::check() && Auth::user()->completedLessons()->where('lesson_id', $courseLesson->id)->exists())
                                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                @if($lesson && $lesson->id === $courseLesson->id)
                                    <div class="mt-2 pt-2 border-t border-indigo-200">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                            </svg>
                                            Şu anda oynatılıyor
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    @if($lesson)
                        <div class="text-center">
                            <h4 class="font-medium text-gray-900 mb-2">Şu Anki Ders</h4>
                            <p class="text-sm text-gray-600">{{ $lesson->title }}</p>
                            @if($lesson->description)
                                <p class="text-xs text-gray-500 mt-2 line-clamp-3">
                                    {{ $lesson->description }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stil sınıflarını kök div içine aldık --}}
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>
