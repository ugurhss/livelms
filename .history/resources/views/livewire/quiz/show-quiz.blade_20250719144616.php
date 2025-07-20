@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h1>{{ $quiz->title }}</h1>
            <p class="lead">{{ $quiz->description }}</p>
        </div>
        <div class="col-md-6 text-end">
            @can('edit-quiz', $quiz)
            <a href="{{ route('courses.quizzes.edit', [$courseId, $quiz->id]) }}" class="btn btn-primary me-2">
                Düzenle
            </a>
            @endcan

            @if($quiz->is_published)
                <a href="{{ route('courses.quizzes.start', [$courseId, $quiz->id]) }}" class="btn btn-success">
                    Quiz'e Başla
                </a>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h5>Quiz Bilgileri</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Durum:</span>
                            <span class="badge bg-{{ $quiz->is_published ? 'success' : 'warning' }}">
                                {{ $quiz->is_published ? 'Yayında' : 'Taslak' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Başlangıç:</span>
                            <span>{{ $quiz->start_date ? $quiz->start_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Bitiş:</span>
                            <span>{{ $quiz->end_date ? $quiz->end_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Süre Limiti:</span>
                            <span>{{ $quiz->time_limit ? $quiz->time_limit.' dakika' : 'Yok' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Geçme Notu:</span>
                            <span>{{ $quiz->passing_score }}%</span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <h5>Sorular</h5>

                    @if($quiz->questions->isEmpty())
                        <div class="alert alert-info">Henüz soru eklenmemiş.</div>
                    @else
                        <div class="list-group mb-3">
                            @foreach($quiz->questions as $question)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $loop->iteration }}. Soru:</strong> {{ $question->question_text }}
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                Tip: {{ $question->question_type }} | Puan: {{ $question->points }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    @can('edit-quiz', $quiz)
                    <a href="{{ route('courses.quizzes.questions.create', [$courseId, $quiz->id]) }}" class="btn btn-sm btn-primary">
                        Yeni Soru Ekle
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('courses.quizzes.index', $courseId) }}" class="btn btn-secondary">
        Quiz Listesine Dön
    </a>
</div>
@endsection
