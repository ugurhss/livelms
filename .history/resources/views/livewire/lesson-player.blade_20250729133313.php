
<div>
 <!-- Lesson Player Modal -->
@if($showModal && $lesson)
<div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showModal') }" x-show="show" x-cloak>
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"
         @click="$wire.closeModal()"
         x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-7xl bg-white rounded-xl shadow-2xl"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">

            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h2>
                    @if(Auth::check())
                        <button
                            wire:click="markAsCompleted"
                            class="flex items-center px-3 py-1 rounded-md text-sm font-medium transition-colors
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

                <button wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Progress Bar -->
            @if(Auth::check())
            <div class="px-6 py-3 bg-gray-50 border-b">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700">Kurs İlerlemesi</span>
                    <span class="text-sm font-medium text-gray-700">{{ $userProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $userProgress }}%"></div>
                </div>
            </div>
            @endif

            <!-- Modal Body -->
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 p-6 max-h-[70vh] overflow-y-auto">
                <!-- Video Section -->
                <div class="xl:col-span-3">
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-black">
                        @if($lesson->video_type === 'youtube' && $lesson->video_id)
                            <iframe
                                class="w-full h-full min-h-[400px]"
                                src="https://www.youtube.com/embed/{{ $lesson->video_id }}?rel=0&autoplay=1&enablejsapi=1"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        @elseif($lesson->video_type === 'vimeo' && $lesson->video_id)
                            <iframe
                                class="w-full h-full min-h-[400px]"
                                src="https://player.vimeo.com/video/{{ $lesson->video_id }}?autoplay=1"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        @elseif($lesson->embed_code)
                            <div class="w-full h-full min-h-[400px]">
                                {!! $lesson->embed_code !!}
                            </div>
                        @else
                            <div class="bg-gray-100 flex items-center justify-center h-96">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-600">Video bulunamadı</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Lesson Description -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Ders Açıklaması</h4>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($lesson->description ?: 'Açıklama bulunmamaktadır.')) !!}
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-6 pt-4 border-t">
                        <button
                            wire:click="previousLesson"
                            class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Önceki Ders
                        </button>

                        <button
                            wire:click="nextLesson"
                            class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 transition-colors">
                            Sonraki Ders
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Curriculum Sidebar -->
                <div class="xl:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4 sticky top-0">
                        <h4 class="font-bold text-gray-900 mb-4">Kurs Müfredatı</h4>
                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            @foreach($course->lessons as $index => $courseLesson)
                                <button
                                    wire:click="loadLesson({{ $courseLesson->id }})"
                                    class="w-full text-left p-3 rounded-md text-sm transition-colors
                                        {{ $lesson && $lesson->id == $courseLesson->id
                                            ? 'bg-indigo-100 border-l-4 border-indigo-600 text-indigo-900'
                                            : 'hover:bg-gray-200 text-gray-700' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium truncate">
                                            {{ $index + 1 }}. {{ $courseLesson->title }}
                                        </span>
                                        @if(Auth::check() && Auth::user()->completedLessons()->where('lesson_id', $courseLesson->id)->exists())
                                            <svg class="w-4 h-4 text-green-500 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1 block">{{ $courseLesson->duration_minutes ?: 15 }} dakika</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Placeholder for when no lesson is selected -->
@if(!$showModal)
<div class="lesson-player-container bg-white rounded-xl shadow-sm p-8 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
    </svg>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Ders Seçin</h3>
    <p class="text-gray-600">Yukarıdaki müfredattan bir ders seçerek izlemeye başlayın</p>
</div>
@endif
   </div>
<style>
[x-cloak] { display: none !important; }
</style>
