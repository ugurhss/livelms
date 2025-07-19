<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use App\Services\DashboardService;
use App\Services\Interfaces\AdminDashboardServiceInterface;

class AdminDashboard extends Component
{    public function render()
    {
        return view('livewire.dashboard.admin-dashboard', [
           ]);
    }
}
