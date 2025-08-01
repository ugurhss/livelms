<div>
    <div x-data="lessonModal()" x-cloak>
    <!-- Modal Trigger Button -->
    <div class="text-center">
        <button @click="openModal" type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            Dersleri Göster
        </button>
    </div>

    <!-- Modal -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">

        <div @click.away="closeModal"
             x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-xl shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col">

            <!-- Modal Header -->
            <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">
                    Ders İçeriği
                </h3>
                <button @click="closeModal" type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="flex flex-1 overflow-hidden h-full">
                <!-- Ders Listesi (Sol Taraf) -->
                <div class="w-1/3 border-r border-gray-200 overflow-y-auto">
                    <div class="p-4">
                        <h4 class="mb-4 text-sm font-semibold uppercase text-gray-600">
                            Dersler
                        </h4>

                        <div class="space-y-2">
                            @foreach($lessons as $index => $lesson)
                                <a href="#"
                                   @click.prevent="selectLesson({{ $index }})"
                                   class="flex items-center gap-x-3 p-3 rounded-lg hover:bg-gray-100"
                                   :class="{'bg-blue-50 border border-blue-200': activeLessonIndex === {{ $index }}}">
                                    <div class="flex items-center justify-center size-8 rounded-full"
                                         :class="activeLessonIndex === {{ $index }} ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'">
                                        <span class="text-xs font-medium" x-show="activeLessonIndex !== {{ $index }}">{{ $index + 1 }}</span>
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="activeLessonIndex === {{ $index }}"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
                                    </div>
                                    <div class="grow">
                                        <h5 class="text-sm font-medium" :class="activeLessonIndex === {{ $index }} ? 'text-blue-800' : 'text-gray-800'">{{ $lesson['title'] }}</h5>
                                        <p class="text-xs text-gray-500">{{ $lesson['duration'] }} dakika</p>
                                    </div>
                                    <div class="shrink-0" x-show="activeLessonIndex === {{ $index }}">
                                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="size-1.5 inline-block rounded-full bg-blue-800"></span>
                                            Devam Ediyor
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Ders İçeriği (Sağ Taraf) -->
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="max-w-4xl mx-auto">
                        <div class="video-container mb-6">
                            <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                                <iframe class="w-full h-96"
                                        :src="'https://www.youtube.com/embed/' + activeLesson.videoId + '?rel=0'"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900" x-text="activeLesson.title"></h3>
                                <p class="text-gray-600 text-sm" x-text="activeLesson.duration + ' dakika'"></p>
                            </div>

                            <div class="flex items-center gap-x-2">
                                <button type="button"
                                        @click="prevLesson"
                                        :disabled="activeLessonIndex === 0"
                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                    Önceki
                                </button>

                                <button type="button"
                                        @click="nextLesson"
                                        :disabled="activeLessonIndex === {{ count($lessons) - 1 }}"
                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                    Sonraki
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="lesson-content bg-gray-50 rounded-lg p-6">
                            <h4 class="font-semibold text-gray-900 mb-4">Ders Açıklaması</h4>
                            <p class="text-gray-700" x-text="activeLesson.description"></p>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-3">İlerleme Durumu</h4>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" :style="'width: ' + progress + '%'"></div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2" x-text="'Dersin %' + progress + ' tamamlandı'"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-between items-center gap-x-2 py-3 px-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span class="font-medium" x-text="completedLessons + '/{{ count($lessons) }}'"></span> Ders Tamamlandı
                </div>
                <div class="flex items-center gap-x-2">
                    <button type="button"
                            @click="completeLesson"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        Dersi Tamamla
                    </button>
                    <button type="button"
                            @click="closeModal"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('lessonModal', () => ({
            isOpen: false,
            activeLessonIndex: 0,
            completedLessons: 1,
            progress: 45,
            lessons: @json($lessons),

            get activeLesson() {
                return this.lessons[this.activeLessonIndex];
            },

            openModal() {
                this.isOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeModal() {
                this.isOpen = false;
                document.body.style.overflow = 'auto';
            },

            selectLesson(index) {
                this.activeLessonIndex = index;
                this.updateProgress();
            },

            prevLesson() {
                if (this.activeLessonIndex > 0) {
                    this.activeLessonIndex--;
                    this.updateProgress();
                }
            },

            nextLesson() {
                if (this.activeLessonIndex < this.lessons.length - 1) {
                    this.activeLessonIndex++;
                    this.updateProgress();
                }
            },

            completeLesson() {
                // Ders tamamlama işlemleri burada
                this.progress = 100;
            },

            updateProgress() {
                // İlerleme durumunu güncelle
                this.progress = this.activeLessonIndex === 0 ? 45 :
                               this.activeLessonIndex === 1 ? 70 : 25;
            }
        }));
    });
</script>
