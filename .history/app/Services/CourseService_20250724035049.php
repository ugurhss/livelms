<?php

namespace App\Services;

use RuntimeException;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\NotEnrolledException;
use App\Exceptions\AlreadyEnrolledException;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\CourseRepositoryInterface;
use App\Exceptions\CourseNotPublishedException;
use App\Exceptions\UnauthorizedAccessException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    //creat

public function createCourse(array $data): Course
{
    try {
        // Thumbnail işleme
        $thumbnailPath = $this->handleThumbnail($data['thumbnail'] ?? null);

        // Kurs verilerini hazırla
        $courseData = $this->prepareCourseData($data, $thumbnailPath);

        // Repository üzerinden kurs oluştur
        $course = $this->courseRepository->createCourse($courseData);

        // Dersleri ekle
        if (isset($data['lessons'])) {
            $this->addLessonsToCourse($course, $data['lessons']);
        }

        return $course;
    } catch (\Exception $e) {
        Log::error('Course creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw new \RuntimeException('Kurs oluşturulamadı: ' . $e->getMessage());
    }
}

private function handleThumbnail($thumbnail): ?string
{
    if ($thumbnail instanceof \Illuminate\Http\UploadedFile) {
        return $thumbnail->store('course-thumbnails', 'public');
    }
    return null;
}

private function prepareCourseData(array $data, ?string $thumbnailPath): array
{
    return [
        'title' => $data['title'],
        'slug' => Str::slug($data['title']),
        'description' => $data['description'],
        'thumbnail' => $thumbnailPath,
        'level' => $data['level'],
        'category_id' => $data['category_id'] ?? null,
        'user_id' => Auth::id(),
        'price' => $data['price'],
        'original_price' => $data['original_price'] ?? $data['price'],
        'status' => $data['status'] ?? 'draft',
        'outcomes' => $data['outcomes'] ?? [],
        'prerequisites' => $data['prerequisites'] ?? [],
        'duration' => 0, // Başlangıçta 0, dersler eklendikçe güncellenecek
    ];
}

private function addLessonsToCourse(Course $course, array $lessons): void
{
    $totalDuration = 0;

    foreach ($lessons as $index => $lessonData) {
        $lessonDuration = $lessonData['duration_minutes'] ?? 0;
        $totalDuration += $lessonDuration;

        $lesson = new Lesson([
            'title' => $lessonData['title'],
            'slug' => Str::slug($lessonData['title']),
            'description' => $lessonData['description'] ?? null,
            'duration_minutes' => $lessonDuration,
            'video_url' => $lessonData['video_url'] ?? null,
            'order' => $lessonData['order'] ?? ($index + 1),
            'is_free' => $lessonData['is_free'] ?? false,
        ]);

        $course->lessons()->save($lesson);
    }

    $course->update(['duration' => $totalDuration]);
}

///bşttş











private function prepareUpdateData(array $data, ?string $thumbnailPath): array
{
    $updateData = [
        'title' => $data['title'] ?? null,
        'description' => $data['description'] ?? null,
        'level' => $data['level'] ?? null,
        'category_id' => $data['category_id'] ?? null,
        'price' => $data['price'] ?? null,
        'original_price' => $data['original_price'] ?? null,
        'status' => $data['status'] ?? null,
        'outcomes' => $data['outcomes'] ?? null,
        'prerequisites' => $data['prerequisites'] ?? null,
    ];

    // Only update slug if title changed
    if (isset($data['title'])) {
        $updateData['slug'] = Str::slug($data['title']);
    }

    // Only update thumbnail if a new one was provided
    if ($thumbnailPath) {
        $updateData['thumbnail'] = $thumbnailPath;
    }

    // Remove null values
    return array_filter($updateData, function($value) {
        return $value !== null;
    });
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
