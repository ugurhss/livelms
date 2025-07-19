<div>
  @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h1>Kurs Quizleri</h1>
        </div>
        @can('create-quiz')
        <div class="col-md-6 text-end">
            <a href="{{ route('courses.quizzes.create', $courseId) }}" class="btn btn-primary">
                Yeni Quiz Oluştur
            </a>
        </div>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($quizzes->isEmpty())
                <p class="text-center">Henüz quiz eklenmemiş.</p>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Başlık</th>
                            <th>Açıklama</th>
                            <th>Başlangıç</th>
                            <th>Bitiş</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizzes as $quiz)
                        <tr>
                            <td>{{ $quiz->title }}</td>
                            <td>{{ Str::limit($quiz->description, 50) }}</td>
                            <td>{{ $quiz->start_date ? $quiz->start_date->format('d.m.Y H:i') : '-' }}</td>
                            <td>{{ $quiz->end_date ? $quiz->end_date->format('d.m.Y H:i') : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $quiz->is_published ? 'success' : 'warning' }}">
                                    {{ $quiz->is_published ? 'Yayında' : 'Taslak' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('courses.quizzes.show', [$courseId, $quiz->id]) }}" class="btn btn-sm btn-info">
                                    Görüntüle
                                </a>
                                @can('edit-quiz', $quiz)
                                <a href="{{ route('courses.quizzes.edit', [$courseId, $quiz->id]) }}" class="btn btn-sm btn-primary">
                                    Düzenle
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

</div>
