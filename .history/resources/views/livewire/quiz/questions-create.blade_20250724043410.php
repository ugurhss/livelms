    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Yeni Soru Ekle: {{ $quiz->title }}</h1>
            <a href="{{ route('courses.quizzes.show', [$courseId, $quiz->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quiz'e Dön
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Soru Bilgileri
                </h2>
            </div>

            <div class="p-6">
                <form wire:submit.prevent="store">
                    <!-- Question Text -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Soru Metni <span class="text-red-500">*</span></label>
                        <textarea wire:model="question_text"
                                  class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('question_text') border-red-500 @enderror"
                                  rows="3" required></textarea>
                        @error('question_text')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Question Type -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Soru Tipi <span class="text-red-500">*</span></label>
                        <select wire:model="question_type"
                                class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('question_type') border-red-500 @enderror"
                                required>
                            <option value="">Seçiniz</option>
                            <option value="multiple_choice">Çoktan Seçmeli</option>
                            <option value="true_false">Doğru/Yanlış</option>
                            <option value="short_answer">Kısa Cevap</option>
                        </select>
                        @error('question_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Points -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Puan <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="points"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('points') border-red-500 @enderror"
                               min="1" required>
                        @error('points')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Answers Section -->
                    <div class="mb-6">
                        <h5 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cevaplar
                        </h5>

                        @foreach($answers as $index => $answer)
                        <div class="mb-4">
                            <div class="flex">
                                <input type="text"
                                       wire:model="answers.{{ $index }}.answer_text"
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('answers.'.$index.'.answer_text') border-red-500 @enderror"
                                       required
                                       @if($question_type === 'true_false') readonly @endif>
                                <div class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-gray-50 text-sm font-medium text-gray-700">
                                    @if($question_type !== 'short_answer')
                                        <input type="{{ $question_type === 'multiple_choice' ? 'radio' : 'radio' }}"
                                               @if($answer['is_correct']) checked @endif
                                               wire:click="setCorrectAnswer({{ $index }})"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2">Doğru Cevap</span>
                                    @else
                                        <span class="text-green-600">Doğru Cevap</span>
                                        <input type="hidden" wire:model="answers.{{ $index }}.is_correct" value="1">
                                    @endif
                                </div>
                                @if($question_type === 'multiple_choice' && $index > 3 || ($question_type === 'short_answer' && $index > 0))
                                    <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-white text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            wire:click="removeAnswer({{ $index }})">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            @error('answers.'.$index.'.answer_text')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endforeach

                        @if($question_type === 'multiple_choice')
                            <button type="button"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    wire:click="addAnswer">
                                <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Cevap Ekle
                            </button>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('courses.quizzes.show', [$courseId, $quiz->id]) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            İptal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span wire:loading.remove wire:target="store">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v10a7 7 0 0014 0V7M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2" />
                                </svg>
                                Kaydet
                            </span>
                            <span wire:loading wire:target="store">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Kaydediliyor...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
