<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kurs Sayfasına Dön
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Ders Listesi -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-sm p-4">
                <h2 class="text-xl font-bold mb-4 text-gray-900">{{ $course->title }}</h2>
                <div class="space-y-1">
                    @foreach($lessons as $lesson)
                        <a href="{{ route('courses.learn', ['course' => $course->id, 'lesson' => $lesson->id]) }}"
                           class="block px-3 py-2 rounded-md {{ request()->has('lesson') && request()->lesson == $lesson->id ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">
                            <div class="flex justify-between items-center">
                                <span>{{ $loop->iteration }}. {{ $lesson->title }}</span>
                                @if($lesson->isCompleted(Auth::id()))
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $lesson->duration_minutes }} dakika</div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Ders İçeriği -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    @if(request()->has('lesson'))
                        @php $currentLesson = $lessons->firstWhere('id', request()->lesson); @endphp
                        <div class="p-4 border-b">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $currentLesson->title }}</h3>
                        </div>

                        <div class="p-4">
                            <!-- Video Player -->
                            <div class="aspect-w-16 aspect-h-9 mb-4">
                                @if($currentLesson->video_type === 'youtube')
                                    <iframe class="w-full h-96" src="https://www.youtube.com/embed/{{ $currentLesson->video_id }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <video controls class="w-full h-auto">
                                        <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                        Tarayıcınız video oynatmayı desteklemiyor.
                                    </video>
                                @endif
                            </div>

                            <!-- Ders Açıklaması -->
                            <div class="prose max-w-none mb-6">
                                {!! nl2br(e($currentLesson->description)) !!}
                            </div>

                            <!-- İlerleme Butonları -->
                            <div class="flex justify-between items-center border-t pt-4">
                                @if($prevLesson = $lessons->firstWhere('order', $currentLesson->order - 1))
                                    <a href="{{ route('courses.learn', ['course' => $course->id, 'lesson' => $prevLesson->id]) }}"
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Önceki Ders
                                    </a>
                                @else
                                    <span></span>
                                @endif

                                <form action="{{ route('lessons.complete', $currentLesson->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                        @if($currentLesson->isCompleted(Auth::id()))
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Tamamlandı
                                        @else
                                            Dersi Tamamla
                                        @endif
                                    </button>
                                </form>

                                @if($nextLesson = $lessons->firstWhere('order', $currentLesson->order + 1))
                                    <a href="{{ route('courses.learn', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                        Sonraki Ders
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <h3 class="text-lg font-medium text-gray-900">Ders Seçin</h3>
                            <p class="mt-2 text-gray-600">Soldaki listeden izlemek istediğiniz dersi seçin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
