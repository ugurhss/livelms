<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\StudentDashboardServiceInterface;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(StudentDashboardServiceInterface $dashboardService)
    {
        $this->middleware('auth');
        $this->middleware('role:student'); // Sadece öğrenciler erişebilir
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $studentId = Auth::id(); // Giriş yapan öğrencinin ID'si

        $data = [
            'enrolledCourses' => $this->dashboardService->getEnrolledCourses($studentId),
            'activeAssignments' => $this->dashboardService->getActiveAssignments($studentId),
            'completedAssignments' => $this->dashboardService->getCompletedAssignments($studentId),
            'courseProgress' => $this->dashboardService->getCourseProgress($studentId),
            'recentAnnouncements' => $this->dashboardService->getRecentAnnouncements($studentId)
        ];

        return view('student.dashboard', $data);
    }
}
