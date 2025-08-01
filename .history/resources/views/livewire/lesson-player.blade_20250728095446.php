<div x-data="{ isOpen: false, lessonId: null }" x-init="
    Livewire.on('openLessonModal', (lessonId) => {
        this.lessonId = lessonId;
        this.isOpen = true;
        $wire.loadLesson(lessonId);
    });
">
    <!-- Modal Overlay -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50"
         @click="isOpen = false">
    </div>

    <!-- Modal Content -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">

        <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-lg font-bold" x-text="lessonId ? 'Ders İzle' : ''"></h3>
                <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 h-full">
                    <!-- Video Player (2/3) -->
                    <div class="lg:col-span-2 p-6">
                        @livewire('lesson-player', ['course' => $course], key('lesson-player-'.time()))
                    </div>

                    <!-- Curriculum (1/3) -->
                    <div class="lg:col-span-1 bg-gray-50 p-6 overflow-y-auto">
                        <h4 class="font-bold text-lg mb-4">Kurs Müfredatı</h4>
                        <div class="space-y-2">
                            @foreach($course->lessons as $lesson)
                                <div
                                    wire:click="loadLesson({{ $lesson->id }})"
                                    @click="lessonId = {{ $lesson->id }}"
                                    class="p-3 rounded cursor-pointer transition {{ $lesson->id == $lessonId ? 'bg-indigo-50 border border-indigo-200' : 'hover:bg-gray-100' }}"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="truncate">{{ $loop->iteration }}. {{ $lesson->title }}</span>
                                        @if($lesson->is_free)
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Ücretsiz</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $lesson->duration_minutes }} dakika</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
