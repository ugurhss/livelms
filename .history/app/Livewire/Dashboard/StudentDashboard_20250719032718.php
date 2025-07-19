<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Services\StudentDashboardService;

class StudentDashboard extends Component
{

 public StudentDashboardService $dashboardService;
    public array $data = [];

    // Servisleri enjekte ediyoruz


public function mount(StudentDashboardService $dashboardService)
{
        $this->dashboardService = $dashboardService;


}
       public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [

        ]);
    }


}
