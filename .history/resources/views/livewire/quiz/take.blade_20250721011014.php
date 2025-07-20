
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4">{{ $quiz->title }}</h1>
                    <div class="quiz-timer" id="quizTimer" style="display: none;">
                        <i class="bi bi-clock"></i> Kalan Süre: <span id="timeDisplay">00:00</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($quiz->description)
                        <div class="alert alert-info mb-4">
                            {{ $quiz->description }}
                        </div>
                    @endif

                    <form id="quizForm" method="POST" action="{{ route('courses.quizzes.submit', [$courseId, $quiz->id]) }}">
                        @csrf

                        @foreach($quiz->questions as $index => $question)
                        <div class="question-card mb-4">
                            <div class="question-header d-flex justify-content-between align-items-center mb-2">
                                <h2 class="h5 mb-0">{{ $index + 1 }}. {{ $question->question_text }}</h2>
                                <span class="badge bg-secondary">{{ $question->points }} puan</span>
                            </div>

                            <div class="question-body">
                                @if($question->question_type === 'multiple_choice')
                                    @foreach($question->answers as $answer)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="answer_{{ $answer->id }}" value="{{ $answer->id }}" required>
                                        <label class="form-check-label" for="answer_{{ $answer->id }}">
                                            {{ $answer->answer_text }}
                                        </label>
                                    </div>
                                    @endforeach
                                @elseif($question->question_type === 'true_false')
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="answer_{{ $question->id }}_true" value="{{ $question->answers->firstWhere('answer_text', 'Doğru')->id }}" required>
                                        <label class="form-check-label" for="answer_{{ $question->id }}_true">
                                            Doğru
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="answer_{{ $question->id }}_false" value="{{ $question->answers->firstWhere('answer_text', 'Yanlış')->id }}">
                                        <label class="form-check-label" for="answer_{{ $question->id }}_false">
                                            Yanlış
                                        </label>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="answer_{{ $question->id }}">Cevabınız:</label>
                                        <input type="text" class="form-control" id="answer_{{ $question->id }}" name="answers[{{ $question->id }}]" required>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Sınavı Tamamla</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($quiz->time_limit)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quizForm = document.getElementById('quizForm');
    const timerDisplay = document.getElementById('quizTimer');
    const timeDisplay = document.getElementById('timeDisplay');

    // Show timer
    timerDisplay.style.display = 'block';

    let timeLeft = {{ $quiz->time_limit * 60 }}; // Convert minutes to seconds

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        timeDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            alert('Süreniz doldu! Quiz otomatik olarak gönderilecek.');
            quizForm.submit();
        } else {
            timeLeft--;
        }
    }

    // Update timer every second
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // Prevent form submission if time is up
    quizForm.addEventListener('submit', function(e) {
        if (timeLeft <= 0) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

