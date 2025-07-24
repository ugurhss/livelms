<?php

namespace App\Interfaces;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function getAllCourses(array $filters = []): LengthAwarePaginator;
    public function getPublishedCourses(array $filters = []): LengthAwarePaginator;

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getCourseById(int $courseId): Course;

    /**
     * @throws \Exception
     */
    public function create(array $data);

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $data);

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteCourse(int $courseId): Course;

    public function getCoursesByInstructor(int $instructorId, array $filters = []): LengthAwarePaginator;
    public function getEnrolledCourses(int $userId): LengthAwarePaginator;

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \RuntimeException
     */
    public function enrollUser(int $courseId, int $userId): void;

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \RuntimeException
     */
    public function unenrollUser(int $courseId, int $userId): void;

    public function checkEnrollment(int $courseId, int $userId): bool;
    public function getCourseStatistics(int $courseId): array;
    public function getPopularCourses(int $limit = 5): array;
    public function countAllCourses(): int;
    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator;
    public function countInstructorCourses(int $instructorId): int;
    public function countInstructorStudents(int $instructorId): int;
    public function getInstructorAverageRating(int $instructorId): float;
    public function calculateInstructorEarnings(int $instructorId): float;
    public function isCourseOwner(int $courseId, int $userId): bool;
}
