<?php

namespace App\Livewire\Dashboard;

use App\Interfaces\InstructorDashboardServiceInterface as InterfacesInstructorDashboardServiceInterface;
use Carbon\Carbon;
use App\Models\Course;
use Livewire\Component;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

use App\Services\Interfaces\InstructorDashboardServiceInterface;

class InstructorDashboard extends Component
{



   public int $totalCourses = 0;
    public int $totalStudents = 0;
    public int $totalAssignments = 0;
    public array $recentSubmissions = [];
    public array $coursePerformance = [];


    public function mount(InterfacesInstructorDashboardServiceInterface $dashboardService)
    {


        $instructorId = Auth::id();

        $this->totalCourses = $dashboardService->getTotalCourses($instructorId);
        $this->totalStudents = $dashboardService->getTotalStudents($instructorId);
        $this->totalAssignments = $dashboardService->getTotalAssignments($instructorId);
        $this->recentSubmissions = $dashboardService->getRecentSubmissions($instructorId);
        $this->coursePerformance = $dashboardService->getCoursePerformance($instructorId);
    }

    public function render()
    {
        return view('livewire.dashboard.instructor-dashboard', [

        ]);
    }
}
