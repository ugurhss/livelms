<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4">{{ $quiz->title }}</h1>
                    @if($timerVisible)
                        <div class="quiz-timer">
                            <i class="bi bi-clock"></i> Kalan Süre:
                            <span x-data="{ timeLeft: {{ $timeLeft }} }"
                                  x-init="setInterval(() => {
                                      timeLeft--;
                                      if(timeLeft <= 0) {
                                          $wire.submitQuiz();
                                      }
                                  }, 1000)"
                                  x-text="`${Math.floor(timeLeft / 60).toString().padStart(2, '0')}:${(timeLeft % 60).toString().padStart(2, '0')}`">
                            </span>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    @if($quiz->description)
                        <div class="alert alert-info mb-4">
                            {{ $quiz->description }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitQuiz" id="quizForm">
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
                                        <input class="form-check-input"
                                               type="radio"
                                               wire:model="answers.{{ $question->id }}"
                                               id="answer_{{ $answer->id }}"
                                               value="{{ $answer->id }}"
                                               required>
                                        <label class="form-check-label" for="answer_{{ $answer->id }}">
                                            {{ $answer->answer_text }}
                                        </label>
                                    </div>
                                    @endforeach
                                @elseif($question->question_type === 'true_false')
                                    <div class="form-check mb-2">
                                        <input class="form-check-input"
                                               type="radio"
                                               wire:model="answers.{{ $question->id }}"
                                               id="answer_{{ $question->id }}_true"
                                               value="{{ $question->answers->firstWhere('answer_text', 'Doğru')->id }}"
                                               required>
                                        <label class="form-check-label" for="answer_{{ $question->id }}_true">
                                            Doğru
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               wire:model="answers.{{ $question->id }}"
                                               id="answer_{{ $question->id }}_false"
                                               value="{{ $question->answers->firstWhere('answer_text', 'Yanlış')->id }}">
                                        <label class="form-check-label" for="answer_{{ $question->id }}_false">
                                            Yanlış
                                        </label>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="answer_{{ $question->id }}">Cevabınız:</label>
                                        <input type="text"
                                               class="form-control"
                                               id="answer_{{ $question->id }}"
                                               wire:model="answers.{{ $question->id }}"
                                               required>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span wire:loading.remove>Sınavı Tamamla</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Gönderiliyor...
                                </span>
                            </button>
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

    // AlpineJS zaten timer'ı yönettiği için bu koda gerek yok
    // Ancak yine de form submit kontrolü için bırakıyorum

    quizForm.addEventListener('submit', function(e) {
        // Zaman kontrolü AlpineJS tarafından yapılıyor
        // Burada ek kontroller yapabilirsiniz
    });
});
</script>
@endif
