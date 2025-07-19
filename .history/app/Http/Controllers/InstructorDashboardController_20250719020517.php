<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\InstructorDashboardServiceInterface;
use Illuminate\Support\Facades\Auth;

class InstructorDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(InstructorDashboardServiceInterface $dashboardService)
    {
        $this->middleware('auth');
        $this->middleware('role:instructor'); // Sadece eğitmenler erişebilir
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $instructorId = Auth::id(); // Giriş yapan eğitmenin ID'si

        $data = [
            'totalCourses' => $this->dashboardService->getTotalCourses($instructorId),
            'totalStudents' => $this->dashboardService->getTotalStudents($instructorId),
            'totalAssignments' => $this->dashboardService->getTotalAssignments($instructorId),
            'recentSubmissions' => $this->dashboardService->getRecentSubmissions($instructorId),
            'coursePerformance' => $this->dashboardService->getCoursePerformance($instructorId)
        ];

        return view('dashboard', $data);
    }
}
