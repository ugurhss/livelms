<div class="lesson-player-container bg-white rounded-xl shadow-sm p-0">
    @if($lesson)
        <!-- İlerleme Çubuğu -->
        @if(Auth::check())
            <div class="border-b px-6 py-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700">Kurs İlerlemesi</span>
                    <span class="text-sm font-medium text-gray-700">{{ $userProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $userProgress }}%"></div>
                </div>
            </div>
        @endif

        <!-- Video ve Müfredat Yan Yana -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
            <!-- Video Bölümü (2/3) -->
            <div class="lg:col-span-2">
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    @if($lesson->video_type === 'youtube' && $lesson->video_id)
                        <iframe
                            class="w-full h-96 lg:h-[500px]"
                            src="https://www.youtube.com/embed/{{ $lesson->video_id }}?rel=0&autoplay=1&enablejsapi=1"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    @elseif($lesson->embed_code)
                        {!! $lesson->embed_code !!}
                    @else
                        <div class="bg-gray-100 flex items-center justify-center h-64">
                            <p class="text-gray-600">Video bulunamadı</p>
                        </div>
                    @endif
                </div>

                <!-- Ders Başlığı ve Tamamlama Butonu -->
                <div class="flex justify-between items-center mt-4">
                    <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                    @if(Auth::check())
                        <button
                            wire:click="markAsCompleted"
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

                <!-- Ders Açıklaması -->
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Ders Açıklaması</h4>
                    <p class="text-gray-700 whitespace-pre-line">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
                </div>
            </div>

            <!-- Müfredat Bölümü (1/3) -->
            <div class="bg-gray-50 rounded-lg p-4 h-fit">
                <h4 class="font-bold text-gray-900 mb-4">Kurs Müfredatı</h4>
                <ul class="space-y-2">
                    @foreach($course->lessons as $index => $item)
                        <li>
                            <button
                                wire:click="loadLesson({{ $item->id }})"
                                class="w-full text-left p-3 rounded-md flex items-center justify-between
                                    {{ $lesson->id == $item->id ? 'bg-indigo-100 border-l-4 border-indigo-600' : 'hover:bg-gray-200' }}">
                                <span class="font-medium text-sm">
                                    {{ $index + 1 }}. {{ $item->title }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $item->duration_minutes }} dk</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <p class="mt-3 text-gray-600">Lütfen bir ders seçin</p>
        </div>
    @endif
</div>
