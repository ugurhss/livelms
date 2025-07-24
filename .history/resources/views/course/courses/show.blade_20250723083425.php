@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <img src="{{ $course->thumbnail ?? asset('images/default-course.jpg') }}" class="card-img-top" alt="{{ $course->title }}">
                <div class="card-body">
                    <h1 class="card-title">{{ $course->title }}</h1>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary me-2">{{ $course->level }}</span>
                        <span class="text-muted me-2">{{ $course->duration }} dakika</span>
                        <span class="text-muted">{{ $course->category->name }}</span>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="ratings me-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $course->reviews->avg('rating'))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span>({{ $course->reviews->count() }} değerlendirme)</span>
                        </div>
                        <div class="students-count">
                            <i class="fas fa-users me-1"></i>
                            {{ $course->students->count() }} öğrenci
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4>Kurs Açıklaması</h4>
                        <p>{{ $course->description }}</p>
                    </div>

                    @if($course->outcomes && count($course->outcomes) > 0)
                        <div class="mb-4">
                            <h4>Kazanımlar</h4>
                            <ul>
                                @foreach($course->outcomes as $outcome)
                                    <li>{{ $outcome }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($course->prerequisites && count($course->prerequisites) > 0)
                        <div class="mb-4">
                            <h4>Önkoşullar</h4>
                            <ul>
                                @foreach($course->prerequisites as $prerequisite)
                                    <li>{{ $prerequisite }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Dersler</h3>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($course->lessons as $lesson)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">{{ $lesson->title }}</h5>
                                    <small class="text-muted">{{ $lesson->duration_minutes }} dakika</small>
                                </div>
                                <div>
                                    @if($lesson->is_free)
                                        <span class="badge bg-success me-2">Ücretsiz</span>
                                    @endif
                                    @if($isEnrolled || $lesson->is_free)
                                        <a href="{{ route('lessons.show', [$course->id, $lesson->id]) }}" class="btn btn-sm btn-primary">İzle</a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>İzle</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Değerlendirmeler</h3>
                </div>
                <div class="card-body">
                    @livewire('reviews.review-component', ['courseId' => $course->id])
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Fiyat</h4>
                        <div>
                            <span class="h4 text-success">{{ number_format($course->price, 2) }} ₺</span>
                            @if($course->original_price > $course->price)
                                <span class="text-decoration-line-through text-muted ms-2">{{ number_format($course->original_price, 2) }} ₺</span>
                            @endif
                        </div>
                    </div>

                    @if($isEnrolled)
                        <div class="alert alert-success">
                            Bu kursa kayıtlısınız.
                        </div>
                        <a href="{{ route('courses.quiz', $course->id) }}" class="btn btn-primary w-100 mb-2">Sınavı Görüntüle</a>
                        <form action="{{ route('courses.unenroll', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">Kurstan Ayrıl</button>
                        </form>
                    @else
                        <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">Kursa Kaydol</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Eğitmen</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $course->instructor->profile_photo_url }}" alt="{{ $course->instructor->name }}" class="rounded-circle me-3" width="50" height="50">
                        <div>
                            <h5 class="mb-0">{{ $course->instructor->name }}</h5>
                            <small class="text-muted">{{ $course->instructor->email }}</small>
                        </div>
                    </div>
                    <p class="mb-3">{{ $course->instructor->bio ?? 'Eğitmen hakkında bilgi bulunmamaktadır.' }}</p>
                    <div class="d-flex justify-content-between">
                        <div class="text-center">
                            <div class="h5 mb-0">{{ $course->instructor->courses_count }}</div>
                            <small class="text-muted">Kurs</small>
                        </div>
                        <div class="text-center">
                            <div class="h5 mb-0">{{ $course->instructor->students_count }}</div>
                            <small class="text-muted">Öğrenci</small>
                        </div>
                        <div class="text-center">
                            <div class="h5 mb-0">{{ $course->instructor->average_rating }}</div>
                            <small class="text-muted">Puan</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Kurs İstatistikleri</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Toplam Ders
                            <span class="badge bg-primary rounded-pill">{{ $course->lessons->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Toplam Süre
                            <span class="badge bg-primary rounded-pill">{{ $course->lessons->sum('duration_minutes') }} dakika</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Öğrenci Sayısı
                            <span class="badge bg-primary rounded-pill">{{ $course->students->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tamamlama Oranı
                            <span class="badge bg-primary rounded-pill">{{ $course->enrollments->avg('progress') ?? 0 }}%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
