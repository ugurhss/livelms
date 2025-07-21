<div>
    <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-6">
        @error('general') <p class="text-red-500">{{ $message }}</p> @enderror

        <!-- Başlık -->
        <div>
            <label class="block font-medium">Kurs Başlığı</label>
            <input type="text" wire:model.defer="form.title" class="input w-full" />
            @error('form.title') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Açıklama -->
        <div>
            <label class="block font-medium">Açıklama</label>
            <textarea wire:model.defer="form.description" class="textarea w-full" rows="4"></textarea>
            @error('form.description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Fiyat -->
        <div>
            <label class="block font-medium">Fiyat</label>
            <input type="number" wire:model.defer="form.price" class="input w-full" />
            @error('form.price') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Kategori -->
        <div>
            <label class="block font-medium">Kategori</label>
            <select wire:model.defer="form.category_id" class="select w-full">
                <option value="">Seçiniz</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('form.category_id') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Seviye -->
        <div>
            <label class="block font-medium">Seviye</label>
            <select wire:model.defer="form.level_id" class="select w-full">
                <option value="">Seçiniz</option>
                @foreach ($levels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            </select>
            @error('form.level_id') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Resim -->
        <div>
            <label class="block font-medium">Görsel</label>
            @if ($currentImage)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $currentImage) }}" alt="Mevcut Görsel" class="w-48 h-32 object-cover rounded">
                    <button wire:click="removeImage" type="button" class="text-sm text-red-500 mt-1">Resmi kaldır</button>
                </div>
            @endif
            <input type="file" wire:model="image" />
            @error('image') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Kazanımlar -->
        <div>
            <label class="block font-medium">Kazanımlar</label>
            <ul class="list-disc ml-5 space-y-1">
                @foreach ($form['outcomes'] as $index => $outcome)
                    <li>{{ $outcome }}
                        <button type="button" wire:click="removeOutcome({{ $index }})" class="text-red-500 ml-2">Kaldır</button>
                    </li>
                @endforeach
            </ul>
            <div class="mt-2 flex items-center gap-2">
                <input type="text" wire:model="newOutcome" class="input" placeholder="Yeni kazanım" />
                <button type="button" wire:click="addOutcome" class="btn btn-primary">Ekle</button>
            </div>
            @error('newOutcome') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Önkoşullar -->
        <div>
            <label class="block font-medium">Önkoşullar</label>
            <ul class="list-disc ml-5 space-y-1">
                @foreach ($form['prerequisites'] as $index => $prerequisite)
                    <li>{{ $prerequisite }}
                        <button type="button" wire:click="removePrerequisite({{ $index }})" class="text-red-500 ml-2">Kaldır</button>
                    </li>
                @endforeach
            </ul>
            <div class="mt-2 flex items-center gap-2">
                <input type="text" wire:model="newPrerequisite" class="input" placeholder="Yeni önkoşul" />
                <button type="button" wire:click="addPrerequisite" class="btn btn-primary">Ekle</button>
            </div>
            @error('newPrerequisite') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Dersler -->
        <div>
            <label class="block font-medium">Dersler</label>
            <ul class="space-y-2">
                @foreach ($form['lessons'] as $index => $lesson)
                    <li class="border p-2 rounded">
                        <div class="flex justify-between">
                            <span class="font-semibold">{{ $lesson['title'] }}</span>
                            <button type="button" wire:click="removeLesson({{ $index }})" class="text-red-500">Sil</button>
                        </div>
                        <p>{{ $lesson['description'] }}</p>
<p class="text-sm text-gray-600">Süre: {{ $lesson['duration_minutes'] }} dakika</p>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4 space-y-2">
                <input type="text" wire:model="currentLesson.title" placeholder="Ders Başlığı" class="input w-full" />
                <textarea wire:model="currentLesson.description" placeholder="Ders Açıklaması" class="textarea w-full"></textarea>
                <input type="text" wire:model="currentLesson.video_url" placeholder="Video URL" class="input w-full" />
                <input type="number" wire:model="currentLesson.duration" placeholder="Süre (dk)" class="input w-full" />
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="currentLesson.is_free" class="mr-2"> Ücretsiz
                </label>
                <button type="button" wire:click="addLesson" class="btn btn-secondary mt-2">Ders Ekle</button>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-success">Kaydet</button>
        </div>
    </form>
</div>
