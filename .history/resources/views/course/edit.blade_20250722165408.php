
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Kursu Düzenle: {{ $course->title }}</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Temel Bilgiler -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Kurs Başlığı*</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title', $course->title) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Kurs Açıklaması*</label>
                                    <textarea class="form-control" id="description" name="description"
                                              rows="5" required>{{ old('description', $course->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Kısa Açıklama (Özet)*</label>
                                    <textarea class="form-control" id="short_description" name="short_description"
                                              rows="3" maxlength="255" required>{{ old('short_description', $course->short_description) }}</textarea>
                                    <small class="text-muted">Max 255 karakter</small>
                                </div>
                            </div>

                            <!-- Yan Bilgiler -->
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Kategori*</label>
                                            <select class="form-select" id="category_id" name="category_id" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="price" class="form-label">Fiyat (₺)*</label>
                                            <input type="number" class="form-control" id="price" name="price"
                                                   min="0" step="0.01" value="{{ old('price', $course->price) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">Kurs Görseli</label>
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                            @if($course->image_path)
                                                <div class="mt-2">
                                                    <img src="{{ asset($course->image_path) }}" alt="Kurs görseli" class="img-thumbnail" width="100">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dersler Bölümü -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Dersler</h5>
                            </div>
                            <div class="card-body">
                                <div id="lessons-container">
                                    @foreach(old('lessons', $course->lessons) as $index => $lesson)
                                        <div class="lesson-item mb-3 p-3 border rounded">
                                            <input type="hidden" name="lessons[{{ $index }}][id]" value="{{ $lesson['id'] ?? '' }}">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Ders Başlığı*</label>
                                                        <input type="text" class="form-control"
                                                               name="lessons[{{ $index }}][title]"
                                                               value="{{ old("lessons.$index.title", $lesson['title'] ?? '') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Süre (dakika)*</label>
                                                        <input type="number" class="form-control"
                                                               name="lessons[{{ $index }}][duration_minutes]"
                                                               value="{{ old("lessons.$index.duration_minutes", $lesson['duration_minutes'] ?? 15) }}"
                                                               min="1" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-lesson">
                                                        <i class="fas fa-trash"></i> Sil
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Ders İçeriği*</label>
                                                <textarea class="form-control" rows="3"
                                                          name="lessons[{{ $index }}][content]" required>{{ old("lessons.$index.content", $lesson['content'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" id="add-lesson" class="btn btn-secondary mt-2">
                                    <i class="fas fa-plus"></i> Yeni Ders Ekle
                                </button>
                            </div>
                        </div>

                        <!-- Durum ve Butonlar -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_published"
                                   name="is_published" value="1" {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Kursu yayınla</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            {{-- <a href="{{ route('courses.show', $course->id) }}" class="btn btn-outline-secondary"> --}}
                                <i class="fas fa-arrow-left"></i> İptal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Yeni ders ekleme
    document.getElementById('add-lesson').addEventListener('click', function() {
        const container = document.getElementById('lessons-container');
        const index = container.children.length;

        const lessonHtml = `
        <div class="lesson-item mb-3 p-3 border rounded">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ders Başlığı*</label>
                        <input type="text" class="form-control"
                               name="lessons[${index}][title]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Süre (dakika)*</label>
                        <input type="number" class="form-control"
                               name="lessons[${index}][duration_minutes]"
                               value="15" min="1" required>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-lesson">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Ders İçeriği*</label>
                <textarea class="form-control" rows="3"
                          name="lessons[${index}][content]" required></textarea>
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', lessonHtml);
    });

    // Ders silme
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-lesson')) {
            if (confirm('Bu dersi silmek istediğinize emin misiniz?')) {
                e.target.closest('.lesson-item').remove();

                // Kalan dersleri yeniden indeksle
                const container = document.getElementById('lessons-container');
                Array.from(container.children).forEach((item, index) => {
                    item.querySelectorAll('[name^="lessons["]').forEach(input => {
                        input.name = input.name.replace(/lessons\[\d+\]/g, `lessons[${index}]`);
                    });
                });
            }
        }
    });
});
</script>
@endpush
