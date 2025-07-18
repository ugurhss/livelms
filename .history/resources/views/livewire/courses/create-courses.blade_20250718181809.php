<div class="container mx-auto px-4 py-8">
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <!-- Başlık -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                Kurs Başlığı *
            </label>
            <input wire:model="title" type="text" id="title"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Açıklama -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Kurs Açıklaması *
            </label>
            <textarea wire:model="description" id="description" rows="5"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Seviye -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Seviye *
            </label>
            <select wire:model="level" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="beginner">Başlangıç</option>
                <option value="intermediate">Orta</option>
                <option value="advanced">İleri</option>
            </select>
        </div>

        <!-- Fiyatlar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                    Fiyat (₺) *
                </label>
                <input wire:model="price" type="number" step="0.01" id="price"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="original_price">
                    İndirimli Fiyat (₺)
                </label>
                <input wire:model="original_price" type="number" step="0.01" id="original_price"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <!-- Durum -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Durum *
            </label>
            <select wire:model="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="draft">Taslak</option>
                <option value="published">Yayında</option>
                <option value="archived">Arşiv</option>
            </select>
        </div>

        <!-- Kazanımlar -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Kazanımlar
            </label>
            <div class="flex mb-2">
                <input wire:model="newOutcome" type="text" placeholder="Yeni kazanım ekle"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <button wire:click.prevent="addOutcome" type="button"
                        class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Ekle
                </button>
            </div>
<ul class="space-y-2">
    @forelse($outcomes as $index => $outcome)
        <li class="flex justify-between items-center bg-gray-50 p-2 rounded hover:bg-gray-100 transition-colors">
            <span class="text-gray-800">{{ $outcome }}</span>
            <button
                wire:click.prevent="removeOutcome({{ $index }})"
                type="button"
                class="text-red-500 hover:text-red-700 focus:outline-none"
                aria-label="Kazanımı sil"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </li>
    @empty
        <li class="text-gray-500 italic py-2">Henüz kazanım eklenmedi</li>
    @endforelse
</ul>
        </div>

        <!-- Önkoşullar -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Önkoşullar
            </label>
            <div class="flex mb-2">
                <input wire:model="newPrerequisite" type="text" placeholder="Yeni önkoşul ekle"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <button wire:click.prevent="addPrerequisite" type="button"
                        class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Ekle
                </button>
            </div>

            <ul class="space-y-2">
                @forelse($prerequisites as $index => $prerequisite)
                    <li class="flex justify-between items-center bg-gray-50 p-2 rounded">
                        <span>{{ $prerequisite }}</span>
                        <button wire:click.prevent="removePrerequisite({{ $index }})" type="button"
                                class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </li>
                @empty
                    <li class="text-gray-500">Henüz önkoşul eklenmedi</li>
                @endforelse
            </ul>
        </div>

        <!-- Gönder Butonu -->
        <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Kursu Kaydet
            </button>
        </div>
    </form>
</div>
