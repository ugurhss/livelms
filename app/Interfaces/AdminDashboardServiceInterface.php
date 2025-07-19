<?php

namespace App\Interfaces;

interface AdminDashboardServiceInterface
{
    public function getTotalUsers(): int;
    public function getTotalCourses(): int;
    public function getActiveEnrollments(): int;
    public function getMonthlyRevenue(): float;
    public function getEnrollmentTrends(): array;
    public function getRecentUsers(int $limit = 5): array;
}
