<div>
    <div class="container mx-auto px-4 py-8">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <h1 class="text-2xl font-bold mb-6">{{ $isEditing ? 'Quiz Düzenle' : 'Yeni Quiz Oluştur' }}</h1>

<form wire:submit.prevent="saveQuiz">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="course_id">
                        Kurs Seçin
                    </label>
                    <select wire:model="course_id" id="course_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Kurs Seçin</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        Quiz Başlığı
                    </label>
                    <input wire:model="title" type="text" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Açıklama
                    </label>
                    <textarea wire:model="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="passing_score">
                            Geçme Notu (%)
                        </label>
                        <input wire:model="passing_score" type="number" min="0" max="100" id="passing_score" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('passing_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                            Başlangıç Tarihi
                        </label>
                        <input wire:model="start_date" type="datetime-local" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                            Bitiş Tarihi
                        </label>
                        <input wire:model="end_date" type="datetime-local" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input wire:model="is_published" type="checkbox" class="form-checkbox">
                        <span class="ml-2 text-gray-700 text-sm font-bold">Yayınla</span>
                    </label>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-xl font-bold mb-4">Sorular</h2>

                @foreach($questions as $index => $question)
                    <div class="mb-6 p-4 border rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold">Soru #{{ $index + 1 }}</h3>
                            <div>
                                <button type="button" wire:click="editQuestion({{ $index }})" class="text-blue-500 hover:text-blue-700 mr-2">Düzenle</button>
                                <button type="button" wire:click="removeQuestion({{ $index }})" class="text-red-500 hover:text-red-700">Sil</button>
                            </div>
                        </div>
                        <p class="mb-2">{{ $question['question_text'] }}</p>
                        <p class="text-sm text-gray-600 mb-2">Tip: {{ $question['question_type'] }} | Puan: {{ $question['points'] }}</p>

                        <div class="ml-4 mt-2">
                            @foreach($question['answers'] as $answerIndex => $answer)
                                <div class="flex items-center mb-1">
                                    @if($question['question_type'] === 'multiple_choice' || $question['question_type'] === 'true_false')
                                        <input type="{{ $question['question_type'] === 'multiple_choice' ? 'checkbox' : 'radio' }}"
                                               @if($answer['is_correct']) checked @endif disabled
                                               class="mr-2">
                                    @endif
                                    <span @if($answer['is_correct']) class="font-bold text-green-600" @endif>
                                        {{ $answer['answer_text'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Add Question Form -->
                <div class="mt-6 p-4 border rounded-lg">
                    <h3 class="font-bold mb-4">Yeni Soru Ekle</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Soru Metni</label>
                        <textarea wire:model="currentQuestion.question_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                        @error('currentQuestion.question_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Soru Tipi</label>
                            <select wire:model="currentQuestion.question_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="multiple_choice">Çoktan Seçmeli</option>
                                <option value="true_false">Doğru/Yanlış</option>
                                <option value="short_answer">Kısa Cevap</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Puan</label>
                            <input wire:model="currentQuestion.points" type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('currentQuestion.points') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Answers -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cevaplar</label>

                        @foreach($currentQuestion['answers'] as $answerIndex => $answer)
                            <div class="flex items-center mb-2">
                                @if($currentQuestion['question_type'] === 'multiple_choice' || $currentQuestion['question_type'] === 'true_false')
                                    <input type="{{ $currentQuestion['question_type'] === 'multiple_choice' ? 'checkbox' : 'radio' }}"
                                           wire:model="currentQuestion.answers.{{ $answerIndex }}.is_correct"
                                           class="mr-2">
                                @endif

                                <input type="text"
                                       wire:model="currentQuestion.answers.{{ $answerIndex }}.answer_text"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">

                                <button type="button" wire:click="removeAnswer({{ $answerIndex }})" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            @error('currentQuestion.answers.'.$answerIndex.'.answer_text') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
                        @endforeach

                        <button type="button" wire:click="addAnswer" class="mt-2 text-blue-500 hover:text-blue-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Cevap Ekle
                        </button>
                    </div>

                    <div class="flex justify-end">
                        @if($activeQuestionIndex !== null)
                            <button type="button" wire:click="updateQuestion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Soruyu Güncelle
                            </button>
                        @else
                            <button type="button" wire:click="addQuestion" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Soruyu Ekle
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Quiz'i Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
