<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\AdminDashboardServiceInterface;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(AdminDashboardServiceInterface $dashboardService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $data = [
            'totalUsers' => $this->dashboardService->getTotalUsers(),
            'totalCourses' => $this->dashboardService->getTotalCourses(),
            'activeEnrollments' => $this->dashboardService->getActiveEnrollments(),
            'monthlyRevenue' => $this->dashboardService->getMonthlyRevenue(),
            'enrollmentTrends' => $this->dashboardService->getEnrollmentTrends(),
            'recentUsers' => $this->dashboardService->getRecentUsers()
        ];

        return view('dashboard', $data);
    }
}
