<div class="lesson-player-container bg-white rounded-xl shadow-sm p-6 mb-6">
    @if($lesson)
        <div class="lesson-header mb-4 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                <p class="text-gray-600 text-sm">{{ $lesson->duration_minutes }} dakika</p>
            </div>
            <button onclick="openLessonModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tam Ekran Aç
            </button>
        </div>

        <!-- Full-screen Modal -->
        <div id="lessonModal" class="hidden fixed inset-0 bg-white z-50 h-screen w-screen overflow-auto">
            <!-- Header with progress and controls -->
            <div class="sticky top-0 bg-white z-10 shadow-sm p-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <button onclick="closeLessonModal()" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h3 class="text-xl font-bold text-gray-900">{{ $lesson->title }}</h3>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                        <span id="currentTime">00:00</span>
                        <span>/</span>
                        <span id="duration">00:00</span>
                    </div>
                    <button class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row h-[calc(100vh-56px)]">
                <!-- Left Side - Curriculum and Description (Scrollable) -->
                <div class="w-full lg:w-2/5 p-6 overflow-y-auto border-r border-gray-200">
          <div class="mb-8">
    <h4 class="font-semibold text-gray-900 mb-3 text-lg">Müfredat</h4>

    <!-- Backend'den gelen dersler -->
    @if($course->lessons && $course->lessons->count() > 0)
        <div class="space-y-2">
            @foreach($course->lessons as $index => $lesson)
                <div class="p-3 rounded-lg hover:bg-gray-50 cursor-pointer {{ $lesson->id === $currentLesson->id ? 'bg-blue-50 border border-blue-200' : '' }}"
                     onclick="loadNewVideo('{{ $lesson->video_id }}', '{{ $lesson->id }}')">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full {{ $lesson->id === $currentLesson->id ? 'bg-blue-600' : ($lesson->completed ? 'bg-green-100' : 'bg-gray-200') }} flex items-center justify-center mr-3">
                            @if($lesson->id === $currentLesson->id)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($lesson->completed)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="{{ $lesson->id === $currentLesson->id ? 'text-blue-800 font-medium' : 'text-gray-700' }}">
                                {{ $lesson->title }}
                                @if($lesson->id === $currentLesson->id)
                                    <span class="text-blue-600 text-sm">(Şu an izleniyor)</span>
                                @endif
                            </p>
                            <p class="text-gray-500 text-sm">{{ $lesson->duration_minutes }} dakika</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Toplam ders sayısı ve süre -->
        <div class="mt-2 flex items-center justify-between">
            <span class="text-gray-600">{{ $course->lessons->count() }} ders • Toplam {{ $course->lessons->sum('duration_minutes') }} dakika</span>
            @if($expandableLessons)
                <button id="toggleAllAccordions" class="text-indigo-600 text-sm font-medium hover:underline hover:text-indigo-700 transition-colors">
                    Tümünü Aç
                </button>
            @endif
        </div>
    @else
        <p class="text-gray-500">Bu kurs için henüz ders eklenmemiş.</p>
    @endif
</div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 text-lg">Ders Açıklaması</h4>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-line">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Video (Larger section) -->
                <div class="w-full lg:w-3/5 bg-gray-900 flex flex-col">
                    <!-- Video Container -->
                    <div class="flex-1 relative" id="customPlayer">
                        @if($videoType === 'youtube' && $videoId)
                            <div id="player"></div>
                        @elseif($lesson->embed_code)
                            <div class="w-full h-full">
                                {!! $lesson->embed_code !!}
                            </div>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-white">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-4 text-xl">Bu ders için video bulunamadı</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Custom Progress Bar -->
                    <div class="h-1 bg-gray-700 w-full">
                        <div class="h-full bg-red-600" id="progressBar" style="width: 0%"></div>
                    </div>

                    <!-- Custom Video Controls -->
                    <div class="bg-gray-800 p-3 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button class="text-white hover:text-gray-300" onclick="togglePlay()">
                                <svg id="playBtn" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                </svg>
                                <svg id="pauseBtn" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                            <span class="text-white text-sm" id="videoCurrentTime">00:00</span>
                        </div>

                        <div class="flex-1 mx-4">
                            <input type="range" id="videoSeek" class="w-full h-1 rounded-lg appearance-none bg-gray-600" min="0" max="100" step="1" value="0">
                        </div>

                        <div class="flex items-center space-x-4">
                            <span class="text-white text-sm" id="videoDuration">00:00</span>
                            <button class="text-white hover:text-gray-300" onclick="toggleFullscreen()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Original content -->
        <div id="originalContent">
            <div class="video-container mb-4">
                @if($videoType === 'youtube' && $videoId)
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                        <iframe class="w-full h-96"
                                src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                @elseif($lesson->embed_code)
                    <div class="aspect-w-16 aspect-h-9">
                        {!! $lesson->embed_code !!}
                    </div>
                @else
                    <div class="bg-gray-100 rounded-lg p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-3 text-gray-600">Bu ders için video bulunamadı</p>
                    </div>
                @endif
            </div>

            <div class="lesson-content">
                <h4 class="font-semibold text-gray-900 mb-2">Ders Açıklaması</h4>
                <p class="text-gray-700">{{ $lesson->description ?: 'Açıklama bulunmamaktadır.' }}</p>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-3 text-gray-600">Gösterilecek ders bulunamadı</p>
        </div>
    @endif
