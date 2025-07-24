<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Yeni Quiz Oluştur') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        @if ($errors->has('save_error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                <p>{{ $errors->first('save_error') }}</p>
            </div>
        @endif

        <form wire:submit.prevent="save">
            <!-- Form alanları aynı şekilde kalacak -->

            <!-- Butonlar -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('courses.quizzes.index', $courseId) }}"
                   wire:navigate
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    {{ __('İptal') }}
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <span wire:loading.remove>{{ __('Kaydet') }}</span>
                    <span wire:loading>
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Kaydediliyor...') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
