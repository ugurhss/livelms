<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Yeni Quiz Oluştur') }}
        </h2>
    </x-slot>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form wire:submit.prevent="save">
            <!-- Başlık -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">
                    {{ __('Quiz Başlığı') }} *
                </label>
                <input wire:model="title" type="text" id="title"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Açıklama -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">
                    {{ __('Açıklama') }}
                </label>
                <textarea wire:model="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <!-- Zaman Limiti -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="time_limit" class="block text-sm font-medium text-gray-700">
                        {{ __('Zaman Limiti (dakika)') }}
                    </label>
                    <input wire:model="time_limit" type="number" id="time_limit" min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="passing_score" class="block text-sm font-medium text-gray-700">
                        {{ __('Geçme Notu (%)') }} *
                    </label>
                    <input wire:model="passing_score" type="number" id="passing_score" min="0" max="100"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('passing_score') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Tarih Aralığı -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">
                        {{ __('Başlangıç Tarihi') }}
                    </label>
                    <input wire:model="start_date" type="datetime-local" id="start_date"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">
                        {{ __('Bitiş Tarihi') }}
                    </label>
                    <input wire:model="end_date" type="datetime-local" id="end_date"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Yayın Durumu -->
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input wire:model="is_published" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Herkese açık olarak yayınla') }}</span>
                </label>
            </div>

            <!-- Butonlar -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('courses.quizzes.index', $courseId) }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    {{ __('İptal') }}
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    {{ __('Kaydet') }}
                </button>
            </div>
        </form>
    </div>
</div>
