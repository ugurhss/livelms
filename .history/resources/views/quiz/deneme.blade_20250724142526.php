<x-layouts.app>


<div class="container">
    <h1>Yeni Quiz Oluştur</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('quizzes.store') }}" method="POST">
        @csrf

        <!-- Kurs Seçimi -->
        <div class="mb-3">
            <label for="course_id" class="form-label">Kurs Seçin</label>
            <select name="course_id" id="course_id" class="form-select" required>
                <option value="">-- Kurs Seçin --</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Quiz Bilgileri -->
        <div class="mb-3">
            <label for="quiz_title" class="form-label">Quiz Başlığı</label>
            <input type="text" name="quiz_title" id="quiz_title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quiz_description" class="form-label">Quiz Açıklaması (Opsiyonel)</label>
            <textarea name="quiz_description" id="quiz_description" class="form-control" rows="2"></textarea>
        </div>

        <!-- Soru Ekleme Alanı -->
        <div id="questions-container">
            <div class="question-group mb-4 p-3 border rounded">
                <h5>Soru #1</h5>
                <div class="mb-3">
                    <label class="form-label">Soru Metni</label>
                    <input type="text" name="questions[0][text]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Soru Tipi</label>
                    <select name="questions[0][type]" class="form-select question-type" required>
                        <option value="multiple_choice">Çoktan Seçmeli</option>
                        <option value="true_false">Doğru/Yanlış</option>
                        <option value="short_answer">Kısa Cevap</option>
                    </select>
                </div>

                <!-- Cevaplar (Çoktan Seçmeli/Doğru-Yanlış için) -->
                <div class="answers-container mb-3" data-question-index="0">
                    <label class="form-label">Cevaplar</label>
                    <div class="answer-group mb-2">
                        <input type="text" name="questions[0][answers][0][text]" class="form-control mb-1" placeholder="Cevap metni">
                        <div class="form-check">
                            <input type="checkbox" name="questions[0][answers][0][is_correct]" class="form-check-input">
                            <label class="form-check-label">Doğru Cevap</label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary add-answer" data-question-index="0">+ Cevap Ekle</button>
                </div>
            </div>
        </div>

        <button type="button" id="add-question" class="btn btn-primary mb-3">+ Yeni Soru Ekle</button>
        <button type="submit" class="btn btn-success">Quiz'i Kaydet</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Yeni soru ekleme
    document.getElementById('add-question').addEventListener('click', function() {
        const container = document.getElementById('questions-container');
        const questionCount = container.querySelectorAll('.question-group').length;
        const newQuestionHtml = `
            <div class="question-group mb-4 p-3 border rounded">
                <h5>Soru #${questionCount + 1}</h5>
                <div class="mb-3">
                    <label class="form-label">Soru Metni</label>
                    <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Soru Tipi</label>
                    <select name="questions[${questionCount}][type]" class="form-select question-type" required>
                        <option value="multiple_choice">Çoktan Seçmeli</option>
                        <option value="true_false">Doğru/Yanlış</option>
                        <option value="short_answer">Kısa Cevap</option>
                    </select>
                </div>
                <div class="answers-container mb-3" data-question-index="${questionCount}">
                    <label class="form-label">Cevaplar</label>
                    <div class="answer-group mb-2">
                        <input type="text" name="questions[${questionCount}][answers][0][text]" class="form-control mb-1" placeholder="Cevap metni">
                        <div class="form-check">
                            <input type="checkbox" name="questions[${questionCount}][answers][0][is_correct]" class="form-check-input">
                            <label class="form-check-label">Doğru Cevap</label>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary add-answer" data-question-index="${questionCount}">+ Cevap Ekle</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newQuestionHtml);
    });

    // Cevap ekleme (Dinamik olarak)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-answer')) {
            const questionIndex = e.target.getAttribute('data-question-index');
            const answersContainer = document.querySelector(`.answers-container[data-question-index="${questionIndex}"]`);
            const answerCount = answersContainer.querySelectorAll('.answer-group').length;

            const newAnswerHtml = `
                <div class="answer-group mb-2">
                    <input type="text" name="questions[${questionIndex}][answers][${answerCount}][text]" class="form-control mb-1" placeholder="Cevap metni">
                    <div class="form-check">
                        <input type="checkbox" name="questions[${questionIndex}][answers][${answerCount}][is_correct]" class="form-check-input">
                        <label class="form-check-label">Doğru Cevap</label>
                    </div>
                </div>
            `;
            answersContainer.insertAdjacentHTML('beforeend', newAnswerHtml);
        }
    });
});
</script>
</x-layouts.app>
