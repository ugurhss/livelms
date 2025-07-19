<?php

namespace App\Interfaces;

interface InstructorDashboardServiceInterface
{
    public function getTotalCourses(int $instructorId): int;
    public function getTotalStudents(int $instructorId): int;
    public function getTotalAssignments(int $instructorId): int;
    public function getRecentSubmissions(int $instructorId, int $limit = 5): array;
    public function getCoursePerformance(int $instructorId): array;
}
