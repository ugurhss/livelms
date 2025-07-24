
<div class="container">
    <h1 class="mb-4">Kayıtlı Kurslarım</h1>

    <div class="row">
        @forelse($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $course->thumbnail ?? asset('images/default-course.jpg') }}" class="card-img-top" alt="{{ $course->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>

                        <div class="progress mb-3">
                            @php
                                $progress = $course->pivot->progress;
                                $progressClass = $progress < 30 ? 'bg-danger' : ($progress < 70 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress-bar {{ $progressClass }}"
                                 role="progressbar"
                                 style="width: {{ $progress }}%"
                                 aria-valuenow="{{ $progress }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ $progress }}%
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ $course->level }}</span>
                            <span class="text-muted">{{ $course->lessons_count }} ders</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">
                                Kursa Git
                            </a>
                            <a href="{{ route('courses.quiz', $course->id) }}" class="btn btn-outline-success btn-sm">
                                Sınav
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Henüz hiç kursa kayıtlı değilsiniz. <a href="{{ route('courses.index') }}">Kursları keşfetmek için tıklayın</a>.
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $courses->links() }}
    </div>
</div>

<style>
    .progress {
        height: 20px;
        border-radius: 10px;
    }
    .progress-bar {
        border-radius: 10px;
        font-size: 12px;
        line-height: 20px;
    }
    .card {
        transition: transform 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
