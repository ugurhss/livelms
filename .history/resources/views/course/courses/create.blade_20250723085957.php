@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Yeni Kurs Oluştur</h1>

    <form action="{{ route('courses.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Temel Bilgiler</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Kurs Başlığı</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Kurs Açıklaması</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Kurs Resmi</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Dersler</div>
                    <div class="card-body">
                        <div id="lessons-container">
                            <div class="lesson-item mb-3 p-3 border rounded">
                                <div class="mb-3">
                                    <label class="form-label">Ders Başlığı</label>
                                    <input type="text" class="form-control" name="lessons[0][title]" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ders Açıklaması</label>
                                    <textarea class="form-control" name="lessons[0][description]" rows="3"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Süre (dakika)</label>
                                        <input type="number" class="form-control" name="lessons[0][duration_minutes]" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Video URL</label>
                                        <input type="url" class="form-control" name="lessons[0][video_url]">
                                    </div>
                                </div>

                                <div class="mt-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="lessons[0][is_free]" id="is_free_0" value="1">
                                    <label class="form-check-label" for="is_free_0">Ücretsiz Ders</label>
                                </div>

                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-lesson">Dersi Sil</button>
                            </div>
                        </div>

                        <button type="button" id="add-lesson" class="btn btn-secondary mt-3">Yeni Ders Ekle</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Ayarlar</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="level" class="form-label">Seviye</label>
                            <select class="form-select" id="level" name="level" required>
                                <option value="beginner">Başlangıç</option>
                                <option value="intermediate">Orta</option>
                                <option value="advanced">İleri</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Fiyat (₺)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                        </div>

                        <div class="mb-3">
                            <label for="original_price" class="form-label">Orijinal Fiyat (₺) (İndirim için)</label>
                            <input type="number" step="0.01" class="form-control" id="original_price" name="original_price">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Durum</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft">Taslak</option>
                                <option value="published">Yayınla</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Toplam Süre (dakika)</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="0">
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Kazanımlar</div>
                    <div class="card-body">
                        <div id="outcomes-container">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="outcomes[]" placeholder="Kazanım ekle">
                                <button type="button" class="btn btn-outline-danger remove-outcome">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-outcome" class="btn btn-sm btn-secondary mt-2">Kazanım Ekle</button>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Önkoşullar</div>
                    <div class="card-body">
                        <div id="prerequisites-container">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="prerequisites[]" placeholder="Önkoşul ekle">
                                <button type="button" class="btn btn-outline-danger remove-prerequisite">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-prerequisite" class="btn btn-sm btn-secondary mt-2">Önkoşul Ekle</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kursu Oluştur</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ders ekleme
    let lessonIndex = 1;
    document.getElementById('add-lesson').addEventListener('click', function() {
        const container = document.getElementById('lessons-container');
        const newLesson = document.createElement('div');
        newLesson.className = 'lesson-item mb-3 p-3 border rounded';
        newLesson.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Ders Başlığı</label>
                <input type="text" class="form-control" name="lessons[${lessonIndex}][title]" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ders Açıklaması</label>
                <textarea class="form-control" name="lessons[${lessonIndex}][description]" rows="3"></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Süre (dakika)</label>
                    <input type="number" class="form-control" name="lessons[${lessonIndex}][duration_minutes]" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Video URL</label>
                    <input type="url" class="form-control" name="lessons[${lessonIndex}][video_url]">
                </div>
            </div>

            <div class="mt-3 form-check">
                <input type="checkbox" class="form-check-input" name="lessons[${lessonIndex}][is_free]" id="is_free_${lessonIndex}" value="1">
                <label class="form-check-label" for="is_free_${lessonIndex}">Ücretsiz Ders</label>
            </div>

            <button type="button" class="btn btn-danger btn-sm mt-2 remove-lesson">Dersi Sil</button>
        `;
        container.appendChild(newLesson);
        lessonIndex++;
    });

    // Ders silme
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-lesson')) {
            if (document.querySelectorAll('.lesson-item').length > 1) {
                e.target.closest('.lesson-item').remove();
            } else {
                alert('En az bir ders olmalıdır.');
            }
        }
    });

    // Kazanım ekleme
    document.getElementById('add-outcome').addEventListener('click', function() {
        const container = document.getElementById('outcomes-container');
        const newOutcome = document.createElement('div');
        newOutcome.className = 'input-group mb-2';
        newOutcome.innerHTML = `
            <input type="text" class="form-control" name="outcomes[]" placeholder="Kazanım ekle">
            <button type="button" class="btn btn-outline-danger remove-outcome">×</button>
        `;
        container.appendChild(newOutcome);
    });

    // Kazanım silme
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-outcome')) {
            if (document.querySelectorAll('#outcomes-container .input-group').length > 1) {
                e.target.closest('.input-group').remove();
            } else {
                alert('En az bir kazanım olmalıdır.');
            }
        }
    });

    // Önkoşul ekleme
    document.getElementById('add-prerequisite').addEventListener('click', function() {
        const container = document.getElementById('prerequisites-container');
        const newPrerequisite = document.createElement('div');
        newPrerequisite.className = 'input-group mb-2';
        newPrerequisite.innerHTML = `
            <input type="text" class="form-control" name="prerequisites[]" placeholder="Önkoşul ekle">
            <button type="button" class="btn btn-outline-danger remove-prerequisite">×</button>
        `;
        container.appendChild(newPrerequisite);
    });

    // Önkoşul silme
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-prerequisite')) {
            if (document.querySelectorAll('#prerequisites-container .input-group').length > 1) {
                e.target.closest('.input-group').remove();
            } else {
                alert('En az bir önkoşul olmalıdır.');
            }
        }
    });
});
</script>

<style>
.lesson-item {
    background-color: #f8f9fa;
}
.remove-lesson, .remove-outcome, .remove-prerequisite {
    cursor: pointer;
}
</style>
@endsection
