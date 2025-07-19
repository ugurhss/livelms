<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Services\StudentDashboardService;

class StudentDashboard extends Component
{

    public int $getEnrolledCourses = 0;
    public int $totalAssignments = 0;
    public int $totalGrades = 0;
    public array $recentAnnouncements = [];
    public array $recentSubmissions = [];

    // Servisleri enjekte ediyoruz


public function mount(StudentDashboardService $dashboardService)
{
    $this->getEnrolledCourses = $dashboardService->getEnrolledCourses();
    $this->totalAssignments = $dashboardService->getTotalAssignments();
    $this->totalGrades = $dashboardService->getTotalGrades();
    $this->recentAnnouncements = $dashboardService->getRecentAnnouncements();
    $this->recentSubmissions = $dashboardService->getRecentSubmissions();

}
       public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [

        ]);
    }


}
