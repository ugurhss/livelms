<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(
        protected CourseRepositoryInterface $repository
    ) {}

    public function getAllCourses(array $filters = [])
    {
        return $this->repository->getAllCourses($filters);
    }

    public function getPopularCourses(int $limit = 5): array
    {
        return $this->repository->getPopularCourses($limit);
    }

    public function getPublishedCourses(array $filters = [])
    {
        return $this->repository->getPublishedCourses([
            'category'   => $filters['category'] ?? null,
            'difficulty' => $filters['level'] ?? null,
            'status'     => 'published',
            'search'     => $filters['search'] ?? null,
            'per_page'   => $filters['per_page'] ?? 9,
        ]);
    }

    public function getCourseById(int $courseId)
    {
        return $this->repository->getCourseById($courseId);
    }

    public function createCourse(array $data): Course
    {
        $data['outcomes']      = $this->prepareArrayField($data['outcomes'] ?? []);
        $data['prerequisites'] = $this->prepareArrayField($data['prerequisites'] ?? []);

        return $this->repository->createCourse($data);
    }

    public function updateCourse(int $courseId, array $data)
    {
        $this->validateCourseOwnership($courseId);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $this->repository->updateCourse($courseId, $data);
    }

    public function deleteCourse(int $courseId)
    {
        $this->validateCourseOwnership($courseId);
        return $this->repository->deleteCourse($courseId);
    }

    public function enrollUser(int $courseId, int $userId)
    {
        $course = $this->repository->getCourseById($courseId);

        if ($course->status !== 'published') {
            throw new \Exception('Bu kursa şu anda kayıt olunamaz');
        }

        if ($this->repository->checkEnrollment($courseId, $userId)) {
            throw new \Exception('Bu kursa zaten kayıtlısınız');
        }

        return $this->repository->enrollUser($courseId, $userId);
    }

    public function unenrollUser(int $courseId, int $userId)
    {
        if (!$this->repository->checkEnrollment($courseId, $userId)) {
            throw new \Exception('Bu kursa kayıtlı değilsiniz');
        }

        return $this->repository->unenrollUser($courseId, $userId);
    }

    public function getInstructorCourses(int $instructorId, array $filters = [])
    {
        if ($instructorId !== Auth::id()) {
            throw new \Exception('Sadece kendi kurslarınızı görüntüleyebilirsiniz');
        }

        return $this->repository->getCoursesByInstructor($instructorId, $filters);
    }

    public function getEnrolledCourses(int $userId)
    {
        if ($userId !== Auth::id()) {
            throw new \Exception('Sadece kendi kurslarınızı görüntüleyebilirsiniz');
        }

        return $this->repository->getEnrolledCourses($userId);
    }

    public function getCourseStatistics(int $courseId)
    {
        $course = $this->repository->getCourseById($courseId);
        $user   = Auth::user();

        $isOwner    = $course->user_id === $user->id;
        $isAdmin    = $user->role === 'admin';
        $isEnrolled = $this->repository->checkEnrollment($courseId, $user->id);

        if (!$isOwner && !$isAdmin && !$isEnrolled) {
            throw new \Exception('Bu istatistikleri görüntüleme yetkiniz yok');
        }

        $stats = $this->repository->getCourseStatistics($courseId);

        if ($isEnrolled && !$isOwner && !$isAdmin) {
            return [
                'average_rating' => $stats['average_rating'],
                'total_students' => $stats['total_students'],
            ];
        }

        return $stats;
    }

    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->searchCourses($query, $filters);
    }

    protected function validateCourseOwnership(int $courseId)
    {
        if (!$this->isCourseOwner($courseId)) {
            throw new \Exception('Bu işlem için yetkiniz yok');
        }
    }

    protected function isCourseOwner(int $courseId): bool
    {
        $course = $this->repository->getCourseById($courseId);
        return $course->user_id === Auth::id();
    }

    private function prepareArrayField($value): array
    {
        if (is_string($value)) {
            return array_filter(array_map('trim', explode(',', $value)));
        }

        return is_array($value) ? $value : [];
    }

    // Öğrenci dashboard istatistik metodları

    public function getEnrolledCoursesCount(int $userId)
    {
        return $this->repository->getEnrolledCourses($userId)->count();
    }

    public function getPendingAssignmentsCount(int $userId)
    {
        return 3; // Gelecekte Assignment modeliyle değiştirilmelidir.
    }

    public function getUpcomingQuizzesCount(int $userId)
    {
        return 2; // Gelecekte Quiz modeliyle değiştirilmelidir.
    }

    public function getUpcomingDeadlines(int $userId)
    {
        return [
            [
                'id' => 1,
                'type' => 'assignment',
                'title' => 'Final Proje Teslimi',
                'course' => 'Laravel İleri Seviye',
                'dueDate' => now()->addDays(2)->toISOString(),
            ],
            [
                'id' => 2,
                'type' => 'quiz',
                'title' => 'Bölüm 3 Quiz',
                'course' => 'Vue.js Temelleri',
                'dueDate' => now()->addDays(5)->toISOString(),
            ],
        ];
    }

    public function getRecentActivities(int $userId)
    {
        return [
            [
                'id' => 1,
                'type' => 'course',
                'message' => 'Laravel İleri Seviye kursuna kaydoldunuz',
                'date' => now()->subHours(2)->toISOString(),
            ],
            [
                'id' => 2,
                'type' => 'assignment',
                'message' => 'Ödev 1 tamamlandı',
                'date' => now()->subDays(1)->toISOString(),
            ],
        ];
    }

    public function getStudentCoursesWithProgress(int $userId)
    {
        $courses = $this->repository->getEnrolledCourses($userId);

        return collect($courses->items())->map(function ($course) use ($userId) {
            $totalLessons = $course->lessons()->count();
            $completedLessons = $course->lessons()
                ->whereHas('completions', fn($q) => $q->where('user_id', $userId))
                ->count();

            $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

            return [
                'id'       => $course->id,
                'title'    => $course->title,
                'progress' => $progress,
            ];
        });
    }

    public function getTotalStudentsForInstructor(int $instructorId)
    {
        return Course::where('user_id', $instructorId)
            ->withCount('students')
            ->get()
            ->sum('students_count');
    }

    public function getAverageRatingForInstructor(int $instructorId)
    {
        return Course::where('user_id', $instructorId)
            ->withAvg('reviews', 'rating')
            ->get()
            ->avg('reviews_avg_rating') ?? 0;
    }

    public function getCompletionRateForInstructor(int $instructorId)
    {
        $courses = Course::where('user_id', $instructorId)
            ->withCount(['lessons', 'lessonCompletions'])
            ->get();

        $totalLessons = $courses->sum('lessons_count');
        $completedLessons = $courses->sum('lesson_completions_count');

        return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
    }

    public function checkEnrollment(int $courseId, int $userId): bool
    {
        return $this->repository->checkEnrollment($courseId, $userId);
    }
}
