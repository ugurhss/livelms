<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Yeni Quiz Oluştur</h1>
        </div>

        <!-- Form -->
        <form action="{{ route('quizzes.store') }}" method="POST">
            @csrf

            <!-- Quiz Info Card -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Quiz Bilgileri</h3>
                </div>
                <div class="px-6 py-4">
                    <!-- Form fields here (same as before) -->
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Sorular</h3>
                    <button type="button" id="add-question-btn" class="btn-indigo">
                        <i class="fas fa-plus mr-1"></i> Yeni Soru Ekle
                    </button>
                </div>
                <div class="px-6 py-4">
                    <div id="questions-container">
                        <!-- Questions will be added here -->
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="btn-indigo">Quiz'i Kaydet</button>
            </div>
        </form>
    </div>

    <!-- Question Template -->
    <template id="question-template">
        <div class="question-item mb-6 border rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border-b">
                <h4 class="font-medium">
                    <span class="question-number mr-2">1</span>. Soru
                </h4>
                <button type="button" class="remove-question text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Soru Metni</label>
                    <textarea name="questions[0][question_text]" class="w-full rounded border-gray-300" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Soru Tipi</label>
                        <select name="questions[0][question_type]" class="question-type w-full rounded border-gray-300" required>
                            <option value="multiple_choice">Çoktan Seçmeli</option>
                            <option value="true_false">Doğru/Yanlış</option>
                            <option value="short_answer">Kısa Cevap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Puan</label>
                        <input type="number" name="questions[0][points]" min="1" value="1" class="w-full rounded border-gray-300" required>
                    </div>
                </div>

                <div class="answers-section">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Cevaplar</label>
                        <button type="button" class="add-answer btn-indigo-sm">
                            <i class="fas fa-plus mr-1"></i> Cevap Ekle
                        </button>
                    </div>
                    <div class="answers-list space-y-2">
                        <!-- Answers will be added here -->
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Answer Template -->
    <template id="answer-template">
        <div class="answer-item flex items-center gap-2">
            <input type="text" name="questions[0][answers][0][answer_text]" class="flex-1 rounded border-gray-300" required>
            <div class="flex items-center">
                <input type="checkbox" name="questions[0][answers][0][is_correct]" value="1" class="is-correct h-4 w-4 rounded border-gray-300">
                <span class="ml-2 text-sm">Doğru</span>
            </div>
            <button type="button" class="remove-answer text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </template>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let questionCount = 0;
        const questionsContainer = document.getElementById('questions-container');

        // Add first question automatically
        addQuestion();

        // Add question button
        document.getElementById('add-question-btn').addEventListener('click', addQuestion);

        // Event delegation for dynamic buttons
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

            // Question type change
            if (e.target.classList.contains('question-type')) {
                const questionItem = e.target.closest('.question-item');
                updateAnswerFields(questionItem);
            }
        });

        function addQuestion() {
            questionCount++;
            const template = document.getElementById('question-template').content.cloneNode(true);
            const questionItem = template.querySelector('.question-item');
            const questionIndex = questionCount - 1;

            // Update all question fields
            questionItem.querySelectorAll('[name^="questions[0]"]').forEach(el => {
                el.name = el.name.replace('questions[0]', `questions[${questionIndex}]`);
            });

            questionsContainer.appendChild(questionItem);
            updateQuestionNumbers();

            // Add first answer
            addAnswer(questionItem, questionIndex);
        }

        function addAnswer(questionItem, questionIndex) {
            const answersList = questionItem.querySelector('.answers-list');
            const answerCount = answersList.querySelectorAll('.answer-item').length;
            const template = document.getElementById('answer-template').content.cloneNode(true);
            const answerItem = template.querySelector('.answer-item');

            // Update answer fields
            answerItem.querySelectorAll('[name^="questions[0]"]').forEach(el => {
                el.name = el.name
                    .replace('questions[0]', `questions[${questionIndex}]`)
                    .replace('answers[0]', `answers[${answerCount}]`);
            });

            answersList.appendChild(answerItem);
            updateAnswerFields(questionItem);
        }

        function updateQuestionNumbers() {
            document.querySelectorAll('.question-item').forEach((item, index) => {
                item.querySelector('.question-number').textContent = index + 1;
            });
        }

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
                }
            });
        }

        function getQuestionIndex(questionItem) {
            return Array.from(document.querySelectorAll('.question-item')).indexOf(questionItem);
        }
    });
    </script>
    @endpush
</x-layouts.app>
