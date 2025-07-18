<div>
    <form wire:submit="save">
        <!-- Başlık -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Kurs Başlığı</label>
            <input wire:model="title" type="text" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Açıklama -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Açıklama</label>
            <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Seviye -->
        <div class="mb-4">
            <label for="level" class="block text-sm font-medium text-gray-700">Seviye</label>
            <select wire:model="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="beginner">Başlangıç</option>
                <option value="intermediate">Orta</option>
                <option value="advanced">İleri</option>
            </select>
        </div>

        <!-- Fiyat -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Fiyat</label>
                <input wire:model="price" type="number" step="0.01" id="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="original_price" class="block text-sm font-medium text-gray-700">İndirimli Fiyat (Opsiyonel)</label>
                <input wire:model="original_price" type="number" step="0.01" id="original_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>

        <!-- Durum -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Durum</label>
            <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="draft">Taslak</option>
                <option value="published">Yayında</option>
                <option value="archived">Arşivlendi</option>
            </select>
        </div>

        <!-- Kazanımlar -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Kazanımlar</label>
            <div class="flex gap-2 mt-1">
                <input wire:model="newOutcome" type="text" class="flex-1 rounded-md border-gray-300 shadow-sm" placeholder="Yeni kazanım ekle">
                <button wire:click.prevent="addOutcome" type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Ekle
                </button>
            </div>
            @error('newOutcome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <ul class="mt-2 space-y-1">
                @foreach($outcomes as $index => $outcome)
                    <li class="flex items-center justify-between bg-gray-100 p-2 rounded">
                        <span>{{ $outcome }}</span>
                        <button wire:click.prevent="removeOutcome({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Önkoşullar -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Önkoşullar</label>
            <div class="flex gap-2 mt-1">
                <input wire:model="newPrerequisite" type="text" class="flex-1 rounded-md border-gray-300 shadow-sm" placeholder="Yeni önkoşul ekle">
                <button wire:click.prevent="addPrerequisite" type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Ekle
                </button>
            </div>
            @error('newPrerequisite') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <ul class="mt-2 space-y-1">
                @foreach($prerequisites as $index => $prerequisite)
                    <li class="flex items-center justify-between bg-gray-100 p-2 rounded">
                        <span>{{ $prerequisite }}</span>
                        <button wire:click.prevent="removePrerequisite({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Kaydet Butonu -->
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                Kursu Oluştur
            </button>
        </div>
    </form>

    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>
