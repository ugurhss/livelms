@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Yeni Soru Ekle: {{ $quiz->title }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('courses.quizzes.questions.store', [$courseId, $quiz->id]) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="question_text" class="form-label">Soru Metni</label>
                            <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="3" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="question_type" class="form-label">Soru Tipi</label>
                            <select class="form-select @error('question_type') is-invalid @enderror" id="question_type" name="question_type" required>
                                <option value="">Seçiniz</option>
                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Çoktan Seçmeli</option>
                                <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>Doğru/Yanlış</option>
                                <option value="short_answer" {{ old('question_type') == 'short_answer' ? 'selected' : '' }}>Kısa Cevap</option>
                            </select>
                            @error('question_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Puan</label>
                            <input type="number" class="form-control @error('points') is-invalid @enderror" id="points" name="points" value="{{ old('points', 1) }}" min="1">
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="answers-container">
                            <!-- Answers will be added here dynamically based on question type -->
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionTypeSelect = document.getElementById('question_type');
    const answersContainer = document.getElementById('answers-container');

    questionTypeSelect.addEventListener('change', function() {
        updateAnswerFields(this.value);
    });

    // Initialize on page load if there's a selected value
    if (questionTypeSelect.value) {
        updateAnswerFields(questionTypeSelect.value);
    }

    function updateAnswerFields(type) {
        answersContainer.innerHTML = '';

        if (type === 'multiple_choice') {
            addMultipleChoiceFields();
        } else if (type === 'true_false') {
            addTrueFalseFields();
        } else if (type === 'short_answer') {
            addShortAnswerFields();
        }
    }

    function addMultipleChoiceFields() {
        const answerCount = 4; // Default number of answers

        answersContainer.innerHTML = '<h5 class="mt-3">Cevaplar</h5>';

        for (let i = 0; i < answerCount; i++) {
            const answerDiv = document.createElement('div');
            answerDiv.className = 'mb-3';

            answerDiv.innerHTML = `
                <label class="form-label">Cevap ${i + 1}</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="answers[${i}][answer_text]" required>
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="answers[${i}][is_correct]" value="1" ${i === 0 ? 'checked' : ''}>
                        <span class="ms-2">Doğru Cevap</span>
                    </div>
                </div>
            `;

            answersContainer.appendChild(answerDiv);
        }

        // Add button to add more answers
        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.className = 'btn btn-sm btn-outline-primary';
        addButton.textContent = '+ Cevap Ekle';
        addButton.onclick = function() {
            const nextIndex = answersContainer.querySelectorAll('.mb-3').length;

            const answerDiv = document.createElement('div');
            answerDiv.className = 'mb-3';

            answerDiv.innerHTML = `
                <label class="form-label">Cevap ${nextIndex + 1}</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="answers[${nextIndex}][answer_text]" required>
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="answers[${nextIndex}][is_correct]" value="1">
                        <span class="ms-2">Doğru Cevap</span>
                    </div>
                </div>
            `;

            answersContainer.insertBefore(answerDiv, addButton);
        };

        answersContainer.appendChild(addButton);
    }

    function addTrueFalseFields() {
        answersContainer.innerHTML = `
            <h5 class="mt-3">Cevaplar</h5>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="answers[0][is_correct]" id="true_answer" value="1" checked>
                <label class="form-check-label" for="true_answer">
                    Doğru
                </label>
                <input type="hidden" name="answers[0][answer_text]" value="Doğru">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="answers[1][is_correct]" id="false_answer" value="1">
                <label class="form-check-label" for="false_answer">
                    Yanlış
                </label>
                <input type="hidden" name="answers[1][answer_text]" value="Yanlış">
            </div>
        `;
    }

    function addShortAnswerFields() {
        answersContainer.innerHTML = `
            <h5 class="mt-3">Doğru Cevap</h5>
            <div class="mb-3">
                <input type="text" class="form-control" name="answers[0][answer_text]" required>
                <input type="hidden" name="answers[0][is_correct]" value="1">
            </div>
        `;
    }
});
</script>
@endsection
