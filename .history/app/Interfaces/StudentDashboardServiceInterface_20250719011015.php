<?php

namespace App\Services\Interfaces;

interface StudentDashboardServiceInterface
{
    public function getEnrolledCourses(int $studentId): array;
    public function getActiveAssignments(int $studentId): array;
    public function getCompletedAssignments(int $studentId): array;
    public function getCourseProgress(int $studentId): array;
    public function getRecentAnnouncements(int $studentId, int $limit = 5): array;
}
