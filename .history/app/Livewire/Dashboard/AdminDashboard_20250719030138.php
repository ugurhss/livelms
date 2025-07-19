<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use App\Services\DashboardService;
use App\Interfaces\AdminDashboardServiceInterface;

class AdminDashboard extends Component
{

    public int $totalUsers = 0;

public function mount(AdminDashboardServiceInterface $dashboardService)
{
    $this->totalUsers = $dashboardService->getTotalUsers();
}

    public function render()
    {
        return view('livewire.dashboard.admin-dashboard', [
           ]);
    }
}
