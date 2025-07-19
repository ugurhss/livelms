<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Assignment;
use App\Services\CourseService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class StudentDashboard extends Component
{
    public $stats = [];
    public $courses = [];
    public $pendingAssignments = [];
    public $completedAssignments = [];
    public $recentActivities = [];
    public $enrolledCoursesCount = 0;
    public $upcomingDeadlines = [];

    protected $dashboardService;
    protected $layout = 'layouts.app';
    protected $courseService;



  public function mount(DashboardService $dashboardService, CourseService $courseService)
{
    $this->courseService = $courseService;
    $this->dashboardService = $dashboardService;

    $this->courses = $this->courseService->getStudentCoursesWithProgress(Auth::id());

    $this->loadDashboardData();
}


    public function loadDashboardData()
    {
        $this->stats = $this->dashboardService->getUserStats(Auth::id());
        $this->courses = $this->courseService->getStudentCoursesWithProgress(Auth::id());
        $this->recentActivities = $this->dashboardService->getRecentActivities(Auth::id());
        $this->upcomingDeadlines = $this->dashboardService->getUpcomingDeadlines(Auth::id());

        $this->loadAssignments();
    }

    protected function loadAssignments()
    {
       $assignments = Assignment::whereHas('course.enrollments', function($query) {
        $query->where('user_id', Auth::id());
    })
    ->with('course')
    ->get();

    $this->pendingAssignments = $assignments->where('completed', false)->values()->toArray();
    $this->completedAssignments = $assignments->where('completed', true)->values()->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.student-dashboard', [
            'stats' => $this->stats,
            'courses' => $this->courses,
            'pendingAssignments' => $this->pendingAssignments,
            'completedAssignments' => $this->completedAssignments,
            'recentActivities' => $this->recentActivities,
               'enrolledCoursesCount' => $this->enrolledCoursesCount,
            'upcomingDeadlines' => $this->upcomingDeadlines
        ]
         );
    }
}
