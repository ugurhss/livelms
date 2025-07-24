  <x-layouts.app :title="__('quizzes')">



<div class="container">
    <h2>Quiz Düzenle: {{ $quiz->title }}</h2>

    <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header">Quiz Bilgileri</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="course_id">Kurs Seçin</label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <option value="">Kurs Seçin</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $quiz->course_id == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Quiz Başlığı</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $quiz->title }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Açıklama</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ $quiz->description }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="time_limit">Süre Limiti (dakika)</label>
                            <input type="number" name="time_limit" id="time_limit" class="form-control" value="{{ $quiz->time_limit }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="passing_score">Geçme Notu (%)</label>
                            <input type="number" name="passing_score" id="passing_score" class="form-control" value="{{ $quiz->passing_score }}" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="is_published">Yayınla</label>
                            <select name="is_published" id="is_published" class="form-control">
                                <option value="0" {{ !$quiz->is_published ? 'selected' : '' }}>Hayır</option>
                                <option value="1" {{ $quiz->is_published ? 'selected' : '' }}>Evet</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Başlangıç Tarihi</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                   value="{{ $quiz->start_date ? $quiz->start_date->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Bitiş Tarihi</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                                   value="{{ $quiz->end_date ? $quiz->end_date->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="{{ route('quizzes.show', $quiz->id) }}" class="btn btn-secondary">İptal</a>
    </form>
</div>

</x-layouts.app>
