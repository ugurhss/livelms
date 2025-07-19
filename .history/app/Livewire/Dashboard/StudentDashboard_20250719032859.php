<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Services\StudentDashboardService;

class StudentDashboard extends Component
{

 public StudentDashboardService $dashboardService;
    public array $enrolledCourses = [];
    public int $activeAssignments = 0;
    public int $completedAssignments = 0;
    public float|int $courseProgress = 0;  // progress yüzdelik olabilir, tip float veya int olabilir
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
