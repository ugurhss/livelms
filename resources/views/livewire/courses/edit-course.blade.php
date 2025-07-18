<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Kursu Düzenle: {{ $course->title }}</h1>

        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save">
            <!-- Temel Bilgiler -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Temel Bilgiler</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Kurs Başlığı</label>
                        <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Fiyat (₺)</label>
                        <input type="number" id="price" wire:model="price" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Açıklama</label>
                    <textarea id="description" wire:model="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select id="category_id" wire:model="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seçiniz</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="level_id" class="block text-sm font-medium text-gray-700">Seviye</label>
                        <select id="level_id" wire:model="level_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seçiniz</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                        @error('level_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Kurs Resmi</label>
                    <input type="file" id="image" wire:model="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if($currentImage)
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Mevcut Resim:</p>
                            <img src="{{ asset('storage/'.$currentImage) }}" alt="Current Course Image" class="h-32 rounded-md mt-2">
                        </div>
                    @endif
                    @if($image)
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Yeni Resim Önizleme:</p>
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-32 rounded-md mt-2">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kazanımlar -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Kazanımlar</h2>
                <p class="text-sm text-gray-500 mb-2">Öğrenciler bu kursu tamamladığında neler öğrenecek?</p>

                <div class="space-y-2 mb-4">
                    @foreach($outcomes as $index => $outcome)
                        <div class="flex items-center">
                            <span class="mr-2">• {{ $outcome }}</span>
                            <button type="button" wire:click="removeOutcome({{ $index }})" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="flex">
                    <input type="text" wire:model="newOutcome" placeholder="Yeni kazanım ekle" class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="button" wire:click="addOutcome" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Ekle
                    </button>
                </div>
                @error('newOutcome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Ön Koşullar -->
            <div class="mb-8 pt-6 border-t border-gray-200">
                <h2 class="text-xl font-semibold mb-4">Ön Koşullar</h2>
                <p class="text-sm text-gray-500 mb-2">Bu kursa katılmak için öğrencilerin sahip olması gereken bilgiler.</p>

                <div class="space-y-2 mb-4">
                    @foreach($prerequisites as $index => $prerequisite)
                        <div class="flex items-center">
                            <span class="mr-2">• {{ $prerequisite }}</span>
                            <button type="button" wire:click="removePrerequisite({{ $index }})" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="flex">
                    <input type="text" wire:model="newPrerequisite" placeholder="Yeni ön koşul ekle" class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="button" wire:click="addPrerequisite" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Ekle
                    </button>
                </div>
                @error('newPrerequisite') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Dersler -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Kurs Dersleri</h2>
                <p class="text-sm text-gray-500 mb-4">Kursunuza eklemek istediğiniz dersleri girin.</p>

                <div class="space-y-4 mb-6">
                    @foreach($lessons as $index => $lesson)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium">Ders {{ $index + 1 }}: {{ $lesson['title'] }}</h4>
                                <button type="button" wire:click="removeLesson({{ $index }})" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $lesson['description'] }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="mr-4">Süre: {{ $lesson['duration'] }} dakika</span>
                                <span>Video URL: <a href="{{ $lesson['video_url'] }}" target="_blank" class="text-indigo-600 hover:underline">Göster</a></span>
                                @if($lesson['is_free'])
                                    <span class="ml-4 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Ücretsiz</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border rounded-lg p-6">
                    <h4 class="font-medium mb-4">Yeni Ders Ekle</h4>
                    <div class="space-y-4">
                        <div>
                            <label for="lesson_title" class="block text-sm font-medium text-gray-700">Ders Başlığı</label>
                            <input type="text" id="lesson_title" wire:model="currentLesson.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('currentLesson.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="lesson_description" class="block text-sm font-medium text-gray-700">Açıklama</label>
                            <textarea id="lesson_description" wire:model="currentLesson.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('currentLesson.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="lesson_video_url" class="block text-sm font-medium text-gray-700">Video URL</label>
                                <input type="url" id="lesson_video_url" wire:model="currentLesson.video_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('currentLesson.video_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="lesson_duration" class="block text-sm font-medium text-gray-700">Süre (dakika)</label>
                                <input type="number" id="lesson_duration" wire:model="currentLesson.duration" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('currentLesson.duration') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="lesson_is_free" wire:model="currentLesson.is_free" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <label for="lesson_is_free" class="ml-2 block text-sm text-gray-700">Ücretsiz ders (Önizleme olarak kullanılabilir)</label>
                        </div>

                        <div class="pt-2">
                            <button type="button" wire:click="addLesson" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ders Ekle
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    İptal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Güncellemeleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
