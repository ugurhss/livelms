<?php

namespace App\Services;

use App\Interfaces\DashboardRepositoryInterface;
use App\Interfaces\InstructorDashboardServiceInterface;



class InstructorDashboardService implements InstructorDashboardServiceInterface
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getTotalCourses(int $instructorId): int
    {
        return $this->dashboardRepository->countInstructorCourses($instructorId);
    }

    public function getTotalStudents(int $instructorId): int
    {
        return $this->dashboardRepository->countInstructorStudents($instructorId);
    }

    public function getTotalAssignments(int $instructorId): int
    {
        return $this->dashboardRepository->countInstructorAssignments($instructorId);
    }

    public function getRecentSubmissions(int $instructorId, int $limit = 5): array
    {
        return $this->dashboardRepository->getInstructorRecentSubmissions($instructorId, $limit);
    }

    public function getCoursePerformance(int $instructorId): array
    {
        return $this->dashboardRepository->getInstructorCoursePerformance($instructorId);
    }
}
