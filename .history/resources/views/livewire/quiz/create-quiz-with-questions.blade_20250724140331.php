<div>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ $course->name }} - Yeni Quiz Oluştur</h1>

        <form wire:submit.prevent="saveQuiz">
            <!-- Quiz Bilgileri -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Quiz Bilgileri</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Quiz Başlığı</label>
                        <input type="text" wire:model="quiz.title" class="w-full px-4 py-2 border rounded-lg">
                        @error('quiz.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Geçme Notu (%)</label>
                        <input type="number" wire:model="quiz.passing_score" min="0" max="100" class="w-full px-4 py-2 border rounded-lg">
                        @error('quiz.passing_score') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700 mb-2">Açıklama</label>
                    <textarea wire:model="quiz.description" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Başlangıç Tarihi</label>
                        <input type="datetime-local" wire:model="quiz.start_date" class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Bitiş Tarihi</label>
                        <input type="datetime-local" wire:model="quiz.end_date" class="w-full px-4 py-2 border rounded-lg">
                        @error('quiz.end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="quiz.is_published" class="form-checkbox">
                        <span class="ml-2">Yayınla</span>
                    </label>
                </div>
            </div>

            <!-- Sorular -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Sorular</h2>

                @foreach($questions as $questionIndex => $question)
                    <div class="border rounded-lg p-4 mb-4" wire:key="question-{{ $questionIndex }}">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-medium">Soru #{{ $questionIndex + 1 }}</h3>
                            <button type="button" wire:click="removeQuestion({{ $questionIndex }})" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Soru Metni</label>
                            <textarea wire:model="questions.{{ $questionIndex }}.question_text" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                            @error('questions.'.$questionIndex.'.question_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Soru Tipi</label>
                                <select wire:model="questions.{{ $questionIndex }}.question_type" class="w-full px-4 py-2 border rounded-lg">
                                    <option value="multiple_choice">Çoktan Seçmeli</option>
                                    <option value="true_false">Doğru/Yanlış</option>
                                    <option value="short_answer">Kısa Cevap</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">Puan</label>
                                <input type="number" wire:model="questions.{{ $questionIndex }}.points" min="1" class="w-full px-4 py-2 border rounded-lg">
                                @error('questions.'.$questionIndex.'.points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Cevaplar -->
                        <div class="mt-4">
                            <h4 class="font-medium mb-2">Cevaplar</h4>

                            @foreach($question['answers'] as $answerIndex => $answer)
                                <div class="flex items-center mb-2" wire:key="answer-{{ $questionIndex }}-{{ $answerIndex }}">
                                    <input type="{{ $question['question_type'] === 'multiple_choice' ? 'radio' : 'checkbox' }}"
                                           name="correct-answer-{{ $questionIndex }}"
                                           wire:model="questions.{{ $questionIndex }}.answers.{{ $answerIndex }}.is_correct"
                                           class="mr-2"
                                           value="true">

                                    <input type="text"
                                           wire:model="questions.{{ $questionIndex }}.answers.{{ $answerIndex }}.answer_text"
                                           class="flex-1 px-3 py-1 border rounded-lg mr-2">

                                    <button type="button" wire:click="removeAnswer({{ $questionIndex }}, {{ $answerIndex }})" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                @error('questions.'.$questionIndex.'.answers.'.$answerIndex.'.answer_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @endforeach

                            <button type="button" wire:click="addAnswer({{ $questionIndex }})" class="mt-2 text-blue-500 hover:text-blue-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Cevap Ekle
                            </button>
                        </div>
                    </div>
                @endforeach

                <button type="button" wire:click="addQuestion" class="mt-4 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Yeni Soru Ekle
                </button>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    Quiz'i Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
