<x-layouts.app>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Yeni Quiz Oluştur</h1>
        <p class="mt-2 text-lg text-gray-600">Kurs için yeni bir değerlendirme oluşturun</p>
    </div>

    <!-- Form -->
    <form action="{{ route('quizzes.store') }}" method="POST" id="quiz-form">
        @csrf

        <!-- Quiz Info Card -->
        <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Temel Bilgiler
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Course Selection -->
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Kurs Seçimi</label>
                        <select id="course_id" name="course_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">-- Kurs seçin --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quiz Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Quiz Başlığı</label>
                        <input type="text" id="title" name="title" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                        <textarea id="description" name="description" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>

                    <!-- Settings -->
                    <div>
                        <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-1">Süre Limiti (dakika)</label>
                        <input type="number" id="time_limit" name="time_limit" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-1">Geçme Notu (%)</label>
                        <input type="number" id="passing_score" name="passing_score" min="0" max="100" value="70" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="is_published" class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                        <select id="is_published" name="is_published" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="0">Taslak</option>
                            <option value="1">Yayında</option>
                        </select>
                    </div>

                    <!-- Dates -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Tarihi</label>
                        <input type="datetime-local" id="start_date" name="start_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Bitiş Tarihi</label>
                        <input type="datetime-local" id="end_date" name="end_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Sorular
                </h3>
                <button type="button" id="add-question-btn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Yeni Soru Ekle
                </button>
            </div>
            <div class="p-6">
                <div id="questions-container" class="space-y-6">
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
    <div class="question-item border rounded-lg overflow-hidden bg-white">
        <div class="px-4 py-3 bg-gray-50 border-b flex justify-between items-center">
            <h4 class="font-medium text-gray-900">
                <span class="question-number">1</span>. Soru
            </h4>
            <button type="button" class="remove-question text-red-600 hover:text-red-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Soru Metni</label>
                <textarea name="questions[0][question_text]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Soru Tipi</label>
                    <select name="questions[0][question_type]" class="question-type w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="multiple_choice">Çoktan Seçmeli</option>
                        <option value="true_false">Doğru/Yanlış</option>
                        <option value="short_answer">Kısa Cevap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Puan</label>
                    <input type="number" name="questions[0][points]" min="1" value="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>

            <div class="answers-section">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Cevaplar</label>
                    <button type="button" class="add-answer inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Cevap Ekle
                    </button>
                </div>
                <div class="answers-list space-y-2">
                    <!-- Answers will be added here -->
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Answer Template (Hidden) -->
<template id="answer-template">
    <div class="answer-item flex items-center gap-3">
        <input type="text" name="questions[0][answers][0][answer_text]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        <div class="flex items-center">
            <input type="checkbox" name="questions[0][answers][0][is_correct]" value="1" class="is-correct h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <span class="ml-2 text-sm text-gray-700">Doğru</span>
        </div>
        <button type="button" class="remove-answer text-red-600 hover:text-red-800">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionsContainer = document.getElementById('questions-container');
    const addQuestionBtn = document.getElementById('add-question-btn');
    let questionCount = 0;

    // Add first question automatically
    addQuestion();

    // Add question button click event
    addQuestionBtn.addEventListener('click', addQuestion);

    // Event delegation for dynamic elements
    document.addEventListener('click', function(e) {
        // Remove question
        if (e.target.closest('.remove-question')) {
            e.target.closest('.question-item').remove();
            updateQuestionNumbers();
        }

        // Add answer
        if (e.target.closest('.add-answer')) {
            const questionItem = e.target.closest('.question-item');
            const questionIndex = getQuestionIndex(questionItem);
            addAnswer(questionItem, questionIndex);
        }

        // Remove answer
        if (e.target.closest('.remove-answer')) {
            e.target.closest('.answer-item').remove();
        }
    });

    // Question type change event (using event delegation)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('question-type')) {
            updateAnswerFields(e.target.closest('.question-item'));
        }
    });

    // Add new question
    function addQuestion() {
        questionCount++;
        const template = document.getElementById('question-template').content.cloneNode(true);
        const questionItem = template.querySelector('.question-item');
        const questionIndex = questionCount - 1;

        // Update all question fields with correct index
        questionItem.querySelectorAll('[name^="questions[0]"]').forEach(el => {
            el.name = el.name.replace('questions[0]', `questions[${questionIndex}]`);
        });

        questionsContainer.appendChild(questionItem);
        updateQuestionNumbers();

        // Add first answer automatically
        addAnswer(questionItem, questionIndex);
    }

    // Add new answer to a question
    function addAnswer(questionItem, questionIndex) {
        const answersList = questionItem.querySelector('.answers-list');
        const answerCount = answersList.querySelectorAll('.answer-item').length;
        const template = document.getElementById('answer-template').content.cloneNode(true);
        const answerItem = template.querySelector('.answer-item');

        // Update answer fields with correct indexes
        answerItem.querySelectorAll('[name^="questions[0]"]').forEach(el => {
            el.name = el.name
                .replace('questions[0]', `questions[${questionIndex}]`)
                .replace('answers[0]', `answers[${answerCount}]`);
        });

        answersList.appendChild(answerItem);
        updateAnswerFields(questionItem);
    }

    // Update question numbers sequentially
    function updateQuestionNumbers() {
        document.querySelectorAll('.question-item').forEach((item, index) => {
            item.querySelector('.question-number').textContent = index + 1;
        });
    }

    // Update answer fields based on question type
    function updateAnswerFields(questionItem) {
        const questionType = questionItem.querySelector('.question-type').value;
        const answers = questionItem.querySelectorAll('.answer-item');

        answers.forEach(answer => {
            const correctCheckbox = answer.querySelector('.is-correct');

            if (questionType === 'short_answer') {
                correctCheckbox.checked = true;
                correctCheckbox.parentElement.style.display = 'none';
            } else {
                correctCheckbox.parentElement.style.display = 'flex';

                // For true/false questions, auto-fill first two answers
                if (questionType === 'true_false' && answers.length <= 2) {
                    const answerInput = answer.querySelector('input[type="text"]');
                    if (answerInput) {
                        answerInput.readOnly = true;
                        answerInput.value = answers[0] === answer ? 'Doğru' : 'Yanlış';
                    }
                }
            }
        });
    }

    // Get question index in the DOM
    function getQuestionIndex(questionItem) {
        return Array.from(document.querySelectorAll('.question-item')).indexOf(questionItem);
    }
});
</script>
</x-layouts.app>
