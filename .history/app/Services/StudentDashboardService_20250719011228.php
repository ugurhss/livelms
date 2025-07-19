<?php

namespace App\Services;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Services\Interfaces\StudentDashboardServiceInterface;

class StudentDashboardService implements StudentDashboardServiceInterface
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getEnrolledCourses(int $studentId): array
    {
        return $this->dashboardRepository->getStudentEnrolledCourses($studentId);
    }

    public function getActiveAssignments(int $studentId): array
    {
        return $this->dashboardRepository->getStudentActiveAssignments($studentId);
    }

    public function getCompletedAssignments(int $studentId): array
    {
        return $this->dashboardRepository->getStudentCompletedAssignments($studentId);
    }

    public function getCourseProgress(int $studentId): array
    {
        return $this->dashboardRepository->getStudentCourseProgress($studentId);
    }

    public function getRecentAnnouncements(int $studentId, int $limit = 5): array
    {
        return $this->dashboardRepository->getStudentRecentAnnouncements($studentId, $limit);
    }
}
