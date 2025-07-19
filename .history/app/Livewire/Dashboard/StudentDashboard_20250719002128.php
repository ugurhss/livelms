<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Assignment;
use App\Services\CourseService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class StudentDashboard extends Component
{
    public array $stats = [];
    public array $courses = [];
    public array $pendingAssignments = [];
    public array $completedAssignments = [];
    public array $recentActivities = [];
    public int $enrolledCoursesCount = 0;
    public array $upcomingDeadlines = [];

    protected DashboardService $dashboardService;
    protected string $layout = 'layouts.app';
    protected CourseService $courseService;

    public function mount(DashboardService $dashboardService, CourseService $courseService): void
    {
        $this->courseService = $courseService;
        $this->dashboardService = $dashboardService;
        $this->loadDashboardData();
    }

    public function loadDashboardData(): void
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
            // Log the error if needed
        }
    }

    public function placeholder(): string
    {
        return view('components.loading-state', ['message' => 'Öğrenci paneli yükleniyor...']);
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
