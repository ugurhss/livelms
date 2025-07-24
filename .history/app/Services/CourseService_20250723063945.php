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
    private CourseRepositoryInterface $repository;

    public function __construct(CourseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function checkEnrollment(int $courseId, int $userId): bool
{
    return $this->repository->checkEnrollment($courseId, $userId);
}
    public function getAllCourses(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->getAllCourses($filters);
    }

    public function getPopularCourses(int $limit = 5): array
    {
        return $this->repository->getPopularCourses($limit);
    }

    public function getPublishedCourses(array $filters = []): LengthAwarePaginator
    {
        return $this->repository->getPublishedCourses([
            'category' => $filters['category'] ?? null,
            'difficulty' => $filters['level'] ?? null,
            'status' => 'published',
            'search' => $filters['search'] ?? null,
            'per_page' => $filters['per_page'] ?? 9
        ]);
    }

    public function getCourseById(int $courseId): Course
    {
        return $this->repository->getCourseById($courseId);
    }

    public function createCourse(array $data): Course
    {
        try {
            $data['user_id'] = Auth::id();
            $data['slug'] = $this->generateUniqueSlug($data['title']);
            $data['outcomes'] = $data['outcomes'] ?? [];
            $data['prerequisites'] = $data['prerequisites'] ?? [];

            $lessons = $data['lessons'] ?? [];
            unset($data['lessons']);

            $course = $this->repository->createCourse($data);
            $this->createLessons($course, $lessons);

            return $course;
        } catch (\Exception $e) {
            Log::error('Course creation failed: '.$e->getMessage(), ['data' => $data]);
            throw $e;
        }
    }

  public function updateCourse(int $courseId, array $data): Course
{
    $course = $this->repository->updateCourse($courseId, $data);

    // Dersleri güncelle
    if (isset($data['lessons'])) {
        $course->lessons()->delete();
        foreach ($data['lessons'] as $lessonData) {
            $course->lessons()->create($lessonData);
        }
    }

    return $course;
}

    public function deleteCourse(int $courseId): Course
    {
        $this->validateCourseOwnership($courseId);
        return $this->repository->deleteCourse($courseId);
    }

   public function enrollUser(int $courseId, int $userId): void
{
    $course = $this->repository->getCourseById($courseId);

    if ($course->status !== 'published') {
        throw new \RuntimeException('Bu kurs şu anda yayında değil');
    }

    if ($this->repository->checkEnrollment($courseId, $userId)) {
        throw new \RuntimeException('Bu kursa zaten kayıtlısınız');
    }

    $this->repository->enrollUser($courseId, $userId);
}

    public function unenrollUser(int $courseId, int $userId): void
    {
        if (!$this->repository->checkEnrollment($courseId, $userId)) {
            throw new NotEnrolledException();
        }

        $this->repository->unenrollUser($courseId, $userId);
    }

    public function getInstructorCourses(int $instructorId, array $filters = []): LengthAwarePaginator
    {
        if ($instructorId !== Auth::id()) {
            throw new UnauthorizedAccessException();
        }

        return $this->repository->getCoursesByInstructor($instructorId, $filters);
    }

    public function getEnrolledCourses(int $userId): LengthAwarePaginator
    {
        if ($userId !== Auth::id()) {
            throw new UnauthorizedAccessException();
        }

        return $this->repository->getEnrolledCourses($userId);
    }

    public function getCourseStatistics(int $courseId): array
    {
        $course = $this->repository->getCourseById($courseId);
        $user = Auth::user();

        $isOwner = $course->user_id === $user->id;
        $isAdmin = $user->role === 'admin';
        $isEnrolled = $this->repository->checkEnrollment($courseId, $user->id);

        if (!$isOwner && !$isAdmin && !$isEnrolled) {
            throw new UnauthorizedAccessException();
        }

        $stats = $this->repository->getCourseStatistics($courseId);

        if ($isEnrolled && !$isOwner && !$isAdmin) {
            return [
                'average_rating' => $stats['average_rating'],
                'total_students' => $stats['total_students'],
                'completion_rate' => $stats['completion_rate']
            ];
        }

        return $stats;
    }

    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->searchCourses($query, $filters);
    }

    public function getEnrolledCoursesCount(int $userId): int
    {
        return $this->repository->getEnrolledCourses($userId)->total();
    }

    public function getStudentCoursesWithProgress(int $userId): Collection
    {
        $courses = $this->repository->getEnrolledCourses($userId);

        return $courses->map(function($course) use ($userId) {
            $totalLessons = $course->lessons()->count();
            $completedLessons = $course->lessons()
                ->whereHas('completions', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->count();

            $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'image' => $course->image,
                'progress' => $progress
            ];
        });
    }

    public function getTotalStudentsForInstructor(int $instructorId): int
    {
        return $this->repository->countInstructorStudents($instructorId);
    }

    public function getAverageRatingForInstructor(int $instructorId): float
    {
        return $this->repository->getInstructorAverageRating($instructorId);
    }

    public function getCompletionRateForInstructor(int $instructorId): float
    {
        $courses = $this->repository->getCoursesByInstructor($instructorId);

        $totalLessons = $courses->sum(function($course) {
            return $course->lessons_count;
        });

        $completedLessons = $courses->sum(function($course) {
            return $course->lessonCompletions()->count();
        });

        return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
    }

    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $i = 1;

        $query = Course::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug.'-'.$i++;
            $query = Course::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    private function createLessons(Course $course, array $lessonsData): void
    {
        foreach ($lessonsData as $lessonData) {
            $lessonData['slug'] = $this->generateUniqueLessonSlug($lessonData['title']);
            $course->lessons()->create($lessonData);
        }
    }

    private function generateUniqueLessonSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $i = 1;

        while (Lesson::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$i++;
        }

        return $slug;
    }

    protected function validateCourseOwnership(int $courseId): void
    {
        if (!$this->isCourseOwner($courseId)) {
            throw new UnauthorizedAccessException();
        }
    }

    protected function isCourseOwner(int $courseId): bool
    {
        return $this->repository->isCourseOwner($courseId, Auth::id());
    }
}
