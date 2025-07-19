<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Services\StudentDashboardService;

class StudentDashboard extends Component
{



public function mount(StudentDashboardService $dashboardService)
{
    $this->totalUsers = $dashboardService->getTotalUsers();
    $this->totalCourses = $dashboardService->getTotalCourses();
    $this->activeEnrollments = $dashboardService->getActiveEnrollments();
    $this->monthlyRevenue = $dashboardService->getMonthlyRevenue();
    $this->enrollmentTrends = $dashboardService->getEnrollmentTrends();
    $this->recentUsers = $dashboardService->getRecentUsers();
    $this->recentUsersLimit = 5; // Default limit for recent users
}
       public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [

        ]);
    }


}
