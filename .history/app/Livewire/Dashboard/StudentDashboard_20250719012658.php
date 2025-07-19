<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Assignment;
use App\Services\CourseService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\StudentDashboardServiceInterface;

class StudentDashboard extends Component
{
public $enrolledCourses = [];
    public $activeAssignments;
    public $completedAssignments;
    public $courseProgress;
    public $recentAnnouncements;

    protected $dashboardService;

    public function boot(StudentDashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $studentId = Auth::id();
        $this->enrolledCourses = $this->dashboardService->getEnrolledCourses($studentId);
        $this->activeAssignments = $this->dashboardService->getActiveAssignments($studentId);
        $this->completedAssignments = $this->dashboardService->getCompletedAssignments($studentId);
        $this->courseProgress = $this->dashboardService->getCourseProgress($studentId);
        $this->recentAnnouncements = $this->dashboardService->getRecentAnnouncements($studentId);
    }    public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [

        ]);
    }


}
