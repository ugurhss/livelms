<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Quiz Düzenle: {{ $quiz->title }}</div>

                <div class="card-body">
                    <form wire:submit.prevent="submit">
                        <div class="mb-3">
                            <label for="title" class="form-label">Quiz Başlığı</label>
                            <input type="text" wire:model="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea wire:model="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      id="description" rows="3"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                <input type="datetime-local" wire:model="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Bitiş Tarihi</label>
                                <input type="datetime-local" wire:model="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="time_limit" class="form-label">Süre Limiti (dakika)</label>
                                <input type="number" wire:model="time_limit"
                                       class="form-control @error('time_limit') is-invalid @enderror"
                                       id="time_limit">
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="passing_score" class="form-label">Geçme Notu (%)</label>
                                <input type="number" wire:model="passing_score"
                                       class="form-control @error('passing_score') is-invalid @enderror"
                                       id="passing_score" min="1" max="100">
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" wire:model="is_published"
                                   class="form-check-input"
                                   id="is_published">
                            <label class="form-check-label" for="is_published">Yayınla</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove>Güncelle</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Güncelleniyor...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
