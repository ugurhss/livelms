@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4">Quiz Sonucu: {{ $result->quiz->title }}</h1>
                </div>

                <div class="card-body">
                    <div class="result-summary mb-4 p-4 rounded {{ $result->is_passed ? 'bg-success-light' : 'bg-danger-light' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="h5">Puanınız:</h2>
                                <div class="display-4 {{ $result->is_passed ? 'text-success' : 'text-danger' }}">
                                    {{ round($result->score) }}%
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="h5">Sonuç:</h2>
                                @if($result->is_passed)
                                    <div class="display-6 text-success">Tebrikler, geçtiniz!</div>
                                    <p>Geçme notu: {{ $result->quiz->passing_score }}%</p>
                                @else
                                    <div class="display-6 text-danger">Maalesef, geçemediniz</div>
                                    <p>Geçme notu: {{ $result->quiz->passing_score }}%</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h3 class="h5 mb-3">Soru Detayları</h3>

                    @foreach($result->quiz->questions as $index => $question)
                        @php
                            $userAnswer = $result->userAnswers->firstWhere('question_id', $question->id);
                            $isCorrect = $userAnswer ? $userAnswer->is_correct : false;
                        @endphp

                        <div class="question-result mb-4 p-3 rounded {{ $isCorrect ? 'bg-success-light' : 'bg-danger-light' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="h6 mb-0">{{ $index + 1 }}. {{ $question->question_text }}</h4>
                                <span class="badge bg-{{ $isCorrect ? 'success' : 'danger' }}">
                                    {{ $isCorrect ? 'Doğru' : 'Yanlış' }} ({{ $question->points }} puan)
                                </span>
                            </div>

                            @if($question->question_type === 'multiple_choice' || $question->question_type === 'true_false')
                                <div class="mb-2">
                                    <strong>Doğru Cevap:</strong>
                                    {{ $question->answers->firstWhere('is_correct', true)->answer_text }}
                                </div>
                                <div>
                                    <strong>Sizin Cevabınız:</strong>
                                    {{ $userAnswer && $userAnswer->answer ? $userAnswer->answer->answer_text : '-' }}
                                </div>
                            @else
                                <div class="mb-2">
                                    <strong>Doğru Cevap:</strong>
                                    {{ $question->answers->first()->answer_text }}
                                </div>
                                <div>
                                    <strong>Sizin Cevabınız:</strong>
                                    {{ $userAnswer ? $userAnswer->answer_text : '-' }}
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="d-grid mt-4">
                        <a href="{{ route('courses.quizzes.index', $courseId) }}" class="btn btn-primary">
                            Quiz Listesine Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
