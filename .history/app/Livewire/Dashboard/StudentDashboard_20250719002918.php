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

    protected string $layout = 'layouts.app';

    public function loadDashboardData(DashboardService $dashboardService, CourseService $courseService): void
    {
        try {
            $userId = Auth::id();

            $this->stats = $dashboardService->getUserStats($userId) ?? [];
            $this->courses = $courseService->getStudentCoursesWithProgress($userId) ?? [];
            $this->enrolledCoursesCount = count($this->courses);
            $this->recentActivities = $dashboardService->getRecentActivities($userId) ?? [];
            $this->upcomingDeadlines = $dashboardService->getUpcomingDeadlines($userId) ?? [];

            $this->loadAssignments();
        } catch (\Exception $e) {
            session()->flash('error', 'Dashboard verileri yüklenirken bir hata oluştu.');
            logger()->error('Dashboard load error: '.$e->getMessage());
        }
    }

    protected function loadAssignments(): void
    {
        try {
            $assignments = Assignment::with(['course:id,title'])
                ->whereHas('course.enrollments', fn($q) => $q->where('user_id', Auth::id()))
                ->get();

            $this->pendingAssignments = $assignments->where('completed', false)->values()->toArray();
            $this->completedAssignments = $assignments->where('completed', true)->values()->toArray();
        } catch (\Exception $e) {
            $this->pendingAssignments = $this->completedAssignments = [];
        }
    }

    public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [
            'stats' => $this->stats,
            'courses' => $this->courses,
            'pendingAssignments' => $this->pendingAssignments,
            'completedAssignments' => $this->completedAssignments,
            'recentActivities' => $this->recentActivities,
            'enrolledCoursesCount' => $this->enrolledCoursesCount,
            'upcomingDeadlines' => $this->upcomingDeadlines
        ]);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="p-4 space-y-4">
            <div class="animate-pulse flex space-x-4">
                <div class="flex-1 space-y-4 py-1">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
