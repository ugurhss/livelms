
<x-layouts.app>

<div class="container">
    <h1>Kurs Düzenle: {{ $course->title }}</h1>

    <form action="{{ route('courses.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Form alanları buraya gelecek -->
        <div class="mb-3">
            <label for="title" class="form-label">Kurs Başlığı</label>
            <input type="text" class="form-control" id="title" name="title"
                   value="{{ old('title', $course->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <textarea class="form-control" id="description" name="description" rows="5" required>
                {{ old('description', $course->description) }}
            </textarea>
        </div>

        <!-- Diğer form alanları -->

        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-secondary">İptal</a>
    </form>
</div>
</x-layouts.app>
