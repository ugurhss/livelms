<div class="space-y-6">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveQuiz" class="bg-white shadow-md rounded-lg p-6">
        @csrf

        <!-- Course Selection (hidden if courseId is provided) -->
        @if($courseId)
            <input type="hidden" wire:model="course_id" value="{{ $courseId }}">
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <p class="font-semibold text-blue-800">Kurs: {{ \App\Models\Course::find($courseId)->title ?? '' }}</p>
            </div>
        @else
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Kurs Seçin</label>
                <select wire:model="course_id" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Kurs Seçin</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        @endif

        <!-- Basic Quiz Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Quiz Başlığı</label>
                <input wire:model="title" type="text" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Geçme Notu (%)</label>
                <input wire:model="passing_score" type="number" min="0" max="100" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('passing_score') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Açıklama</label>
            <textarea wire:model="description" rows="3" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Dates and Publishing -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Başlangıç Tarihi</label>
                <input wire:model="start_date" type="datetime-local" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('start_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Bitiş Tarihi</label>
                <input wire:model="end_date" type="datetime-local" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('end_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input wire:model="is_published" type="checkbox" id="is_published" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_published" class="ml-2 block text-gray-700 font-medium">Yayınla</label>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Sorular</h3>

            @foreach($questions as $index => $question)
                <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-700">Soru #{{ $index + 1 }}</h4>
                        <div class="space-x-2">
                            <button type="button" wire:click="editQuestion({{ $index }})" class="text-blue-600 hover:text-blue-800">
                                Düzenle
                            </button>
                            <button type="button" wire:click="removeQuestion({{ $index }})" class="text-red-600 hover:text-red-800">
                                Sil
                            </button>
                        </div>
                    </div>

                    <p class="mb-2 font-medium">{{ $question['question_text'] }}</p>
                    <div class="text-sm text-gray-600 mb-3">
                        <span class="mr-3">Tip: {{ $question['question_type'] }}</span>
                        <span>Puan: {{ $question['points'] }}</span>
                    </div>

                    <div class="ml-4 space-y-2">
                        @foreach($question['answers'] as $answerIndex => $answer)
                            <div class="flex items-center">
                                @if(in_array($question['question_type'], ['multiple_choice', 'true_false']))
                                    <input type="{{ $question['question_type'] === 'multiple_choice' ? 'checkbox' : 'radio' }}"
                                           @checked($answer['is_correct'])
                                           disabled
                                           class="h-4 w-4 text-blue-600 mr-2">
                                @endif
                                <span @class(['font-medium' => $answer['is_correct'], 'text-green-600' => $answer['is_correct']])>
                                    {{ $answer['answer_text'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Add Question Form -->
            <div class="p-4 border rounded-lg bg-white">
                <h4 class="font-medium text-gray-700 mb-3">{{ $activeQuestionIndex !== null ? 'Soruyu Düzenle' : 'Yeni Soru Ekle' }}</h4>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-1">Soru Metni</label>
                    <textarea wire:model="currentQuestion.question_text" rows="3" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('currentQuestion.question_text') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Soru Tipi</label>
                        <select wire:model="currentQuestion.question_type" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="multiple_choice">Çoktan Seçmeli</option>
                            <option value="true_false">Doğru/Yanlış</option>
                            <option value="short_answer">Kısa Cevap</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Puan</label>
                        <input wire:model="currentQuestion.points" type="number" min="1" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('currentQuestion.points') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Answers -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Cevaplar</label>

                    @foreach($currentQuestion['answers'] as $answerIndex => $answer)
                        <div class="flex items-center mb-2">
                            @if(in_array($currentQuestion['question_type'], ['multiple_choice', 'true_false']))
                                <input type="{{ $currentQuestion['question_type'] === 'multiple_choice' ? 'checkbox' : 'radio' }}"
                                       wire:model="currentQuestion.answers.{{ $answerIndex }}.is_correct"
                                       class="h-4 w-4 text-blue-600 mr-2">
                            @endif

                            <input type="text"
                                   wire:model="currentQuestion.answers.{{ $answerIndex }}.answer_text"
                                   class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2">

                            <button type="button" wire:click="removeAnswer({{ $answerIndex }})" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        @error('currentQuestion.answers.'.$answerIndex.'.answer_text') <span class="text-red-500 text-sm block mb-2">{{ $message }}</span> @enderror
                    @endforeach

                    <button type="button" wire:click="addAnswer" class="mt-2 text-blue-600 hover:text-blue-800 flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Cevap Ekle
                    </button>
                </div>

                <div class="flex justify-end">
                    @if($activeQuestionIndex !== null)
                        <button type="button" wire:click="updateQuestion" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                            Soruyu Güncelle
                        </button>
                    @else
                        <button type="button" wire:click="addQuestion" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                            Soruyu Ekle
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                {{ $isEditing ? 'Güncelle' : 'Oluştur' }}
            </button>
        </div>
    </form>
</div>
