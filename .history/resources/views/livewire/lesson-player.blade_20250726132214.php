<div x-data="{ open: @entangle('showModal') }">
    <!-- Ders Listesi -->
    <div class="space-y-3">
        @foreach($lessons as $lesson)
            <button
                wire:click="openModal({{ $lesson->id }})"
                @click="open = true"
                class="w-full text-left p-4 border rounded-lg hover:bg-gray-50 transition-colors"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-medium">{{ $lesson->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $lesson->duration_minutes }} dakika</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
         class="fixed inset-0 z-[9999] bg-white p-6 overflow-y-auto">

        <div class="flex flex-col h-full">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">{{ $currentLesson->title ?? '' }}</h2>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto">
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $currentLesson->description ?? 'Ders içeriği bulunmamaktadır.' }}</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">{{ $currentLesson->duration_minutes ?? 0 }} dakika</span>
                    <button @click="open = false" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
    [x-cloak] { display: none !important; }

    .prose {
        max-width: 100%;
        line-height: 1.6;
    }

    .prose p {
        margin-bottom: 1rem;
    }

    /* Modal için özel stiller */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
    }

    .modal-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        background: white;
    }
</style>
