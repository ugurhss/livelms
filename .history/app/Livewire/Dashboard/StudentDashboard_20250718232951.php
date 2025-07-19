<?php

namespace App\Livewire\Dashboard;

use App\Services\DashboardService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StudentDashboard extends Component
{
    public $istatistikler = [];
    public $kurslar = [];
    public $bekleyen_odevler = [];
    public $tamamlanan_odevler = [];
    public $son_etkinlikler = [];
    public $yaklasan_teslimler = [];

    protected $dashboardService;

    public function boot(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function mount()
    {
        $this->verileriYukle();
    }

    public function verileriYukle()
    {
        $kullaniciId = Auth::id();

        // İstatistikleri al
        $this->istatistikler = $this->dashboardService->getUserStats($kullaniciId);

        // Kursları al ve doğru formata dönüştür
        $courses = $this->dashboardService->getStudentCoursesWithProgress($kullaniciId);
        $this->kurslar = $this->formatKurslar($courses);

        $this->son_etkinlikler = $this->dashboardService->getRecentActivities($kullaniciId);
        $this->yaklasan_teslimler = $this->dashboardService->getUpcomingDeadlines($kullaniciId);
        $this->odevleriYukle($kullaniciId);
    }

    protected function formatKurslar(array $courses): array
    {
        return array_map(function($course) {
            return [
                'id' => $course['id'] ?? null,
                'title' => $course['title'] ?? 'Bilinmeyen Kurs',
                'thumbnail' => $course['thumbnail'] ?? asset('images/default-course.png'),
                'instructor' => $course['instructor'] ?? 'Bilinmeyen Eğitmen',
                'progress' => $course['progress'] ?? 0,
                'completed' => $course['completed'] ?? false
            ];
        }, $courses);
    }

    protected function odevleriYukle($kullaniciId)
    {
        // Örnek veri - Gerçek uygulamada veritabanından çekilecek
        $this->bekleyen_odevler = [
            [
                'id' => 1,
                'title' => 'Final Proje Teslimi',
                'course' => ['title' => 'Laravel İleri Seviye'],
                'due_date' => now()->addDays(3)->format('Y-m-d H:i:s')
            ]
        ];

        $this->tamamlanan_odevler = [
            [
                'id' => 2,
                'title' => 'Bölüm 3 Quiz',
                'course' => ['title' => 'Vue.js Temelleri'],
                'due_date' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'grade' => '95/100'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.student-dashboard')
            ->layout('layouts.app', [
                'title' => 'Öğrenci Paneli',
                'role' => 'student'
            ]);
    }
}
