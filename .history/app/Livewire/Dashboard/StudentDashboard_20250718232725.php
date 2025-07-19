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

        $this->istatistikler = $this->dashboardService->getUserStats($kullaniciId);
        $this->kurslar = $this->dashboardService->getStudentCoursesWithProgress($kullaniciId);
        $this->son_etkinlikler = $this->dashboardService->getRecentActivities($kullaniciId);
        $this->yaklasan_teslimler = $this->dashboardService->getUpcomingDeadlines($kullaniciId);
        $this->odevleriYukle($kullaniciId);
    }

    protected function odevleriYukle($kullaniciId)
    {
        // Ödev yükleme işlemleri
        $this->bekleyen_odevler = []; // Gerçek verilerle doldurulacak
        $this->tamamlanan_odevler = []; // Gerçek verilerle doldurulacak
    }

    public function render()
    {
        return view('livewire.dashboard.student-dashboard');

    }


}
