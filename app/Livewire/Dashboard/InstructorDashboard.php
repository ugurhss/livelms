<?php

namespace App\Livewire\Dashboard;

use App\Models\Enrollment;
use App\Models\Course;
use Carbon\Carbon;
use Livewire\Component;

class InstructorDashboard extends UserDashboard
{
    public $courses = [];
    public $earningsData = [];
    public $recentEnrollments = [];
      protected $layout = 'layouts.app';

    public function loadDashboardData()
    {
        parent::loadDashboardData();
        $this->courses = $this->dashboardService->getInstructorCourses($this->user->id);
        $this->loadEarningsData();
        $this->loadRecentEnrollments();
    }

    protected function loadEarningsData()
    {
        // Son 6 aylık kazanç verileri
        $months = [];
        $earnings = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $earnings[] = Course::where('user_id', $this->user->id)
                ->whereHas('enrollments', function($query) use ($date) {
                    $query->whereMonth('created_at', $date->month)
                          ->whereYear('created_at', $date->year);
                })
                ->withSum('enrollments as revenue', 'price')
                ->get()
                ->sum('revenue') * 0.7; // %30 platform payı
        }

        $this->earningsData = [
            'labels' => $months,
            'data' => $earnings
        ];
    }

    protected function loadRecentEnrollments()
    {
        $this->recentEnrollments = Enrollment::query()
            ->whereHas('course', function($query) {
                $query->where('user_id', $this->user->id);
            })
            ->with(['user', 'course'])
            ->latest()
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.instructor-dashboard', [
            'stats' => $this->stats,
            'activities' => $this->recentActivities,
            'courses' => $this->courses,
            'earningsData' => $this->earningsData,
            'recentEnrollments' => $this->recentEnrollments
        ])->layout('layouts.app', [
            'title' => 'Eğitmen Paneli',
            'role' => 'instructor'
        ]);
    }
}
