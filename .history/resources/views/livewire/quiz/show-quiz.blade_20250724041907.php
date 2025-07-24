<div class="container py-4">
    <!-- Quiz Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-5">
        <div>
            <h1 class="fw-bold text-gradient text-primary">{{ $quiz->title }}</h1>
            <p class="lead text-muted mb-0">{{ $quiz->description }}</p>
        </div>
        <div class="d-flex gap-2">
            @can('edit-quiz', $quiz)
            <a href="{{ route('courses.quizzes.edit', [$courseId, $quiz->id]) }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-edit me-2"></i>Düzenle
            </a>
            @endcan

            @if($quiz->is_published)
                <a href="{{ route('courses.quizzes.start', [$courseId, $quiz->id]) }}" class="btn btn-success rounded-pill px-4">
                    <i class="fas fa-play me-2"></i>Quiz'e Başla
                </a>
            @endif
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- Quiz Info Section -->
                <div class="col-lg-4 p-4 bg-light">
                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Quiz Detayları
                    </h5>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Durum:</span>
                            <span class="badge rounded-pill bg-{{ $quiz->is_published ? 'success' : 'warning' }} bg-opacity-10 text-{{ $quiz->is_published ? 'success' : 'warning' }}">
                                {{ $quiz->is_published ? 'Yayında' : 'Taslak' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Başlangıç:</span>
                            <span class="fw-medium">{{ $quiz->start_date ? $quiz->start_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Bitiş:</span>
                            <span class="fw-medium">{{ $quiz->end_date ? $quiz->end_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Süre Limiti:</span>
                            <span class="fw-medium">{{ $quiz->time_limit ? $quiz->time_limit.' dakika' : 'Yok' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Geçme Notu:</span>
                            <span class="fw-medium">{{ $quiz->passing_score }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Questions Section -->
                <div class="col-lg-8 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Sorular
                        </h5>

                        @can('edit-quiz', $quiz)
                        <a href="{{ route('courses.quizzes.questions.create', [$courseId, $quiz->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="fas fa-plus me-1"></i> Yeni Soru Ekle
                        </a>
                        @endcan
                    </div>

                    @if($quiz->questions->isEmpty())
                        <div class="alert alert-light border rounded-3 text-center py-4">
                            <i class="fas fa-question-circle fa-2x text-muted mb-3"></i>
                            <h5 class="text-muted">Henüz soru eklenmemiş</h5>
                            @can('edit-quiz', $quiz)
                            <a href="{{ route('courses.quizzes.questions.create', [$courseId, $quiz->id]) }}" class="btn btn-sm btn-outline-primary mt-2">
                                İlk Soruyu Ekle
                            </a>
                            @endcan
                        </div>
                    @else
                        <div class="accordion" id="questionsAccordion">
                            @foreach($quiz->questions as $question)
                            <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden shadow-sm">
                                <h2 class="accordion-header" id="heading{{ $question->id }}">
                                    <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $question->id }}" aria-expanded="false" aria-controls="collapse{{ $question->id }}">
                                        <div class="d-flex align-items-center w-100">
                                            <span class="badge bg-primary me-3">{{ $loop->iteration }}</span>
                                            <div class="flex-grow-1 text-truncate me-2">
                                                {{ $question->question_text }}
                                            </div>
                                            <small class="text-muted ms-auto">
                                                {{ $question->points }} puan
                                            </small>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $question->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $question->id }}" data-bs-parent="#questionsAccordion">
                                    <div class="accordion-body pt-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-light text-dark me-2">
                                                    {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                                </span>
                                            </div>
                                            <div class="d-flex gap-2">
                                                @can('edit-quiz', $quiz)
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('courses.quizzes.index', $courseId) }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i>Quiz Listesine Dön
    </a>
</div>
