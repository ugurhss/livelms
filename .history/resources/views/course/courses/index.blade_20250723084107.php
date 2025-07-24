@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tüm Kurslar</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('courses.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Kurs ara..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Ara</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                @auth
                    {{-- <a href="{{ route('courses.instructor') }}" class="btn btn-outline-primary me-2">Eğitmen Kurslarım</a>
                    <a href="{{ route('courses.my-courses') }}" class="btn btn-outline-success">Kayıtlı Kurslarım</a> --}}
                @endauth
                @can('create', App\Models\Course::class)
                    <a href="{{ route('courses.create') }}" class="btn btn-success ms-2">Yeni Kurs Oluştur</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $course->thumbnail ?? asset('images/default-course.jpg') }}" class="card-img-top" alt="{{ $course->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary">{{ $course->level }}</span>
                            <span class="text-muted">{{ $course->duration }} dakika</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-success fw-bold">{{ number_format($course->price, 2) }} ₺</span>
                            @if($course->original_price > $course->price)
                                <span class="text-decoration-line-through text-muted">{{ number_format($course->original_price, 2) }} ₺</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">Detayları Gör</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $courses->links() }}
    </div>
</div>
@endsection
