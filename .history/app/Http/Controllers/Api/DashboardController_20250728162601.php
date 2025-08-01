<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\DashboardService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): JsonResponse
    {
        try {
            $userId = Auth::id(); // auth()  Auth facade kullan

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $this->dashboardService->getUserStats($userId),
                    'recent_activities' => $this->dashboardService->getRecentActivities($userId),
                    'upcoming_deadlines' => $this->dashboardService->getUpcomingDeadlines($userId),
                    'role_specific_data' => $this->getRoleSpecificData($userId)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function getRoleSpecificData(int $userId): array
    {
            $userId = Auth::id(); //

        switch ($userId->role) {
            case 'student':
                return [
                    'courses' => $this->dashboardService->getStudentCoursesWithProgress($userId)
                ];
            case 'instructor':
                return [
                    'courses' => $this->dashboardService->getInstructorCourses($userId)
                ];
            case 'admin':
                return [
                    'system_stats' => $this->dashboardService->getSystemStats()
                ];
            default:
                return [];
        }
    }
}
