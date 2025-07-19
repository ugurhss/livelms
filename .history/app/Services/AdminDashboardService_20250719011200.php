<?php

namespace App\Services;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Services\Interfaces\AdminDashboardServiceInterface;

class AdminDashboardService implements AdminDashboardServiceInterface
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getTotalUsers(): int
    {
        return $this->dashboardRepository->countTotalUsers();
    }

    public function getTotalCourses(): int
    {
        return $this->dashboardRepository->countTotalCourses();
    }

    public function getActiveEnrollments(): int
    {
        return $this->dashboardRepository->countActiveEnrollments();
    }

    public function getMonthlyRevenue(): float
    {
        return $this->dashboardRepository->calculateMonthlyRevenue();
    }

    public function getEnrollmentTrends(): array
    {
        return $this->dashboardRepository->getEnrollmentTrends();
    }

    public function getRecentUsers(int $limit = 5): array
    {
        return $this->dashboardRepository->getRecentUsers($limit);
    }
}
