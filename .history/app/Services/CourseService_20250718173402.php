<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseService
{
    protected $repository;

    public function __construct(CourseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

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
        'category' => $filters['category'] ?? null,
        'difficulty' => $filters['level'] ?? null, // level parametresini difficulty olarak geçiriyoruz
        'status' => 'published',
        'search' => $filters['search'] ?? null,
        'per_page' => $filters['per_page'] ?? 9
    ]);
}
    public function getCourseById(int $courseId)
    {
        return Course::with([
                'instructor',
                'lessons',
                'reviews.user',
                'students'
            ])
            ->withCount(['students', 'lessons'])
            ->findOrFail($courseId);
    }

// app/Services/CourseService.php

    public function createCourse(array $data, $thumbnailFile = null)
    {
        // Thumbnail yükleme
        if ($thumbnailFile) {
            $data['thumbnail'] = $this->uploadThumbnail($thumbnailFile);
        }

        return $this->repository->createCourse($data);
    }
  protected function uploadThumbnail($file): string
    {
        return $file->store('public/course-thumbnails');
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
    $user = $userId = Auth::user();

    // Eğitmen ve admin kontrolü
    $isOwner = $course->user_id === $user->id;
    $isAdmin = $user->role === 'admin';

    // Öğrenci kontrolü
    $isEnrolled = $this->repository->checkEnrollment($courseId, $user->id);

    if (!$isOwner && !$isAdmin && !$isEnrolled) {
        throw new \Exception('Bu istatistikleri görüntüleme yetkiniz yok');
    }

    $stats = $this->repository->getCourseStatistics($courseId);

    // Öğrenciler için kısıtlı veri
    if ($isEnrolled && !$isOwner && !$isAdmin) {
        $stats = [
            'average_rating' => $stats['average_rating'],
            'total_students' => $stats['total_students'],
            // Hassas verileri filtrele
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




    ///******* */
    public function getEnrolledCoursesCount(int $userId)
{
    return $this->repository->getEnrolledCourses($userId)->count();
}

public function getPendingAssignmentsCount(int $userId)
{
    // Assignment modelinize göre bu metodu implement edin
    return 3; // Örnek değer
}

public function getUpcomingQuizzesCount(int $userId)
{
    // Quiz modelinize göre bu metodu implement edin
    return 2; // Örnek değer
}

public function getUpcomingDeadlines(int $userId)
{
    return [
        [
            'id' => 1,
            'type' => 'assignment',
            'title' => 'Final Proje Teslimi',
            'course' => 'Laravel İleri Seviye',
            'dueDate' => now()->addDays(2)->toISOString()
        ],
        [
            'id' => 2,
            'type' => 'quiz',
            'title' => 'Bölüm 3 Quiz',
            'course' => 'Vue.js Temelleri',
            'dueDate' => now()->addDays(5)->toISOString()
        ]
    ];
}

public function getRecentActivities(int $userId)
{
    return [
        [
            'id' => 1,
            'type' => 'course',
            'message' => 'Laravel İleri Seviye kursuna kaydoldunuz',
            'date' => now()->subHours(2)->toISOString()
        ],
        [
            'id' => 2,
            'type' => 'assignment',
            'message' => 'Ödev 1 tamamlandı',
            'date' => now()->subDays(1)->toISOString()
        ]
    ];
}

public function getStudentCoursesWithProgress(int $userId)
{
    $courses = $this->repository->getEnrolledCourses($userId);

        return collect($courses->items())->map(function($course) {
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
            'progress' => $progress
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
        return Course::where('id', $courseId)
            ->whereHas('students', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->exists();
    }
}
