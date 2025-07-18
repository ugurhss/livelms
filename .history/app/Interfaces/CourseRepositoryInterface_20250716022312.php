<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    // Temel CRUD İşlemleri
    public function getAllCourses(array $filters = []): LengthAwarePaginator;
    public function getPublishedCourses(array $filters = []): LengthAwarePaginator;
    public function getCourseById(int $courseId);
    public function createCourse(array $courseDetails);
    public function updateCourse(int $courseId, array $courseDetails);
    public function deleteCourse(int $courseId);

    // Özel Sorgular
    public function getCoursesByInstructor(int $instructorId, array $filters = []): LengthAwarePaginator;
    public function getEnrolledCourses(int $userId): LengthAwarePaginator;
    public function enrollUser(int $courseId, int $userId);
    public function unenrollUser(int $courseId, int $userId);
    public function checkEnrollment(int $courseId, int $userId): bool;

    // İstatistikler
    public function getCourseStatistics(int $courseId): array;
public function getPopularCourses(int $limit = 5): array;
    public function countAllCourses(): int;

    public function countInstructorCourses(int $instructorId): int;
    public function countInstructorStudents(int $instructorId): int;
    public function getInstructorAverageRating(int $instructorId): float;
    public function calculateInstructorEarnings(int $instructorId): float;

    // Arama ve Filtreleme
    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator;

    // Yardımcı Metodlar
    public function isCourseOwner(int $courseId, int $userId): bool;
}
