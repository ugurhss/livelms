<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Services\StudentDashboardService;

class StudentDashboard extends Component
{



    // Servisleri enjekte ediyoruz


public function mount(StudentDashboardService $dashboardService)
{


}
       public function render()
    {
        // Servisleri render metodunda enjekte ediyoruz
        return view('livewire.dashboard.student-dashboard', [

        ]);
    }


}
