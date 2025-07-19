<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use App\Services\DashboardService;
use App\Interfaces\AdminDashboardServiceInterface;

class AdminDashboard extends Component
{

    public int $totalUsers = 0;
public int $totalCourses = 0;
    public int $activeEnrollments = 0;
    public float $monthlyRevenue = 0.0;
    public array $enrollmentTrends = [];
    public array $recentUsers = [];
    public int $recentUsersLimit = 5; // Default limit for recent users

public function mount(AdminDashboardServiceInterface $dashboardService)
{
    $this->totalUsers = $dashboardService->getTotalUsers();
    $this->totalCourses = $dashboardService->getTotalCourses();
    $this->activeEnrollments = $dashboardService->getActiveEnrollments();
    $this->monthlyRevenue = $dashboardService->getMonthlyRevenue();
    $this->enrollmentTrends = $dashboardService->getEnrollmentTrends();
    $this->recentUsers = $dashboardService->getRecentUsers();
    $this->recentUsersLimit = 5; // Default limit for recent users
}

    public function render()
    {
        return view('livewire.dashboard.admin-dashboard', [
           ]);
    }
}
