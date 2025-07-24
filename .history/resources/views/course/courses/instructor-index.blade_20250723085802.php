<x-layouts.app>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Eğitmen Kurslarım</h1>
        <a href="{{ route('courses.courses.create') }}" class="btn btn-success">Yeni Kurs Oluştur</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="stat-card">
                        <h3>{{ $stats['total_courses'] }}</h3>
                        <p class="text-muted">Toplam Kurs</p>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="stat-card">
                        <h3>{{ $stats['total_students'] }}</h3>
                        <p class="text-muted">Toplam Öğrenci</p>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="stat-card">
                        <h3>{{ number_format($stats['average_rating'], 1) }}</h3>
                        <p class="text-muted">Ortalama Puan</p>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="stat-card">
                        <h3>{{ number_format($stats['total_earnings'], 2) }} ₺</h3>
                        <p class="text-muted">Toplam Kazanç</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Kurslarım</h4>
                <div>
                    <form action="{{ route('courses.courses.instructor') }}" method="GET" class="d-inline">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Yayında</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Arşivlenmiş</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kurs Adı</th>
                            <th>Durum</th>
                            <th>Öğrenci Sayısı</th>
                            <th>Ortalama Puan</th>
                            <th>Kazanç</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>
                                    <a href="{{ route('courses.show', $course->id) }}" class="text-decoration-none">
                                        {{ $course->title }}
                                    </a>
                                </td>
                                <td>
                                    @if($course->status == 'published')
                                        <span class="badge bg-success">Yayında</span>
                                    @elseif($course->status == 'draft')
                                        <span class="badge bg-secondary">Taslak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Arşivlenmiş</span>
                                    @endif
                                </td>
                                <td>{{ $course->students_count }}</td>
                                <td>
                                    @if($course->reviews_avg_rating)
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ number_format($course->reviews_avg_rating, 1) }}</span>
                                            <div class="stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($course->reviews_avg_rating))
                                                        <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                    @else
                                                        <i class="far fa-star text-warning" style="font-size: 12px;"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Değerlendirme yok</span>
                                    @endif
                                </td>
                                <td>{{ number_format($course->enrollments_sum_price, 2) }} ₺</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-outline-primary me-2">
                                            Düzenle
                                        </a>
                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu kursu silmek istediğinize emin misiniz?')">
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Henüz hiç kurs oluşturmadınız.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    .stat-card h3 {
        margin-bottom: 5px;
        font-weight: bold;
    }
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
</style>
</x-layouts.app>
