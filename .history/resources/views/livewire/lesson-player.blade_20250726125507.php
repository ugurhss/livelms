<div>
    <!-- Modal Trigger -->
    @foreach($lessons as $lesson)
        <button wire:click="openLessonModal({{ $lesson->id }})" class="text-indigo-600 hover:text-indigo-900">
            {{ $lesson->title }}
        </button>
    @endforeach

    <!-- Modal -->
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity"
                 aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal Content -->
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full"
                 role="dialog"
                 aria-modal="true"
                 aria-labelledby="modal-headline">
                <div class="flex h-full">
                    <!-- Left Sidebar - Curriculum -->
                    <div class="w-1/4 bg-gray-50 p-4 border-r">
                        <h3 class="text-lg font-bold mb-4">Kurs Müfredatı</h3>
                        <ul class="space-y-2">
                            @foreach($lessons as $lesson)
                                <li>
                                    <button wire:click="openLessonModal({{ $lesson->id }})"
                                            class="w-full text-left px-3 py-2 rounded {{ $currentLesson->id == $lesson->id ? 'bg-indigo-100 text-indigo-700' : 'hover:bg-gray-100' }}">
                                        {{ $loop->iteration }}. {{ $lesson->title }}
                                        <span class="text-xs text-gray-500 block">{{ $lesson->duration_minutes }} dakika</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Main Content -->
                    <div class="w-3/4 p-6">
                        <div class="flex justify-between items-start mb-6">
                            <h2 class="text-2xl font-bold">{{ $currentLesson->title }}</h2>
                            <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Video Player -->
                        <div class="bg-black rounded-lg overflow-hidden mb-6">
                            <iframe class="w-full aspect-video" src="{{ $currentLesson->video_url }}" frameborder="0" allowfullscreen></iframe>
                        </div>

                        <!-- Lesson Description -->
                        <div class="prose max-w-none mb-6">
                            {!! nl2br(e($currentLesson->description)) !!}
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center border-t pt-4">
                            <button wire:click="prevLesson"
                                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 {{ $currentLesson->order == 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $currentLesson->order == 1 ? 'disabled' : '' }}>
                                Önceki Ders
                            </button>

                            <div class="w-1/3 mx-4 bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>

                            <button wire:click="nextLesson"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 {{ $currentLesson->order == $lessons->count() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $currentLesson->order == $lessons->count() ? 'disabled' : '' }}>
                                Sonraki Ders
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
