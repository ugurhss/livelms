<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Kursu Düzenle</h1>
            <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kursa Geri Dön
            </a>
        </div>

        <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-6 text-gray-900">Temel Bilgiler</h2>

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Kurs Başlığı</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Kurs Açıklaması</label>
                                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Thumbnail -->
                            <div>
                                <label for="thumbnail" class="block text-sm font-medium text-gray-700">Kurs Resmi</label>
                                <input type="file" name="thumbnail" id="thumbnail" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @error('thumbnail')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if($course->thumbnail)
                                    <div class="mt-2">
                                        <span class="text-sm text-gray-500">Mevcut resim:</span>
                                        <img src="{{ $course->thumbnail }}" alt="Current thumbnail" class="mt-1 h-20 rounded-md">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Status -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-6 text-gray-900">Fiyatlandırma ve Durum</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Fiyat (₺)</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $course->price) }}" min="0" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Original Price -->
                            <div>
                                <label for="original_price" class="block text-sm font-medium text-gray-700">İndirimsiz Fiyat (₺)</label>
                                <input type="number" name="original_price" id="original_price" value="{{ old('original_price', $course->original_price) }}" min="0" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('original_price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Level -->
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700">Seviye</label>
                                <select name="level" id="level" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Başlangıç</option>
                                    <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Orta Seviye</option>
                                    <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>İleri Seviye</option>
                                </select>
                                @error('level')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Durum</label>
                                <select name="status" id="status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Taslak</option>
                                    <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Yayınlandı</option>
                                    <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Arşivlendi</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Kategori Seçin</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Outcomes -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-6 text-gray-900">Kazanımlar</h2>
                        <div class="space-y-4" id="outcomes-container">
                            @foreach(old('outcomes', $course->outcomes ?? []) as $index => $outcome)
                                <div class="flex items-center outcome-item">
                                    <input type="text" name="outcomes[]" value="{{ $outcome }}" class="flex-1 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Kurs sonunda öğrenci ne kazanacak?">
                                    <button type="button" class="ml-2 p-2 text-red-500 hover:text-red-700 remove-outcome">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-outcome" class="mt-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Yeni Kazanım Ekle
                        </button>
                    </div>

                    <!-- Prerequisites -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-6 text-gray-900">Ön Koşullar</h2>
                        <div class="space-y-4" id="prerequisites-container">
                            @foreach(old('prerequisites', $course->prerequisites ?? []) as $index => $prerequisite)
                                <div class="flex items-center prerequisite-item">
                                    <input type="text" name="prerequisites[]" value="{{ $prerequisite }}" class="flex-1 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Bu kurs için gerekli ön bilgiler">
                                    <button type="button" class="ml-2 p-2 text-red-500 hover:text-red-700 remove-prerequisite">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-prerequisite" class="mt-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Yeni Ön Koşul Ekle
                        </button>
                    </div>

                    <!-- Lessons -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-6 text-gray-900">Dersler</h2>

                        <div class="space-y-6" id="lessons-container">
                            @foreach(old('lessons', $course->lessons ?? []) as $index => $lesson)
                                <div class="border border-gray-200 rounded-lg p-4 lesson-item">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-medium text-gray-900">Ders #{{ $index + 1 }}</h3>
                                        <button type="button" class="text-red-500 hover:text-red-700 remove-lesson">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Ders Başlığı</label>
                                            <input type="text" name="lessons[{{ $index }}][title]" value="{{ $lesson['title'] }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Ders Açıklaması</label>
                                            <textarea name="lessons[{{ $index }}][description]" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ $lesson['description'] ?? '' }}</textarea>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Süre (dakika)</label>
                                                <input type="number" name="lessons[{{ $index }}][duration_minutes]" value="{{ $lesson['duration_minutes'] ?? '' }}" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Video URL</label>
                                                <input type="url" name="lessons[{{ $index }}][video_url]" value="{{ $lesson['video_url'] ?? '' }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" name="lessons[{{ $index }}][is_free]" id="lesson-free-{{ $index }}" value="1" {{ isset($lesson['is_free']) && $lesson['is_free'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="lesson-free-{{ $index }}" class="ml-2 block text-sm text-gray-700">Ücretsiz olarak erişilebilir</label>
                                        </div>

                                        <input type="hidden" name="lessons[{{ $index }}][order]" value="{{ $index + 1 }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" id="add-lesson" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Yeni Ders Ekle
                        </button>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Actions -->
                    <div class="bg-white shadow-sm rounded-lg p-6 sticky top-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-900">İşlemler</h2>

                        <div class="space-y-4">
                            <button type="submit" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Değişiklikleri Kaydet
                            </button>

                            <a href="{{ route('courses.show', $course->id) }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                İptal
                            </a>
                        </div>

                        @if($course->status != 'archived')
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <button type="button" onclick="confirmArchive()" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Kursu Arşivle
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Preview -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4 text-gray-900">Önizleme</h2>

                        @if($course->thumbnail)
                            <img src="{{ $course->thumbnail }}" alt="Course thumbnail" class="w-full h-auto rounded-lg mb-4">
                        @endif

                        <h3 class="font-medium text-gray-900">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $course->level === 'beginner' ? 'Başlangıç' : ($course->level === 'intermediate' ? 'Orta Seviye' : 'İleri Seviye') }} • {{ $course->lessons->count() }} ders</p>
                    </div>
                </div>
            </div>
        </form>

        <!-- Archive Form -->
        <form id="archive-form" action="{{ route('courses.archive', $course->id) }}" method="POST" class="hidden">
            @csrf
            @method('PUT')
        </form>
    </div>

    @push('scripts')
    <script>
        // Add new outcome field
        document.getElementById('add-outcome').addEventListener('click', function() {
            const container = document.getElementById('outcomes-container');
            const index = container.querySelectorAll('.outcome-item').length;

            const div = document.createElement('div');
            div.className = 'flex items-center outcome-item';
            div.innerHTML = `
                <input type="text" name="outcomes[]" class="flex-1 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Kurs sonunda öğrenci ne kazanacak?">
                <button type="button" class="ml-2 p-2 text-red-500 hover:text-red-700 remove-outcome">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;

            container.appendChild(div);
        });

        // Remove outcome field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-outcome') || e.target.closest('.remove-outcome')) {
                const item = e.target.closest('.outcome-item');
                if (item) {
                    item.remove();
                }
            }
        });

        // Add new prerequisite field
        document.getElementById('add-prerequisite').addEventListener('click', function() {
            const container = document.getElementById('prerequisites-container');
            const index = container.querySelectorAll('.prerequisite-item').length;

            const div = document.createElement('div');
            div.className = 'flex items-center prerequisite-item';
            div.innerHTML = `
                <input type="text" name="prerequisites[]" class="flex-1 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Bu kurs için gerekli ön bilgiler">
                <button type="button" class="ml-2 p-2 text-red-500 hover:text-red-700 remove-prerequisite">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;

            container.appendChild(div);
        });

        // Remove prerequisite field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-prerequisite') || e.target.closest('.remove-prerequisite')) {
                const item = e.target.closest('.prerequisite-item');
                if (item) {
                    item.remove();
                }
            }
        });

        // Add new lesson
        document.getElementById('add-lesson').addEventListener('click', function() {
            const container = document.getElementById('lessons-container');
            const index = container.querySelectorAll('.lesson-item').length;

            const div = document.createElement('div');
            div.className = 'border border-gray-200 rounded-lg p-4 lesson-item';
            div.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-900">Ders #${index + 1}</h3>
                    <button type="button" class="text-red-500 hover:text-red-700 remove-lesson">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ders Başlığı</label>
                        <input type="text" name="lessons[${index}][title]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ders Açıklaması</label>
                        <textarea name="lessons[${index}][description]" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Süre (dakika)</label>
                            <input type="number" name="lessons[${index}][duration_minutes]" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Video URL</label>
                            <input type="url" name="lessons[${index}][video_url]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="lessons[${index}][is_free]" id="lesson-free-${index}" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="lesson-free-${index}" class="ml-2 block text-sm text-gray-700">Ücretsiz olarak erişilebilir</label>
                    </div>

                    <input type="hidden" name="lessons[${index}][order]" value="${index + 1}">
                </div>
            `;

            container.appendChild(div);
        });

        // Remove lesson
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-lesson') || e.target.closest('.remove-lesson')) {
                const item = e.target.closest('.lesson-item');
                if (item) {
                    item.remove();
                    // Reindex remaining lessons
                    const lessons = document.querySelectorAll('.lesson-item');
                    lessons.forEach((lesson, index) => {
                        lesson.querySelector('h3').textContent = `Ders #${index + 1}`;
                        // Update all inputs in this lesson
                        const inputs = lesson.querySelectorAll('input, textarea');
                        inputs.forEach(input => {
                            const name = input.getAttribute('name');
                            if (name) {
                                input.setAttribute('name', name.replace(/lessons\[\d+\]/, `lessons[${index}]`));
                            }
                        });
                        // Update the order hidden input
                        lesson.querySelector('input[type="hidden"]').value = index + 1;
                    });
                }
            }
        });

        // Confirm archive
        function confirmArchive() {
            if (confirm('Bu kursu arşivlemek istediğinize emin misiniz? Arşivlenen kurslar öğrenciler tarafından görüntülenemez.')) {
                document.getElementById('archive-form').submit();
            }
        }
    </script>
    @endpush
</x-layouts.app>
