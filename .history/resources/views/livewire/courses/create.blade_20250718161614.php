<div>
    @if (session()->has('success'))
        <div class="bg-green-100 p-2 rounded">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div>
            <label>Kurs Başlığı</label>
            <input type="text" wire:model="title">
            @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Açıklama</label>
            <textarea wire:model="description"></textarea>
        </div>

        <div>
            <label>Seviye</label>
            <input type="text" wire:model="level">
        </div>

        <div>
            <label>Fiyat</label>
            <input type="number" wire:model="price" step="0.01">
        </div>

        <div>
            <label>Orijinal Fiyat</label>
            <input type="number" wire:model="original_price" step="0.01">
        </div>

        <div>
            <label>Durum</label>
            <select wire:model="status">
                <option value="draft">Taslak</option>
                <option value="published">Yayında</option>
            </select>
        </div>

        <div>
            <label>Kazanımlar</label>
            <textarea wire:model="outcomes" placeholder='["Örnek 1", "Örnek 2"]'></textarea>
        </div>

        <div>
            <label>Önkoşullar</label>
            <textarea wire:model="prerequisites" placeholder='["Temel Bilgi"]'></textarea>
        </div>

        {{-- Ders ekleme gibi bir alan eklemek istersen buraya yazılır --}}

        <button type="submit">Kursu Oluştur</button>
    </form>
</div>
