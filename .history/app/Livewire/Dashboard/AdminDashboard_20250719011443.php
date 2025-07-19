<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use App\Services\DashboardService;

class AdminDashboard extends Component
{
     public $totalUsers;
    public $totalCourses;
    public $activeEnrollments;
    public $monthlyRevenue;
    public $enrollmentTrends;
    public $recentUsers;

    protected $dashboardService;

    public function boot(AdminDashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->totalUsers = $this->dashboardService->getTotalUsers();
        $this->totalCourses = $this->dashboardService->getTotalCourses();
        $this->activeEnrollments = $this->dashboardService->getActiveEnrollments();
        $this->monthlyRevenue = $this->dashboardService->getMonthlyRevenue();
        $this->enrollmentTrends = $this->dashboardService->getEnrollmentTrends();
        $this->recentUsers = $this->dashboardService->getRecentUsers();

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
