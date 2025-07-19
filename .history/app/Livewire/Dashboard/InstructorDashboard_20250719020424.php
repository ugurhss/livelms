<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Course;
use Livewire\Component;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

use App\Services\Interfaces\InstructorDashboardServiceInterface;

class InstructorDashboard extends Component
{


    public function render()
    {
        return view('livewire.dashboard.instructor-dashboard', [

        ]);
    }
}
