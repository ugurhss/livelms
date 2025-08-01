<div x-data="{ isOpen: false, lessonId: null }" x-init="
    Livewire.on('openLessonModal', (lessonId) => {
        this.lessonId = lessonId;
        this.isOpen = true;
    });
">
    <!-- Modal Trigger (Bu kısmı kaldırabilirsiniz, zaten butonunuz var) -->

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"
                 @click="isOpen = false">
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full"
                 x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Video ve içerik kısmı (sol taraf - 2/3) -->
                        <div class="lg:col-span-2">
                            @livewire('lesson-player', ['course' => $course], key('lesson-player-'.$lessonId))
                        </div>

                        <!-- Müfredat kısmı (sağ taraf - 1/3) -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-bold text-lg mb-4">Kurs Müfredatı</h3>
                                <div class="space-y-2">
                                    @foreach($course->lessons as $lesson)
                                        <div class="p-3 rounded hover:bg-gray-100 cursor-pointer transition
                                            {{ $lesson->id == $lessonId ? 'bg-indigo-50 border border-indigo-200' : '' }}"
                                            wire:click="$emit('loadLesson', {{ $lesson->id }})">
                                            <div class="flex items-center">
                                                @if($lesson->is_free)
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded mr-2">Ücretsiz</span>
                                                @endif
                                                <span class="truncate">{{ $lesson->title }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $lesson->duration_minutes }} dakika</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="isOpen = false">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
