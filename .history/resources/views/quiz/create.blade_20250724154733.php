<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Yeni Quiz Oluştur</h1>
                <p class="text-lg text-gray-500 mt-2">Kurs için yeni bir quiz oluşturun</p>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('quizzes.store') }}" method="POST">
            @csrf

            <!-- Quiz Information Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Quiz Bilgileri
                    </h3>
                </div>

                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Course Selection -->
                        <div class="sm:col-span-6">
                            <label for="course_id" class="block text-sm font-medium text-gray-700">Kurs Seçin</label>
                            <select id="course_id" name="course_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                                <option value="">Kurs seçin</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quiz Title -->
                        <div class="sm:col-span-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">Quiz Başlığı</label>
                            <input type="text" name="title" id="title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Açıklama</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                        </div>

                        <!-- Settings Grid -->
                        <div class="sm:col-span-2">
                            <label for="time_limit" class="block text-sm font-medium text-gray-700">Süre Limiti (dakika)</label>
                            <input type="number" name="time_limit" id="time_limit" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="passing_score" class="block text-sm font-medium text-gray-700">Geçme Notu (%)</label>
                            <input type="number" name="passing_score" id="passing_score" min="0" max="100" value="70" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="is_published" class="block text-sm font-medium text-gray-700">Durum</label>
                            <select id="is_published" name="is_published" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="0">Taslak</option>
                                <option value="1">Yayında</option>
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Sorular
                    </h3>
                    <button type="button" id="add-question" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Yeni Soru Ekle
                    </button>
                </div>

                <div class="px-6 py-4">
                    <div id="questions-container" class="space-y-4">
                        <!-- Questions will be added here dynamically -->
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3">
                {{-- <a href="{{ route('quizzes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"> --}}
                    İptal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quiz'i Kaydet
                </button>
            </div>
        </form>
    </div>

    <!-- Question Template (Hidden) -->
    <template id="question-template">
        <div class="question-item bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <div class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-medium text-gray-900 flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-3 question-number">
                            1
                        </span>
                        Soru
                    </h4>
                    <button type="button" class="remove-question inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Question Text -->
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">Soru Metni</label>
                        <textarea name="questions[0][question_text]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md question-text" required></textarea>
                    </div>

                    <!-- Question Type and Points -->
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Soru Tipi</label>
                        <select name="questions[0][question_type]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md question-type" required>
                            <option value="multiple_choice">Çoktan Seçmeli</option>
                            <option value="true_false">Doğru/Yanlış</option>
                            <option value="short_answer">Kısa Cevap</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Puan</label>
                        <input type="number" name="questions[0][points]" min="1" value="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md question-points" required>
                    </div>

                    <!-- Answers Section -->
                    <div class="sm:col-span-6 answers-container">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Cevaplar</label>
                            <button type="button" class="add-answer inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Cevap Ekle
                            </button>
                        </div>

                        <div class="mt-2 answers-list space-y-2">
                            <!-- Answers will be added here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Answer Template (Hidden) -->
    <template id="answer-template">
        <div class="answer-item flex items-center space-x-2">
            <div class="flex-grow">
                <input type="text" name="questions[0][answers][0][answer_text]" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
            </div>
            <div class="flex-shrink-0 flex items-center">
                <input id="correct-0-0" name="questions[0][answers][0][is_correct]" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded is-correct" value="1">
                <label for="correct-0-0" class="ml-2 block text-sm text-gray-700">Doğru</label>
            </div>
            <button type="button" class="remove-answer inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </template>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let questionCounter = 0;
        let answerCounters = {};

        // Add new question
        document.getElementById('add-question').addEventListener('click', function() {
            addNewQuestion();
        });

        function addNewQuestion() {
            const container = document.getElementById('questions-container');
            const template = document.getElementById('question-template').content.cloneNode(true);
            const questionItem = template.querySelector('.question-item');

            questionCounter++;
            answerCounters[questionCounter] = 0;

            // Update question number
            questionItem.querySelector('.question-number').textContent = questionCounter;

            // Update names with current index
            const questionIndex = questionCounter - 1;

            // Question text
            const questionText = questionItem.querySelector('.question-text');
            questionText.name = `questions[${questionIndex}][question_text]`;

            // Question type
            const questionType = questionItem.querySelector('.question-type');
            questionType.name = `questions[${questionIndex}][question_type]`;
            questionType.addEventListener('change', function() {
                updateAnswerFields(questionItem, this.value);
            });

            // Points
            const questionPoints = questionItem.querySelector('.question-points');
            questionPoints.name = `questions[${questionIndex}][points]`;

            // Remove button
            questionItem.querySelector('.remove-question').addEventListener('click', function() {
                questionItem.remove();
                updateQuestionNumbers();
            });

            // Add answer button
            const addAnswerBtn = questionItem.querySelector('.add-answer');
            addAnswerBtn.addEventListener('click', function() {
                addNewAnswer(questionItem, questionIndex);
            });

            container.appendChild(questionItem);

            // Add first answer
            addNewAnswer(questionItem, questionIndex);

            // Update question numbers
            updateQuestionNumbers();
        }

        function addNewAnswer(questionItem, questionIndex) {
            const answersList = questionItem.querySelector('.answers-list');
            const template = document.getElementById('answer-template').content.cloneNode(true);
            const answerItem = template.querySelector('.answer-item');

            answerCounters[questionIndex + 1]++;
            const answerIndex = answerCounters[questionIndex + 1] - 1;

            // Answer text
            const answerText = answerItem.querySelector('input[type="text"]');
            answerText.name = `questions[${questionIndex}][answers][${answerIndex}][answer_text]`;

            // Correct answer checkbox
            const isCorrect = answerItem.querySelector('.is-correct');
            isCorrect.name = `questions[${questionIndex}][answers][${answerIndex}][is_correct]`;
            isCorrect.id = `correct-${questionIndex}-${answerIndex}`;

            // Update label for attribute
            const label = answerItem.querySelector('label');
            label.htmlFor = `correct-${questionIndex}-${answerIndex}`;

            // Remove button
            answerItem.querySelector('.remove-answer').addEventListener('click', function() {
                answerItem.remove();
            });

            answersList.appendChild(answerItem);

            // Update answer fields based on question type
            const questionType = questionItem.querySelector('.question-type').value;
            updateAnswerFieldsForItem(answerItem, questionType);
        }

        function updateAnswerFields(questionItem, questionType) {
            const answers = questionItem.querySelectorAll('.answer-item');
            answers.forEach(answer => {
                updateAnswerFieldsForItem(answer, questionType);
            });
        }

        function updateAnswerFieldsForItem(answerItem, questionType) {
            const isCorrectCheckbox = answerItem.querySelector('.is-correct');
            const isCorrectContainer = isCorrectCheckbox.parentElement;

            if (questionType === 'short_answer') {
                isCorrectContainer.style.display = 'none';
                isCorrectCheckbox.checked = true;
            } else {
                isCorrectContainer.style.display = 'flex';
            }

            // Special handling for true/false questions
            if (questionType === 'true_false') {
                const answerText = answerItem.querySelector('input[type="text"]');
                if (answerCounters[questionCounter] <= 2) {
                    answerText.readOnly = true;
                    answerText.value = answerCounters[questionCounter] === 1 ? 'Doğru' : 'Yanlış';
                }
            }
        }

        function updateQuestionNumbers() {
            const questions = document.querySelectorAll('.question-item');
            questions.forEach((question, index) => {
                question.querySelector('.question-number').textContent = index + 1;
            });
        }

        // Add first question on load
        addNewQuestion();
    });
    </script>

</x-layouts.app>
