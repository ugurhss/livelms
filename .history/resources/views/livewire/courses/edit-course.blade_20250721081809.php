<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Header Section -->
        <div class="bg-indigo-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Kursu Düzenle: {{ $course->title }}</h1>
        </div>

        <!-- Success Message -->
        @if (session('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-4 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p>{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <div class="p-6">
            <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-8">
                <!-- Basic Information Card -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Temel Bilgiler
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Kurs Başlığı*</label>
                            <input type="text" id="title" wire:model="form.title"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('form.title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)*</label>
                            <input type="number" id="price" wire:model="form.price" min="0" step="0.01"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('form.price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori*</label>
                            <select id="category_id" wire:model="form.category_id"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seçiniz</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('form.category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Level -->
                        <div>
                            <label for="level_id" class="block text-sm font-medium text-gray-700 mb-1">Seviye*</label>
                            <select id="level_id" wire:model="form.level_id"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seçiniz</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                            @error('form.level_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama*</label>
                        <textarea id="description" wire:model="form.description" rows="4"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('form.description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kurs Resmi</label>
                        <div class="flex items-center space-x-4">
                            @if($currentImage)
                                <div class="relative">
                                    <img src="{{ asset('storage/'.$currentImage) }}" alt="Current Course Image" class="h-32 rounded-lg object-cover">
                                    <button type="button" wire:click="removeImage"
                                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 -mt-2 -mr-2 hover:bg-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <div class="flex-1">
                                <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Resim yükle</span> veya sürükle bırak
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                                    </div>
                                    <input id="image" type="file" wire:model="image" class="hidden" accept="image/*">
                                </label>
                                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if($image)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-1">Yeni Resim Önizleme:</p>
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-32 rounded-lg object-cover">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Outcomes Card -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kazanımlar
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">Öğrenciler bu kursu tamamladığında neler öğrenecek?</p>

                    <div class="space-y-3 mb-4">
                        @foreach($form['outcomes'] as $index => $outcome)
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>{{ $outcome }}</span>
                                </div>
                                <button type="button" wire:click="removeOutcome({{ $index }})"
                                        class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex">
                        <input type="text" wire:model="newOutcome" placeholder="Yeni kazanım ekle"
                               class="flex-1 rounded-l-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="button" wire:click="addOutcome"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-lg font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Ekle
                        </button>
                    </div>
                    @error('newOutcome') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Prerequisites Card -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Ön Koşullar
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">Bu kursa katılmak için öğrencilerin sahip olması gereken bilgiler.</p>

                    <div class="space-y-3 mb-4">
                        @foreach($form['prerequisites'] as $index => $prerequisite)
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $prerequisite }}</span>
                                </div>
                                <button type="button" wire:click="removePrerequisite({{ $index }})"
                                        class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex">
                        <input type="text" wire:model="newPrerequisite" placeholder="Yeni ön koşul ekle"
                               class="flex-1 rounded-l-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="button" wire:click="addPrerequisite"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-lg font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Ekle
                        </button>
                    </div>
                    @error('newPrerequisite') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Lessons Card -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kurs Dersleri
                    </h2>
                    <p class="text-sm text-gray-600 mb-6">Kursunuza eklemek istediğiniz dersleri girin.</p>

                    <!-- Existing Lessons -->
                    <div class="space-y-4 mb-6">
                        @foreach($form['lessons'] as $index => $lesson)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                                    <h4 class="font-medium text-gray-800">
                                        Ders {{ $index + 1 }}: {{ $lesson['title'] }}
                                    </h4>
                                    <button type="button" wire:click="removeLesson({{ $index }})"
                                            class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-600 mb-3">{{ $lesson['description'] }}</p>
                                    <div class="flex flex-wrap items-center text-sm text-gray-500 gap-4">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $lesson['duration'] }} dakika
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            <a href="{{ $lesson['video_url'] }}" target="_blank" class="text-indigo-600 hover:underline">Video Linki</a>
                                        </span>
                                        @if($lesson['is_free'])
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                Ücretsiz Önizleme
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add New Lesson -->
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="px-4 py-3 bg-gray-50">
                            <h4 class="font-medium text-gray-800">Yeni Ders Ekle</h4>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Lesson Title -->
                            <div>
                                <label for="lesson_title" class="block text-sm font-medium text-gray-700 mb-1">Ders Başlığı*</label>
                                <input type="text" id="lesson_title" wire:model="currentLesson.title"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('currentLesson.title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Lesson Description -->
                            <div>
                                <label for="lesson_description" class="block text-sm font-medium text-gray-700 mb-1">Açıklama*</label>
                                <textarea id="lesson_description" wire:model="currentLesson.description" rows="3"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('currentLesson.description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Video URL -->
                                <div>
                                    <label for="lesson_video_url" class="block text-sm font-medium text-gray-700 mb-1">Video URL*</label>
                                    <input type="url" id="lesson_video_url" wire:model="currentLesson.video_url"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('currentLesson.video_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label for="lesson_duration" class="block text-sm font-medium text-gray-700 mb-1">Süre (dakika)*</label>
                                    <input type="number" id="lesson_duration" wire:model="currentLesson.duration" min="1"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('currentLesson.duration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Free Lesson -->
                            <div class="flex items-center">
                                <input type="checkbox" id="lesson_is_free" wire:model="currentLesson.is_free"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <label for="lesson_is_free" class="ml-2 block text-sm text-gray-700">
                                    Ücretsiz ders (Önizleme olarak kullanılabilir)
                                </label>
                            </div>

                            <!-- Add Lesson Button -->
                            <div class="pt-2">
                                <button type="button" wire:click="addLesson"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Ders Ekle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('courses.show', $course->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        İptal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Güncellemeleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
