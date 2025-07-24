@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Kursu Düzenle: {{ $course->title }}</h1>

    <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3>Kurs Bilgileri</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Kurs Başlığı *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $course->title }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">Kategori *</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Kurs Açıklaması *</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $course->description }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="level">Seviye *</label>
                            <select class="form-control" id="level" name="level" required>
                                <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Başlangıç</option>
                                <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>Orta</option>
                                <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>İleri</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price">Fiyat (TL) *</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $course->price }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="original_price">İndirimli Fiyat (TL)</label>
                            <input type="number" step="0.01" class="form-control" id="original_price" name="original_price" value="{{ $course->original_price }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Kurs Resmi</label>
                    @if($course->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="Kurs Resmi" style="max-width: 200px;">
                            <a href="#" class="btn btn-sm btn-danger ml-2" id="remove-thumbnail">Resmi Kaldır</a>
                            <input type="hidden" name="remove_thumbnail" id="remove-thumbnail-input" value="0">
                        </div>
                    @endif
                    <input type="file" class="form-control-file" id="thumbnail" name="thumbnail">
                </div>

                <div class="form-group">
                    <label for="outcomes">Kazanımlar (Her satıra bir kazanım yazın)</label>
                    <textarea class="form-control" id="outcomes" name="outcomes" rows="3">{{ $course->outcomes ? implode("\n", $course->outcomes) : '' }}</textarea>
                    <small class="form-text text-muted">Her kazanımı ayrı satıra yazın</small>
                </div>

                <div class="form-group">
                    <label for="prerequisites">Ön Koşullar (Her satıra bir koşul yazın)</label>
                    <textarea class="form-control" id="prerequisites" name="prerequisites" rows="3">{{ $course->prerequisites ? implode("\n", $course->prerequisites) : '' }}</textarea>
                    <small class="form-text text-muted">Her koşulu ayrı satıra yazın</small>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3>Dersler</h3>
            </div>
            <div class="card-body">
                <div id="lessons-container">
                    @foreach($course->lessons as $index => $lesson)
                        <div class="lesson-item card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Ders <span class="lesson-number">{{ $index + 1 }}</span></h4>
                                <button type="button" class="btn btn-danger btn-sm remove-lesson">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="lessons[{{ $index }}][id]" value="{{ $lesson->id }}">
                                <div class="form-group">
                                    <label>Ders Başlığı *</label>
                                    <input type="text" class="form-control" name="lessons[{{ $index }}][title]" value="{{ $lesson->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Ders Açıklaması</label>
                                    <textarea class="form-control" name="lessons[{{ $index }}][description]" rows="2">{{ $lesson->description }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Süre (dakika)</label>
                                            <input type="number" class="form-control" name="lessons[{{ $index }}][duration_minutes]" value="{{ $lesson->duration_minutes }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Video URL</label>
                                            <input type="url" class="form-control" name="lessons[{{ $index }}][video_url]" value="{{ $lesson->video_url }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="lesson-{{ $index }}-free" name="lessons[{{ $index }}][is_free]" value="1" {{ $lesson->is_free ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lesson-{{ $index }}-free">Ücretsiz Ders</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-secondary mt-3" id="add-lesson">
                    <i class="fas fa-plus"></i> Yeni Ders Ekle
                </button>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">Değişiklikleri Kaydet</button>
            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-secondary btn-lg">İptal</a>
        </div>
    </form>
</div>

<!-- Ders şablonu (hidden) -->
<div id="lesson-template" class="d-none">
    <div class="lesson-item card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Ders <span class="lesson-number">1</span></h4>
            <button type="button" class="btn btn-danger btn-sm remove-lesson">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Ders Başlığı *</label>
                <input type="text" class="form-control" name="lessons[0][title]" required>
            </div>
            <div class="form-group">
                <label>Ders Açıklaması</label>
                <textarea class="form-control" name="lessons[0][description]" rows="2"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Süre (dakika)</label>
                        <input type="number" class="form-control" name="lessons[0][duration_minutes]">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Video URL</label>
                        <input type="url" class="form-control" name="lessons[0][video_url]">
                    </div>
                </div>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="lesson-0-free" name="lessons[0][is_free]" value="1">
                <label class="form-check-label" for="lesson-0-free">Ücretsiz Ders</label>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let lessonCount = {{ count($course->lessons) }};

    // Yeni ders ekle
    $('#add-lesson').click(function() {
        const template = $('#lesson-template').html();
        const newLesson = template.replace(/0/g, lessonCount);
        $('#lessons-container').append(newLesson);
        lessonCount++;

        // Ders numaralarını güncelle
        updateLessonNumbers();
    });

    // Ders silme
    $(document).on('click', '.remove-lesson', function() {
        if (confirm('Bu dersi silmek istediğinize emin misiniz?')) {
            $(this).closest('.lesson-item').remove();
            updateLessonNumbers();
        }
    });

    // Ders numaralarını güncelle
    function updateLessonNumbers() {
        $('.lesson-item').each(function(index) {
            $(this).find('.lesson-number').text(index + 1);
        });
    }

    // Thumbnail kaldırma
    $('#remove-thumbnail').click(function(e) {
        e.preventDefault();
        $('#remove-thumbnail-input').val(1);
        $(this).parent().remove();
    });
});
</script>
@endpush
