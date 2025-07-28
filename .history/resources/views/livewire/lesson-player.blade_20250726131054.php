<div x-data="{ isOpen: @entangle('isOpen') }">
    <!-- Ders Listesi -->
    <div class="space-y-3">
        @foreach($lessons as $lesson)
            <button
                wire:click="openLessonModal({{ $lesson->id }})"
                class="w-full text-left p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-medium text-gray-900">{{ $loop->iteration }}. {{ $lesson->title }}</span>
                        <p class="text-sm text-gray-500 mt-1">{{ $lesson->duration_minutes }} dakika</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </button>
        @endforeach
    </div>

    <!-- Tam Ekran Modal -->
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 bg-white overflow-hidden">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $currentLesson->title ?? 'Ders Yükleniyor' }}
                </h2>
                <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Ders İçeriği -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="prose max-w-none">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ders İçeriği</h3>
                    {!! nl2br(e($currentLesson->description ?? 'Bu dersin içeriği henüz eklenmemiş.')) !!}
                </div>
            </div>

            <!-- Navigasyon -->
            <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                <button wire:click="prevLesson"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
                        @if(!$currentLesson || $currentLesson->order == 1) disabled @endif>
                    ← Önceki Ders
                </button>

                <div class="flex-1 mx-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-center mt-1 text-gray-600">Tamamlanma: {{ $progress }}%</p>
                </div>

                <button wire:click="nextLesson"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        @if(!$currentLesson || $currentLesson->order == $lessons->count()) disabled @endif>
                    Sonraki Ders →
                </button>
            </div>
        </div>
    </div>
</div>
