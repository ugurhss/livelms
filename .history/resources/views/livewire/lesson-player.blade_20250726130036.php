<div x-data="{ isOpen: @entangle('isOpen') }">
    <!-- Modal Trigger Buttons -->
    <div class="space-y-2">
        @forelse($lessons as $lesson)
            <button wire:click="openLessonModal({{ $lesson->id }})"
                    class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex justify-between items-center">
                    <span>{{ $loop->iteration }}. {{ $lesson->title }}</span>
                    <span class="text-xs text-gray-500">{{ $lesson->duration_minutes }} dakika</span>
                </div>
            </button>
        @empty
            <p class="text-gray-500">Henüz ders eklenmemiş</p>
        @endforelse
    </div>

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
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
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
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full h-[80vh]"
                 role="dialog">
                <div class="flex h-full">
                    <!-- Left Sidebar - Curriculum -->
                    <div class="w-1/4 bg-gray-50 p-4 border-r overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Kurs Müfredatı</h3>
                            <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <ul class="space-y-2">
                            @foreach($lessons as $lesson)
                                <li>
                                    <button wire:click="openLessonModal({{ $lesson->id }})"
                                            class="w-full text-left px-3 py-2 rounded-lg transition-colors {{ $currentLesson && $currentLesson->id == $lesson->id ? 'bg-indigo-100 text-indigo-700 font-medium' : 'hover:bg-gray-100' }}">
                                        <div class="flex justify-between items-center">
                                            <span>{{ $loop->iteration }}. {{ $lesson->title }}</span>
                                            @if($currentLesson && $currentLesson->id == $lesson->id)
                                                <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500 block mt-1">{{ $lesson->duration_minutes }} dakika</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Main Content -->
                    <div class="w-3/4 flex flex-col">
                        @if($currentLesson)
                            <!-- Lesson Header -->
                            <div class="p-6 border-b flex justify-between items-start">
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $currentLesson->title }}</h2>
                                    <p class="text-gray-500 mt-1">{{ $currentLesson->duration_minutes }} dakika</p>
                                </div>
                                <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Video Player -->
                            <div class="flex-1 bg-black overflow-hidden">
                                <iframe class="w-full h-full" src="{{ $currentLesson->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>

                            <!-- Lesson Content -->
                            <div class="p-6 border-t overflow-y-auto" style="max-height: 30vh;">
                                <div class="prose max-w-none">
                                    {!! nl2br(e($currentLesson->description)) !!}
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="p-4 border-t flex items-center justify-between bg-gray-50">
                                <button wire:click="prevLesson"
                                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors flex items-center {{ $currentLesson->order == 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $currentLesson->order == 1 ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Önceki
                                </button>

                                <div class="flex-1 mx-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 text-center">Tamamlanma: {{ $progress }}%</p>
                                </div>

                                <button wire:click="nextLesson"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center {{ $currentLesson->order == $lessons->count() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $currentLesson->order == $lessons->count() ? 'disabled' : '' }}>
                                    Sonraki
                                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center">
                                <div class="text-center p-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900">Ders bulunamadı</h3>
                                    <p class="mt-1 text-gray-500">Lütfen geçerli bir ders seçin.</p>
                                    <div class="mt-6">
                                        <button @click="isOpen = false" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                            Kapat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
