<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EnrollmentRepositoryInterface
{
    public function enrollUser(int $courseId, int $userId);
    public function unenrollUser(int $courseId, int $userId);
    public function getEnrollmentsByUser(int $userId): LengthAwarePaginator;
    public function getEnrollmentsByCourse(int $courseId): LengthAwarePaginator;

    // İstatistikler
    public function countUserEnrollments(int $userId): int;
    public function countCompletedCourses(int $userId): int;
    public function countInProgressCourses(int $userId): int;
    public function calculateCompletionRate(int $userId): float;
    public function countActiveEnrollments(): int;
    public function calculateMonthlyRevenue(): float;
    public function getEnrollmentTrends(int $months = 6): array;
}
