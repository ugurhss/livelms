<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{

    public $user;
    public $stats = [];
    public $recentActivities = [];
    public $upcomingDeadlines = [];

    protected $dashboardService;

    public function mount(DashboardService $dashboardService)
    {
        $this->user = Auth::user();
        $this->dashboardService = $dashboardService;
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->stats = $this->dashboardService->getUserStats($this->user->id);
        $this->recentActivities = $this->dashboardService->getRecentActivities($this->user->id);
        $this->upcomingDeadlines = $this->dashboardService->getUpcomingDeadlines($this->user->id);

        // Rol bazlı ek verileri yükle
        $this->loadRoleSpecificData();
    }

    protected function loadRoleSpecificData()
    {
        if (method_exists($this, $method = 'load'.ucfirst($this->user->role).'Data')) {
            $this->$method();
        }
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatchBrowserEvent('notify', ['message' => 'Veriler güncellendi!', 'type' => 'success']);
    }

   protected $layout = 'layouts.app';

public function render()
{
    return view('livewire.dashboard.user-dashboard')
            ->layout($this->layout); // layout metodu düzeltildi
}
}
