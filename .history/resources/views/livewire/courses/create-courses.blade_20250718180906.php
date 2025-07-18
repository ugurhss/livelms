<div>
    <form wire:submit.prevent="submit">
        <!-- Başlık -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Kurs Başlığı</label>
            <input wire:model="title" type="text" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Açıklama -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Kurs Açıklaması</label>
            <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Seviye -->
        <div class="mb-4">
            <label for="level" class="block text-sm font-medium text-gray-700">Zorluk Seviyesi</label>
            <select wire:model="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="beginner">Başlangıç</option>
                <option value="intermediate">Orta</option>
                <option value="advanced">İleri</option>
            </select>
        </div>

        <!-- Fiyatlar -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Fiyat</label>
                <input wire:model="price" type="number" step="0.01" id="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="original_price" class="block text-sm font-medium text-gray-700">Orijinal Fiyat (İndirimli ise)</label>
                <input wire:model="original_price" type="number" step="0.01" id="original_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <!-- Durum -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Durum</label>
            <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="draft">Taslak</option>
                <option value="published">Yayında</option>
                <option value="archived">Arşivlendi</option>
            </select>
        </div>

        <!-- Kazanımlar -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Kazanımlar</label>
            <div class="mt-1 flex">
                <input wire:model="newOutcome" type="text" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Yeni kazanım ekle">
                <button wire:click.prevent="addOutcome" type="button" class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Ekle
                </button>
            </div>
            <ul class="mt-2 space-y-1">
                @foreach($outcomes as $index => $outcome)
                    <li class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded">
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
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Önkoşullar</label>
            <div class="mt-1 flex">
                <input wire:model="newPrerequisite" type="text" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Yeni önkoşul ekle">
                <button wire:click.prevent="addPrerequisite" type="button" class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Ekle
                </button>
            </div>
            <ul class="mt-2 space-y-1">
                @foreach($prerequisites as $index => $prerequisite)
                    <li class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded">
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

        <!-- Gönder Butonu -->
        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kursu Oluştur
            </button>
        </div>
    </form>
</div>
