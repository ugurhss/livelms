<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Exceptions\CourseNotPublishedException;
use App\Exceptions\UnauthorizedAccessException;
use App\Exceptions\AlreadyEnrolledException;
use App\Exceptions\NotEnrolledException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseService
{
 protected CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAllCourses(array $filters = []): LengthAwarePaginator
    {
        return $this->courseRepository->getAllCourses($filters);
    }

    public function getPublishedCourses(array $filters = []): LengthAwarePaginator
    {
        return $this->courseRepository->getPublishedCourses($filters);
    }

    public function getCourseById(int $courseId)
    {
        try {
            return $this->courseRepository->getCourseById($courseId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Kurs bulunamadı');
        }
    }

    public function createCourse(array $data)
    {
        try {
            return $this->courseRepository->createCourse($data);
        } catch (RuntimeException $e) {
            throw new RuntimeException('Kurs oluşturulamadı: ' . $e->getMessage());
        }
    }

    public function updateCourse(int $courseId, array $courseDetails)
    {
        try {
            return $this->courseRepository->updateCourse($courseId, $courseDetails);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Kurs bulunamadı');
        } catch (RuntimeException $e) {
            throw new RuntimeException('Kurs güncellenemedi: ' . $e->getMessage());
        }
    }

    public function deleteCourse(int $courseId)
    {
        try {
            return $this->courseRepository->deleteCourse($courseId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Kurs bulunamadı');
        } catch (RuntimeException $e) {
            throw new RuntimeException('Kurs silinemedi: ' . $e->getMessage());
        }
    }

    public function getCoursesByInstructor(int $instructorId, array $filters = []): LengthAwarePaginator
    {
        return $this->courseRepository->getCoursesByInstructor($instructorId, $filters);
    }

    public function getEnrolledCourses(int $userId): LengthAwarePaginator
    {
        return $this->courseRepository->getEnrolledCourses($userId);
    }

    public function enrollUser(int $courseId, int $userId): void
    {
        try {
            $this->courseRepository->enrollUser($courseId, $userId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Kurs bulunamadı');
        } catch (RuntimeException $e) {
            throw new RuntimeException('Kursa kayıt olunamadı: ' . $e->getMessage());
        }
    }

    public function unenrollUser(int $courseId, int $userId): void
    {
        try {
            $this->courseRepository->unenrollUser($courseId, $userId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Kurs bulunamadı');
        } catch (RuntimeException $e) {
            throw new RuntimeException('Kurstan çıkılamadı: ' . $e->getMessage());
        }
    }

    public function checkEnrollment(int $courseId, int $userId): bool
    {
        return $this->courseRepository->checkEnrollment($courseId, $userId);
    }

    public function getCourseStatistics(int $courseId): array
    {
        try {
            return $this->courseRepository->getCourseStatistics($courseId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Kurs bulunamadı');
        }
    }

    public function getPopularCourses(int $limit = 5): array
    {
        return $this->courseRepository->getPopularCourses($limit);
    }

    public function countAllCourses(): int
    {
        return $this->courseRepository->countAllCourses();
    }

    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator
    {
        return $this->courseRepository->searchCourses($query, $filters);
    }

    public function countInstructorCourses(int $instructorId): int
    {
        return $this->courseRepository->countInstructorCourses($instructorId);
    }

    public function countInstructorStudents(int $instructorId): int
    {
        return $this->courseRepository->countInstructorStudents($instructorId);
    }

    public function getInstructorAverageRating(int $instructorId): float
    {
        return $this->courseRepository->getInstructorAverageRating($instructorId);
    }

    public function calculateInstructorEarnings(int $instructorId): float
    {
        return $this->courseRepository->calculateInstructorEarnings($instructorId);
    }

    public function isCourseOwner(int $courseId, int $userId): bool
    {
        return $this->courseRepository->isCourseOwner($courseId, $userId);
    }
}
