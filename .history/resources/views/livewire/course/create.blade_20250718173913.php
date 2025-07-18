<div>
    <form wire:submit.prevent="save">
        <!-- Başlık -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Kurs Başlığı</label>
            <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Açıklama -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Açıklama</label>
            <textarea id="description" wire:model="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Thumbnail -->
        {{-- <div class="mb-4">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700">Kurs Resmi</label>
            @if($tempThumbnail)
                <img src="{{ Storage::url($tempThumbnail) }}" alt="Current thumbnail" class="mb-2 h-32">
            @endif
            <input type="file" id="thumbnail" wire:model="thumbnail" class="mt-1 block w-full">
            @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div> --}}

        <!-- Kategori -->
        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
            <input type="text" id="category" wire:model="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Seviye -->
        <div class="mb-4">
            <label for="level" class="block text-sm font-medium text-gray-700">Seviye</label>
            <select id="level" wire:model="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="beginner">Başlangıç</option>
                <option value="intermediate">Orta</option>
                <option value="advanced">İleri</option>
            </select>
            @error('level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Fiyatlar -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Fiyat</label>
                <input type="number" id="price" wire:model="price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="original_price" class="block text-sm font-medium text-gray-700">Orijinal Fiyat</label>
                <input type="number" id="original_price" wire:model="original_price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('original_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Durum -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Durum</label>
            <select id="status" wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="draft">Taslak</option>
                <option value="published">Yayında</option>
                <option value="archived">Arşivlendi</option>
            </select>
            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Kazanımlar -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Kazanımlar</label>
            <div class="flex mb-2">
                <input type="text" wire:model="newOutcome" placeholder="Yeni kazanım ekle" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" wire:click="addOutcome" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Ekle</button>
            </div>
            <ul class="list-disc pl-5">
                @foreach($outcomes as $index => $outcome)
                    <li class="flex justify-between items-center">
                        {{ $outcome }}
                        <button type="button" wire:click="removeOutcome({{ $index }})" class="text-red-500 hover:text-red-700">Sil</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Önkoşullar -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Önkoşullar</label>
            <div class="flex mb-2">
                <input type="text" wire:model="newPrerequisite" placeholder="Yeni önkoşul ekle" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" wire:click="addPrerequisite" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Ekle</button>
            </div>
            <ul class="list-disc pl-5">
                @foreach($prerequisites as $index => $prerequisite)
                    <li class="flex justify-between items-center">
                        {{ $prerequisite }}
                        <button type="button" wire:click="removePrerequisite({{ $index }})" class="text-red-500 hover:text-red-700">Sil</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Kaydet Butonu -->
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                {{ $courseId ? 'Güncelle' : 'Oluştur' }}
            </button>
        </div>
    </form>
</div>
