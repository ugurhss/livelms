<div x-data="{ open: @entangle('showModal') }">
    <!-- Ders Listesi -->
    <div class="space-y-3 mb-8">
        @foreach($lessons as $lesson)
            <button
                wire:click="openModal({{ $lesson->id }})"
                @click="open = true"
                wire:key="lesson-{{ $lesson->id }}"
                class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-xs"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $loop->iteration }}. {{ $lesson->title }}</h3>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $lesson->duration_minutes }} dakika
                            </span>
                            @if($lesson->is_free)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                    Ücretsiz
                                </span>
                            @endif
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </button>
        @endforeach
    </div>

    <!-- Modal -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-[9999] bg-white flex flex-col">

        <div class="flex flex-1 overflow-hidden">
            <!-- Ana İçerik -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Video Player -->
                <div class="flex-1 bg-black flex items-center justify-center relative">
                    @if($currentLesson && $currentLesson->video_url)
                        @if($currentLesson->video_type === 'youtube' && $currentLesson->video_id)
                            <iframe
                                src="https://www.youtube.com/embed/{{ $currentLesson->video_id }}?autoplay=1"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        @else
                            <video controls class="w-full h-full">
                                <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                Tarayıcınız video oynatmayı desteklemiyor.
                            </video>
                        @endif
                    @else
                        <div class="text-center p-8 text-white">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-xl font-medium mb-2">Video Bulunamadı</h3>
                            <p class="text-gray-300">
                                @if(!$currentLesson)
                                    Ders bilgisi yüklenemedi
                                @elseif(empty($currentLesson->video_url))
                                    Bu dersin videosu bulunmamaktadır
                                @elseif($currentLesson->video_type === 'youtube' && empty($currentLesson->video_id))
                                    YouTube video ID çıkarılamadı
                                @else
                                    Video yüklenirken bir hata oluştu
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Ders Detayları -->
                <div class="border-t border-gray-200 bg-white">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">
                            {{ $currentLesson->title ?? 'Ders başlığı bulunamadı' }}
                        </h2>

                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($currentLesson->description ?? 'Ders açıklaması bulunmamaktadır.')) !!}
                        </div>

                        <div class="mt-6 flex items-center space-x-4 text-sm text-gray-500">
                            @if($currentLesson)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $currentLesson->duration_minutes }} dakika
                                </span>
                                <span>•</span>
                                <span>
                                    @if($currentLesson->video_url)
                                        @if($currentLesson->video_type === 'youtube')
                                            YouTube Video
                                        @else
                                            Direkt Video
                                        @endif
                                    @else
                                        Video Yok
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kapatma Butonu -->
        <div class="border-t border-gray-200 bg-gray-50 p-4 flex justify-end">
            <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                Kapat
            </button>
        </div>
    </div>
</div>
