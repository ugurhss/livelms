<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use App\Services\StudentDashboardService;

class StudentDashboard extends Component
{

public array $enrolledCourses = [];
public array $activeAssignments = [];
public int $completedAssignments = []; // Buraya int bekleniyor
public array $courseProgress = [];
public array $recentAnnouncements = [];



public function mount(StudentDashboardService $dashboardService)
{
  $studentId = Auth::id(); // Giriş yapan öğrencinin ID'si

    $this->enrolledCourses = $dashboardService->getEnrolledCourses($studentId);
    $this->activeAssignments = $dashboardService->getActiveAssignments($studentId);
    $this->completedAssignments = $dashboardService->getCompletedAssignments($studentId);
    $this->courseProgress = $dashboardService->getCourseProgress($studentId);
    $this->recentAnnouncements = $dashboardService->getRecentAnnouncements($studentId);
}

       public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [

        ]);
    }


}
