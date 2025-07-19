<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Course;
use Livewire\Component;
use App\Models\Enrollment;
use App\Services\Interfaces\InstructorDashboardServiceInterface;

class InstructorDashboard extends Component
{

    public $recentSubmissions=  [];
    public $coursePerformance= [];

    public $totalCourses = 0;
public $totalStudents = 0;
public $totalAssignments = 0;
    protected $dashboardService;

    public function boot(InstructorDashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $instructorId = Auth::id();
        $this->totalCourses = $this->dashboardService->getTotalCourses($instructorId);
        $this->totalStudents = $this->dashboardService->getTotalStudents($instructorId);
        $this->totalAssignments = $this->dashboardService->getTotalAssignments($instructorId);
        $this->recentSubmissions = $this->dashboardService->getRecentSubmissions($instructorId);
        $this->coursePerformance = $this->dashboardService->getCoursePerformance($instructorId);
    }

    public function render()
    {
        return view('livewire.dashboard.instructor-dashboard', [

        ]);
    }
}
