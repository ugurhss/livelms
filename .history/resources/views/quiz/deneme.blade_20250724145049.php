<x-layouts.app>

<div class="container">
    <h2>Yeni Quiz Oluştur</h2>

    <form action="{{ route('quizzes.store') }}" method="POST">
        @csrf

        <div class="card mb-4">
            <div class="card-header">Quiz Bilgileri</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="course_id">Kurs Seçin</label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <option value="">Kurs Seçin</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Quiz Başlığı</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Açıklama</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="time_limit">Süre Limiti (dakika)</label>
                            <input type="number" name="time_limit" id="time_limit" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="passing_score">Geçme Notu (%)</label>
                            <input type="number" name="passing_score" id="passing_score" class="form-control" value="70" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="is_published">Yayınla</label>
                            <select name="is_published" id="is_published" class="form-control">
                                <option value="0">Hayır</option>
                                <option value="1">Evet</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Başlangıç Tarihi</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Bitiş Tarihi</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Sorular</div>
            <div class="card-body">
                <div id="questions-container">
                    <!-- Sorular buraya eklenecek -->
                </div>

                <button type="button" id="add-question" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Soru Ekle
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Quiz Oluştur</button>
    </form>
</div>

<!-- Soru şablonu (JavaScript tarafından kullanılacak) -->
<template id="question-template">
    <div class="question-item card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Soru <span class="question-number"></span></span>
            <button type="button" class="btn btn-sm btn-danger remove-question">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Soru Metni</label>
                <textarea name="questions[0][question_text]" class="form-control question-text" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Soru Tipi</label>
                        <select name="questions[0][question_type]" class="form-control question-type" required>
                            <option value="multiple_choice">Çoktan Seçmeli</option>
                            <option value="true_false">Doğru/Yanlış</option>
                            <option value="short_answer">Kısa Cevap</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Puan</label>
                        <input type="number" name="questions[0][points]" class="form-control question-points" value="1" min="1" required>
                    </div>
                </div>
            </div>

            <div class="answers-container">
                <h5>Cevaplar</h5>
                <div class="answers-list">
                    <!-- Cevaplar buraya eklenecek -->
                </div>
                <button type="button" class="btn btn-sm btn-primary add-answer">
                    <i class="fas fa-plus"></i> Cevap Ekle
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Cevap şablonu (JavaScript tarafından kullanılacak) -->
<template id="answer-template">
    <div class="answer-item mb-2">
        <div class="input-group">
            <input type="text" name="questions[0][answers][0][answer_text]" class="form-control" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <input type="checkbox" name="questions[0][answers][0][is_correct]" class="is-correct" value="1">
                    <span class="ml-2">Doğru Cevap</span>
                </div>
            </div>
            <div class="input-group-append">
                <button type="button" class="btn btn-danger remove-answer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCounter = 0;
    let answerCounters = {};

    // Yeni soru ekle
    document.getElementById('add-question').addEventListener('click', function() {
        addNewQuestion();
    });

    function addNewQuestion() {
        const container = document.getElementById('questions-container');
        const template = document.getElementById('question-template').content.cloneNode(true);
        const questionItem = template.querySelector('.question-item');

        questionCounter++;
        answerCounters[questionCounter] = 0;

        // Soru numarasını güncelle
        questionItem.querySelector('.question-number').textContent = questionCounter;

        // İsimleri güncelle
        const questionIndex = questionCounter - 1;

        // Soru metni
        const questionText = questionItem.querySelector('.question-text');
        questionText.name = `questions[${questionIndex}][question_text]`;

        // Soru tipi
        const questionType = questionItem.querySelector('.question-type');
        questionType.name = `questions[${questionIndex}][question_type]`;
        questionType.addEventListener('change', function() {
            updateAnswerFields(questionItem, this.value);
        });

        // Puan
        const questionPoints = questionItem.querySelector('.question-points');
        questionPoints.name = `questions[${questionIndex}][points]`;

        // Sil butonu
        questionItem.querySelector('.remove-question').addEventListener('click', function() {
            questionItem.remove();
            updateQuestionNumbers();
        });

        // Cevap ekle butonu
        const addAnswerBtn = questionItem.querySelector('.add-answer');
        addAnswerBtn.addEventListener('click', function() {
            addNewAnswer(questionItem, questionIndex);
        });

        container.appendChild(questionItem);

        // İlk cevabı ekle
        addNewAnswer(questionItem, questionIndex);

        // Soru numaralarını güncelle
        updateQuestionNumbers();
    }

    function addNewAnswer(questionItem, questionIndex) {
        const answersList = questionItem.querySelector('.answers-list');
        const template = document.getElementById('answer-template').content.cloneNode(true);
        const answerItem = template.querySelector('.answer-item');

        answerCounters[questionIndex + 1]++;
        const answerIndex = answerCounters[questionIndex + 1] - 1;

        // Cevap metni
        const answerText = answerItem.querySelector('input[type="text"]');
        answerText.name = `questions[${questionIndex}][answers][${answerIndex}][answer_text]`;

        // Doğru cevap checkbox
        const isCorrect = answerItem.querySelector('.is-correct');
        isCorrect.name = `questions[${questionIndex}][answers][${answerIndex}][is_correct]`;

        // Sil butonu
        answerItem.querySelector('.remove-answer').addEventListener('click', function() {
            answerItem.remove();
        });

        answersList.appendChild(answerItem);

        // Soru tipine göre cevap alanlarını güncelle
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

        if (questionType === 'short_answer') {
            isCorrectCheckbox.parentElement.style.display = 'none';
            isCorrectCheckbox.checked = true;
        } else {
            isCorrectCheckbox.parentElement.style.display = 'flex';
        }
    }

    function updateQuestionNumbers() {
        const questions = document.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            question.querySelector('.question-number').textContent = index + 1;
        });
    }

    // İlk soruyu ekle
    addNewQuestion();
});
</script>
</x-layouts.app>
