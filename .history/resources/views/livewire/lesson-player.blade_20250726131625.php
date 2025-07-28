<div x-data="{ isOpen: @entangle('isOpen') }">
    <!-- Ders Listesi -->
    <div class="space-y-3 mb-6">
        @foreach($lessons as $lesson)
        <button
            wire:click="openLessonModal({{ $lesson->id }})"
            @click="isOpen = true"
            class="w-full text-left p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors flex justify-between items-center"
        >
            <div>
                <span class="font-medium text-gray-900">{{ $loop->iteration }}. {{ $lesson->title }}</span>
                <p class="text-sm text-gray-500 mt-1">{{ $lesson->duration_minutes }} dakika</p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        @endforeach
    </div>

    <!-- Modal -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-white p-4">

        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">
                    {{ $currentLesson->title ?? 'Ders Yükleniyor' }}
                </h2>
                <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto">
                <div class="prose max-w-none">
                    <h3 class="text-lg font-medium mb-4">Ders İçeriği</h3>
                    <p>{{ $currentLesson->description ?? 'Ders açıklaması bulunmamaktadır.' }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <button wire:click="prevLesson"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                            @if(!$currentLesson || $currentLesson->order == 1) disabled @endif>
                        Önceki
                    </button>

                    <span class="text-sm text-gray-600">
                        {{ $currentLesson->duration_minutes ?? 0 }} dakika
                    </span>

                    <button wire:click="nextLesson"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            @if(!$currentLesson || $currentLesson->order == $lessons->count()) disabled @endif>
                        Sonraki
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Layout dosyanızın sonuna ekleyin -->
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('modal-opened', () => {
            // Modal açıldığında sayfayı en üste çek
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Eğer modal hala görünmüyorsa z-index'i kontrol et
            const modal = document.querySelector('[x-show="isOpen"]');
            if (modal) {
                modal.style.zIndex = '9999';
            }
        });
    });
</script>