</div>

<script>
    // YouTube API and player variables
    let player;
    let isPlaying = false;
    let updateInterval;
    let videoEnded = false;

    function onYouTubeIframeAPIReady() {
        initializeYouTubePlayer();
    }

    function loadYouTubeAPI() {
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    function initializeYouTubePlayer() {
        player = new YT.Player('player', {
            height: '100%',
            width: '100%',
            videoId: '{{ $videoId }}',
            playerVars: {
                'autoplay': 0,
                'controls': 0,
                'disablekb': 1, // Klavye kontrollerini devre dışı bırak
                'modestbranding': 1, // YouTube logosunu küçült
                'rel': 0, // Bitince önerilen videoları gösterme
                'fs': 0, // Fullscreen butonunu gizle
                'enablejsapi': 1,
                'iv_load_policy': 3, // Açıklamaları gizle
                'playsinline': 1, // iOS'ta tam ekran olmadan oynat
                'showinfo': 0, // Video başlığını gizle
                'origin': window.location.origin // Güvenlik için
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange,
                'onError': onPlayerError
            }
        });

    }

      function onPlayerReady(event) {
        updateVideoDuration(event.target.getDuration());
        updateInterval = setInterval(updateVideoProgress, 1000);

        // Player hazır olduğunda oynatma kontrollerini etkinleştir
        document.getElementById('playBtn').addEventListener('click', togglePlay);
        document.getElementById('pauseBtn').addEventListener('click', togglePlay);
    }


  function onPlayerStateChange(event) {
        switch(event.data) {
            case YT.PlayerState.PLAYING:
                isPlaying = true;
                videoEnded = false;
                document.getElementById('pauseBtn').classList.remove('hidden');
                document.getElementById('playBtn').classList.add('hidden');
                break;
            case YT.PlayerState.PAUSED:
                isPlaying = false;
                document.getElementById('playBtn').classList.remove('hidden');
                document.getElementById('pauseBtn').classList.add('hidden');
                break;
            case YT.PlayerState.ENDED:
                videoEnded = true;
                isPlaying = false;
                document.getElementById('playBtn').classList.remove('hidden');
                document.getElementById('pauseBtn').classList.add('hidden');

                // Videoyu başa sar
                setTimeout(() => {
                    player.seekTo(0);
                }, 1000);
                break;
            case YT.PlayerState.BUFFERING:
            case YT.PlayerState.CUED:
                break;
        }
    }
       function onPlayerError(event) {
        console.error('YouTube Player Error:', event.data);
        // Hata durumunda bir şeyler yapabilirsiniz
    }

    function togglePlay() {
        if (!player) return;

        if (videoEnded) {
            player.seekTo(0);
            videoEnded = false;
        }

        if (isPlaying) {
            player.pauseVideo();
        } else {
            player.playVideo();
        }
    }


   function updateVideoProgress() {
        if (!player || typeof player.getCurrentTime !== 'function') return;

        const currentTime = player.getCurrentTime();
        const duration = player.getDuration();

        if (duration > 0) {
            const progressPercent = (currentTime / duration) * 100;
            document.getElementById('progressBar').style.width = `${progressPercent}%`;
            document.getElementById('videoSeek').value = progressPercent;

            document.getElementById('videoCurrentTime').textContent = formatTime(currentTime);
            document.getElementById('currentTime').textContent = formatTime(currentTime);
        }
    }
  function updateVideoDuration(duration) {
        document.getElementById('videoDuration').textContent = formatTime(duration);
        document.getElementById('duration').textContent = formatTime(duration);
    }

  function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

     function seekVideo(event) {
        if (!player) return;

        const seekPercent = event.target.value;
        const duration = player.getDuration();
        const seekTime = (duration * seekPercent) / 100;

        player.seekTo(seekTime, true);

        // Eğer video bitmişse ve kullanıcı seek yaparsa, bitiş durumunu sıfırla
        if (videoEnded) {
            videoEnded = false;
        }
    }

  function toggleFullscreen() {
        const elem = document.getElementById('customPlayer');

        if (!document.fullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
     function openLessonModal() {
        document.getElementById('lessonModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
            loadYouTubeAPI();
        } else if (!player) {
            initializeYouTubePlayer();
        }
    }

  function closeLessonModal() {
        document.getElementById('lessonModal').classList.add('hidden');
        document.body.style.overflow = '';

        if (player) {
            player.destroy();
            player = null;
        }
        clearInterval(updateInterval);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('videoSeek').addEventListener('input', seekVideo);

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLessonModal();
            }
        });

        // Player üzerindeki tıklamaları yakala (önerilen videolara gitmeyi engelle)
        document.getElementById('player')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    });

    // YouTube iframe API'sinin yüklenmesini sağla
    if (!window.YT) {
        loadYouTubeAPI();
    }
</script>
