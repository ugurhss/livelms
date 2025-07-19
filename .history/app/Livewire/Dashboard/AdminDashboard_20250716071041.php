<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use App\Services\DashboardService;

class AdminDashboard extends Component
{
    public $totalUsers = 0;
    public $totalCourses = 0;
    public $activeEnrollments = 0;
    public $monthlyRevenue = 0;
    public $recentUsers = [];
    public $enrollmentTrends = [];

    protected $dashboardService;

public function mount(DashboardService $dashboardService)
{
    $this->dashboardService = $dashboardService;

    // Debug için servis çıktısını kontrol edin
    dd($this->dashboardService->getSystemStats());

    $this->loadDashboardData();
}

    public function loadDashboardData()
    {
        $stats = $this->dashboardService->getSystemStats();

        // Dizi anahtarlarını tek tek atayalım
        $this->totalUsers = $stats['total_users'] ?? 0;
        $this->totalCourses = $stats['total_courses'] ?? 0;
        $this->activeEnrollments = $stats['active_enrollments'] ?? 0;
        $this->monthlyRevenue = $stats['monthly_revenue'] ?? 0;
        $this->enrollmentTrends = $stats['enrollment_trends'] ?? [];

        $this->loadRecentUsers();
    }

    protected function loadRecentUsers()
    {
        $this->recentUsers = User::query()
            ->withCount(['courses', 'enrolledCourses'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'courses_count' => $user->courses_count,
                    'enrollments_count' => $user->enrolled_courses_count,
                    'joined_at' => $user->created_at->format('Y-m-d H:i')
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.admin-dashboard', [
            'totalUsers' => $this->totalUsers,
            'totalCourses' => $this->totalCourses,
            'activeEnrollments' => $this->activeEnrollments,
            'monthlyRevenue' => $this->monthlyRevenue,
            'recentUsers' => $this->recentUsers,
            'enrollmentTrends' => $this->enrollmentTrends
        ])->layout('layouts.app', [
            'title' => 'Yönetici Paneli',
            'role' => 'admin'
        ]);
    }
}
