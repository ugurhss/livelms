<?php

namespace App\Interfaces;

interface DashboardRepositoryInterface
{
    // Admin methods
    public function countTotalUsers(): int;
    public function countTotalCourses(): int;
    public function countActiveEnrollments(): int;
    public function calculateMonthlyRevenue(): float;
    public function getEnrollmentTrends(int $months = 12): array;
    public function getRecentUsers(int $limit = 5): array;

    // Instructor methods
    public function countInstructorCourses(int $instructorId): int;
    public function countInstructorStudents(int $instructorId): int;
    public function countInstructorAssignments(int $instructorId): int;
    public function getInstructorRecentSubmissions(int $instructorId, int $limit = 5): array;
    public function getInstructorCoursePerformance(int $instructorId): array;

    // Student methods
    public function getStudentEnrolledCourses(int $studentId): array;
    public function getStudentActiveAssignments(int $studentId): array;
    public function getStudentCompletedAssignments(int $studentId): array;
    public function getStudentCourseProgress(int $studentId): array;
    public function getStudentRecentAnnouncements(int $studentId, int $limit = 5): array;
}
