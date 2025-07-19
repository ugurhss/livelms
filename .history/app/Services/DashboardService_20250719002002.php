<?php

namespace App\Services;

use App\Models\User;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Validation\ValidationException;
use App\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Support\Collection;

class DashboardService
{
    protected $userRepository;
    protected $courseRepository;
    protected $enrollmentRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        CourseRepositoryInterface $courseRepository,
        EnrollmentRepositoryInterface $enrollmentRepository
    ) {
        $this->userRepository = $userRepository;
        $this->courseRepository = $courseRepository;
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function getUserStats(int $userId): array
    {
        $user = $this->userRepository->getUserById($userId);

        switch ($user->role) {
            case 'student':
                return $this->getStudentStats($userId);
            case 'instructor':
                return $this->getInstructorStats($userId);
            case 'admin':
                return $this->getAdminStats();
            default:
                return [];
        }
    }

    protected function getStudentStats(int $userId): array
    {
        return [
            'enrolled_courses' => $this->enrollmentRepository->countUserEnrollments($userId),
            'completed_courses' => $this->enrollmentRepository->countCompletedCourses($userId),
            'in_progress_courses' => $this->enrollmentRepository->countInProgressCourses($userId),
            'completion_rate' => $this->enrollmentRepository->calculateCompletionRate($userId)
        ];
    }

    protected function getInstructorStats(int $userId): array
    {
        return [
            'total_courses' => $this->courseRepository->countInstructorCourses($userId),
            'total_students' => $this->courseRepository->countInstructorStudents($userId),
            'average_rating' => $this->courseRepository->getInstructorAverageRating($userId),
            'total_earnings' => $this->courseRepository->calculateInstructorEarnings($userId)
        ];
    }

    protected function getAdminStats(): array
    {
        return [
            'total_users' => $this->userRepository->countUsers() ?? 0,
            'total_courses' => $this->courseRepository->countAllCourses() ?? 0,
            'active_enrollments' => $this->enrollmentRepository->countActiveEnrollments() ?? 0,
            'monthly_revenue' => $this->enrollmentRepository->calculateMonthlyRevenue() ?? 0,
            'enrollment_trends' => $this->enrollmentRepository->getEnrollmentTrends() ?? []
        ];
    }

    public function getRecentActivities(int $userId): array
    {
        $activities = [];

        // Repository'de getRecentEnrollments yoksa doğrudan modeli kullan
        $enrollments = Enrollment::with('course')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($enrollments as $enrollment) {
            $activities[] = [
                'type' => 'enrollment',
                'message' => $enrollment->course->title.' kursuna kaydoldunuz',
                'date' => $enrollment->created_at,
                'icon' => 'bookmark'
            ];
        }

        usort($activities, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, 5);
    }

    public function getSystemStats(): array
    {
        return [
            'toplam_kullanici' => $this->userRepository->countUsers(),
            'toplam_ogrenci' => $this->userRepository->countUsersByRole('student'),
            'toplam_egitmen' => $this->userRepository->countUsersByRole('instructor'),
            'yeni_kullanicilar' => $this->userRepository->getRecentUsers()
        ];
    }

    public function getUpcomingDeadlines(int $userId): array
    {
        $assignments = Assignment::whereHas('course.enrollments', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('due_date', '>', now())
        ->orderBy('due_date')
        ->with('course')
        ->limit(5)
        ->get();

        return $assignments->map(function($assignment) {
            return [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'course' => $assignment->course->title,
                'due_date' => $assignment->due_date,
                'type' => 'assignment'
            ];
        })->toArray();
    }

    public function getStudentCoursesWithProgress(int $userId): array
    {
        $enrollments = $this->enrollmentRepository->getEnrollmentsByUser($userId);

        // Paginator'dan collection alıp map uygula
        $collection = $enrollments instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $enrollments->getCollection()
            : collect($enrollments);

        return $collection->map(function($enrollment) {
            return [
                'id' => $enrollment->course_id,
                'title' => $enrollment->course->title,
                'thumbnail' => $enrollment->course->thumbnail,
                'instructor' => $enrollment->course->instructor->name,
                'progress' => $enrollment->progress,
                'completed' => $enrollment->completed_at !== null
            ];
        })->toArray();
    }

    public function getInstructorCourses(int $instructorId): array
    {
        $courses = $this->courseRepository->getCoursesByInstructor($instructorId);

        // Paginator'dan collection alıp map uygula
        $collection = $courses instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $courses->getCollection()
            : collect($courses);

        return $collection->map(function($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'status' => $course->status,
                'students_count' => $course->students_count,
                'rating' => $course->reviews_avg_rating ?? 0
            ];
        })->toArray();
    }

    public function countUsers(): int
    {
        return User::count();
    }
}
