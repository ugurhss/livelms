<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Assignment;
use App\Services\CourseService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class StudentDashboard extends Component
{
    public array $stats = [];
    public array $courses = [];
    public array $pendingAssignments = [];
    public array $completedAssignments = [];
    public array $recentActivities = [];
    public int $enrolledCoursesCount = 0;
    public array $upcomingDeadlines = [];

    protected string $dashboardService;
    protected string $courseService;

    protected string $layout = 'layouts.app';

    // Remove the constructor

    // Use mount method with dependency injection
    public function mount(DashboardService $dashboardService, CourseService $courseService)
    {
        $this->dashboardService = $dashboardService;
        $this->courseService = $courseService;

        $this->initializeDashboard();
    }

    protected function initializeDashboard(): void
    {
        try {
            $userId = Auth::id();

            $this->stats = $this->dashboardService->getUserStats($userId) ?? [];
            $this->courses = $this->courseService->getStudentCoursesWithProgress($userId) ?? [];
            $this->enrolledCoursesCount = count($this->courses);
            $this->recentActivities = $this->dashboardService->getRecentActivities($userId) ?? [];
            $this->upcomingDeadlines = $this->dashboardService->getUpcomingDeadlines($userId) ?? [];

            $this->loadAssignments();
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            session()->flash('error', 'Dashboard verileri yüklenirken bir hata oluştu.');
        }
    }

    protected function loadAssignments(): void
    {
        try {
            $assignments = Assignment::query()
                ->whereHas('course.enrollments', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with(['course' => function($query) {
                    $query->select('id', 'title');
                }])
                ->get();

            $this->pendingAssignments = $assignments->where('completed', false)->values()->toArray();
            $this->completedAssignments = $assignments->where('completed', true)->values()->toArray();
        } catch (\Exception $e) {
            $this->pendingAssignments = [];
            $this->completedAssignments = [];
        }
    }

    public function render()
    {
        return view('livewire.dashboard.student-dashboard')
            ->layout($this->layout, [
                'title' => 'Öğrenci Paneli',
                'description' => 'Öğrenci dashboard sayfası'
            ]);
    }
}
