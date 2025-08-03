<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
        <!-- Geri Dönüş Butonu -->
        <div class="mb-4 md:mb-6">
            <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium">Kurs Sayfasına Dön</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
            <!-- Ders Listesi -->
            <div class="lg:col-span-1 bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                <div class="flex justify-between items-center lg:block">
                    <h2 class="text-lg md:text-xl font-bold mb-3 text-gray-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ $course->title }}
                    </h2>
                    <button class="lg:hidden text-gray-500 focus:outline-none" id="lessonsToggle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-1 hidden lg:block" id="lessonsContainer">
                    @foreach($lessons as $lesson)
                        <a href="{{ route('courses.learn', ['course' => $course->id, 'lesson' => $lesson->id]) }}"
                           class="block px-3 py-2 rounded-lg transition-colors duration-200 {{ request()->has('lesson') && request()->lesson == $lesson->id ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-gray-50 text-gray-700' }}">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="bg-gray-100 text-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">
                                        {{ $loop->iteration }}
                                    </span>
                                    <span>{{ $lesson->title }}</span>
                                </div>
                                @if($lesson->isCompleted(Auth::id()))
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex items-center text-xs text-gray-500 mt-1 ml-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $lesson->duration_minutes }} dakika
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Ders İçeriği -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    @if(request()->has('lesson'))
                        @php $currentLesson = $lessons->firstWhere('id', request()->lesson); @endphp
                        <div class="p-4 md:p-6 border-b">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    {{ $currentLesson->title }}
                                </h3>
                                <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ $currentLesson->order }}/{{ $lessons->count() }}. Ders
                                </span>
                            </div>
                        </div>

                        <div class="p-4 md:p-6">
                            <!-- MP4 Video Player -->
                            <div class="relative">
                                <video
                                    id="lessonVideo"
                                    class="w-full h-auto rounded-lg shadow-xs"
                                    controls
                                    playsinline
                                    poster="{{ $currentLesson->thumbnail_url ?? '' }}"
                                >
                                    <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                    Tarayıcınız video oynatmayı desteklemiyor.
                                </video>

                                <!-- Özel Kontroller -->
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black to-transparent pt-10 pb-4 px-4 opacity-0 hover:opacity-100 transition-opacity duration-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex space-x-4">
                                            <button id="videoPlayPause" class="text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                </svg>
                                            </button>
                                            <div class="text-white text-sm" id="videoTime">0:00 / 0:00</div>
                                        </div>
                                        <button id="videoFullscreen" class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="range" min="0" max="100" value="0" class="w-full mt-2 h-1.5 bg-white bg-opacity-30 rounded-lg appearance-none cursor-pointer" id="videoProgress">
                                </div>
                            </div>

                            <!-- Ders Açıklaması -->
                            <div class="prose max-w-none mb-6 p-4 bg-gray-50 rounded-lg mt-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Ders Açıklaması
                                </h4>
                                <div class="text-gray-700">
                                    {!! nl2br(e($currentLesson->description)) !!}
                                </div>
                            </div>

                            <!-- İlerleme Butonları -->
                            <div class="flex flex-col sm:flex-row justify-between items-center border-t pt-6 space-y-3 sm:space-y-0">
                                @if($prevLesson = $lessons->firstWhere('order', $currentLesson->order - 1))
                                    <a href="{{ route('courses.learn', ['course' => $course->id, 'lesson' => $prevLesson->id]) }}"
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 w-full sm:w-auto justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Önceki Ders
                                    </a>
                                @else
                                    <div></div>
                                @endif

                                <form action="{{ route('lessons.complete', $currentLesson->id) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 w-full justify-center">
                                        @if($currentLesson->isCompleted(Auth::id()))
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Tamamlandı
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Dersi Tamamla
                                        @endif
                                    </button>
                                </form>

                                @if($nextLesson = $lessons->firstWhere('order', $currentLesson->order + 1))
                                    <a href="{{ route('courses.learn', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                                        Sonraki Ders
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Ders Seçin</h3>
                            <p class="mt-2 text-gray-600">Soldaki listeden izlemek istediğiniz dersi seçerek başlayabilirsiniz.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobilde ders listesini açıp kapamak için
        document.getElementById('lessonsToggle')?.addEventListener('click', function() {
            const container = document.getElementById('lessonsContainer');
            container.classList.toggle('hidden');
        });

        // Video kontrol fonksiyonları
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('lessonVideo');
            if (video) {
                const playPauseBtn = document.getElementById('videoPlayPause');
                const progressBar = document.getElementById('videoProgress');
                const timeDisplay = document.getElementById('videoTime');
                const fullscreenBtn = document.getElementById('videoFullscreen');

                // Play/Pause kontrolü
                playPauseBtn?.addEventListener('click', function() {
                    if (video.paused) {
                        video.play();
                        playPauseBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>`;
                    } else {
                        video.pause();
                        playPauseBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        </svg>`;
                    }
                });

                // İlerleme çubuğu güncelleme
                video.addEventListener('timeupdate', function() {
                    const value = (video.currentTime / video.duration) * 100;
                    progressBar.value = value;

                    const formatTime = (time) => {
                        const minutes = Math.floor(time / 60);
                        const seconds = Math.floor(time % 60);
                        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    };

                    timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
                });

                // İlerleme çubuğu ile video kontrolü
                progressBar?.addEventListener('input', function() {
                    const seekTime = (video.duration / 100) * progressBar.value;
                    video.currentTime = seekTime;
                });

                // Tam ekran butonu
                fullscreenBtn?.addEventListener('click', function() {
                    if (video.requestFullscreen) {
                        video.requestFullscreen();
                    } else if (video.webkitRequestFullscreen) {
                        video.webkitRequestFullscreen();
                    } else if (video.msRequestFullscreen) {
                        video.msRequestFullscreen();
                    }
                });

                // Video tıklayınca play/pause
                video.addEventListener('click', function() {
                    if (video.paused) {
                        video.play();
                        playPauseBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>`;
                    } else {
                        video.pause();
                        playPauseBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        </svg>`;
                    }
                });
            }
        });
    </script>
</x-layouts.app>
