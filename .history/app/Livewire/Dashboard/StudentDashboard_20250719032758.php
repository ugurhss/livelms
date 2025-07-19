<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Services\StudentDashboardService;

class StudentDashboard extends Component
{

 public StudentDashboardService $dashboardService;
    public array $data = [];
    public int $enrolledCourses = 0;
    public int $completedCourses = 0;
    public int $totalAssignments = 0;
    public int $totalSubmissions = 0;
    public array $recentAnnouncements = [];

    // Servisleri enjekte ediyoruz


public function mount(StudentDashboardService $dashboardService)
{
        $this->dashboardService = $dashboardService;


}
       public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [

        ]);
    }


}
