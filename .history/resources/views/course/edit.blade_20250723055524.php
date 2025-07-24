
<div class="container py-5">
    <h1 class="mb-4">{{ $isEdit ? 'Kursu Düzenle' : 'Yeni Kurs Oluştur' }}</h1>

    <form action="{{ $isEdit ? route('courses.update', $course->id) : route('courses.store') }}"
          method="POST" enctype="multipart/form-data" id="course-form">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">Kurs Bilgileri</h3>

                        <div class="mb-3">
                            <label class="form-label">Kurs Başlığı</label>
                            <input type="text" class="form-control"
                                   name="course[title]"
                                   value="{{ old('course.title', $course->title ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kurs Açıklaması</label>
                            <textarea class="form-control" name="course[description]"
                                      rows="5" required>{{ old('course.description', $course->description ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kazanımlar</label>
                            <div id="outcomes-container">
                                @foreach(old('course.outcomes', $course->outcomes ?? ['']) as $index => $outcome)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control"
                                           name="course[outcomes][]"
                                           value="{{ $outcome }}">
                                    <button type="button" class="btn btn-outline-danger remove-outcome">×</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-outcome">
                                Kazanım Ekle
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ön Koşullar</label>
                            <div id="prerequisites-container">
                                @foreach(old('course.prerequisites', $course->prerequisites ?? ['']) as $index => $prerequisite)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control"
                                           name="course[prerequisites][]"
                                           value="{{ $prerequisite }}">
                                    <button type="button" class="btn btn-outline-danger remove-prerequisite">×</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-prerequisite">
                                Ön Koşul Ekle
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">Dersler</h3>

                        <div id="lessons-container">
                            @foreach(old('lessons', $lessons) as $index => $lesson)
                            <div class="lesson-item card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5>Ders #{{ $index + 1 }}</h5>
                                        @if($index > 0 || count(old('lessons', $lessons)) > 1)
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-lesson">Dersi Sil</button>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ders Başlığı</label>
                                        <input type="text" class="form-control"
                                               name="lessons[{{ $index }}][title]"
                                               value="{{ $lesson['title'] }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ders Açıklaması</label>
                                        <textarea class="form-control"
                                                  name="lessons[{{ $index }}][description]"
                                                  rows="3" required>{{ $lesson['description'] }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Süre (dakika)</label>
                                                <input type="number" class="form-control"
                                                       name="lessons[{{ $index }}][duration_minutes]"
                                                       value="{{ $lesson['duration_minutes'] }}" min="1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Video URL</label>
                                                <input type="url" class="form-control"
                                                       name="lessons[{{ $index }}][video_url]"
                                                       value="{{ $lesson['video_url'] }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                               name="lessons[{{ $index }}][is_free]"
                                               value="1" {{ isset($lesson['is_free']) && $lesson['is_free'] ? 'checked' : '' }}>
                                        <label class="form-check-label">Ücretsiz Ders</label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-outline-primary w-100" id="add-lesson">
                            Yeni Ders Ekle
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">Ayarlar</h3>

                        <div class="mb-3">
                            <label class="form-label">Kurs Resmi</label>
                            @if($isEdit && $course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                     class="img-thumbnail mb-2 d-block" style="max-width: 100%;">
                            @endif
                            <input type="file" class="form-control" name="course[thumbnail]"
                                   {{ !$isEdit ? 'required' : '' }}>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Seviye</label>
                            <select class="form-select" name="course[level]" required>
                                @foreach(['beginner' => 'Başlangıç', 'intermediate' => 'Orta', 'advanced' => 'İleri'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('course.level', $course->level ?? '') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="course[category_id]" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('course.category_id', $course->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fiyat (₺)</label>
                            <input type="number" step="0.01" class="form-control"
                                   name="course[price]"
                                   value="{{ old('course.price', $course->price ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">İndirimsiz Fiyat (₺)</label>
                            <input type="number" step="0.01" class="form-control"
                                   name="course[original_price]"
                                   value="{{ old('course.original_price', $course->original_price ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Toplam Süre (dakika)</label>
                            <input type="number" class="form-control"
                                   name="course[duration]"
                                   value="{{ old('course.duration', $course->duration ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Durum</label>
                            <select class="form-select" name="course[status]" required>
                                @foreach(['draft' => 'Taslak', 'published' => 'Yayınla'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('course.status', $course->status ?? '') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    {{ $isEdit ? 'Değişiklikleri Kaydet' : 'Kursu Oluştur' }}
                </button>

                @if($isEdit)
                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-outline-secondary w-100 mt-2">
                    İptal
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kazanım ekleme
    document.getElementById('add-outcome').addEventListener('click', function() {
        addDynamicField('outcomes-container', 'course[outcomes][]');
    });

    // Ön koşul ekleme
    document.getElementById('add-prerequisite').addEventListener('click', function() {
        addDynamicField('prerequisites-container', 'course[prerequisites][]');
    });

    // Ders ekleme
    let lessonIndex = {{ count(old('lessons', $lessons)) }};
    document.getElementById('add-lesson').addEventListener('click', function() {
        const container = document.getElementById('lessons-container');
        const div = document.createElement('div');
        div.className = 'lesson-item card mb-3';
        div.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Ders #${lessonIndex + 1}</h5>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-lesson">Dersi Sil</button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ders Başlığı</label>
                    <input type="text" class="form-control" name="lessons[${lessonIndex}][title]" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ders Açıklaması</label>
                    <textarea class="form-control" name="lessons[${lessonIndex}][description]" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Süre (dakika)</label>
                            <input type="number" class="form-control" name="lessons[${lessonIndex}][duration_minutes]" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Video URL</label>
                            <input type="url" class="form-control" name="lessons[${lessonIndex}][video_url]" required>
                        </div>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="lessons[${lessonIndex}][is_free]" value="1">
                    <label class="form-check-label">Ücretsiz Ders</label>
                </div>
            </div>
        `;
        container.appendChild(div);
        lessonIndex++;
    });

    // Dinamik element silme
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-outcome') ||
            e.target.classList.contains('remove-prerequisite')) {
            e.target.parentElement.remove();
        }

        if (e.target.classList.contains('remove-lesson')) {
            const lessons = document.querySelectorAll('.lesson-item');
            if (lessons.length > 1) {
                e.target.closest('.lesson-item').remove();
                // Yeniden numaralandır
                document.querySelectorAll('.lesson-item').forEach((lesson, index) => {
                    lesson.querySelector('h5').textContent = `Ders #${index + 1}`;
                });
                lessonIndex = lessons.length - 1;
            } else {
                alert('En az bir ders eklemelisiniz!');
            }
        }
    });

    function addDynamicField(containerId, name) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" class="form-control" name="${name}">
            <button type="button" class="btn btn-outline-danger remove-${containerId.replace('-container', '')}">×</button>
        `;
        container.appendChild(div);
    }
});
</script>
@endsection
@endsection
