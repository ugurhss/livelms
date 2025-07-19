<?php

namespace App\Services;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Validation\ValidationException;
use App\Interfaces\EnrollmentRepositoryInterface;

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
        'total_users' => $this->userRepository->countUsers() ?? 0, // Null kontrolü eklendi
        'total_courses' => $this->courseRepository->countAllCourses() ?? 0,
        'active_enrollments' => $this->enrollmentRepository->countActiveEnrollments() ?? 0,
        'monthly_revenue' => $this->enrollmentRepository->calculateMonthlyRevenue() ?? 0,
        'enrollment_trends' => $this->enrollmentRepository->getEnrollmentTrends() ?? []
    ];
}

    public function getRecentActivities(int $userId): array
    {
        $activities = [];

        // Son kayıt olunan kurslar
        $enrollments = $this->enrollmentRepository->getEnrollmentsByUser($userId);
        foreach ($enrollments as $enrollment) {
            $activities[] = [
                'type' => 'enrollment',
                'message' => $enrollment->course->title.' kursuna kaydoldunuz',
                'date' => $enrollment->created_at,
                'icon' => 'bookmark'
            ];
        }

        // Son tamamlanan dersler (LessonCompletion modeliniz varsa)

        usort($activities, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, 5);
    }

    public function getUpcomingDeadlines(int $userId): array
    {
        // Örnek veri - Gerçek uygulamada veritabanından çekilecek
        return [
            [
                'id' => 1,
                'title' => 'Final Proje Teslimi',
                'course' => 'Laravel İleri Seviye',
                'due_date' => now()->addDays(3)->format('Y-m-d'),
                'type' => 'assignment'
            ],
            [
                'id' => 2,
                'title' => 'Bölüm 3 Quiz',
                'course' => 'Vue.js Temelleri',
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'type' => 'quiz'
            ]
        ];
    }

    public function getStudentCoursesWithProgress(int $userId): array
    {
        $enrollments = $this->enrollmentRepository->getEnrollmentsByUser($userId);

        return $enrollments->map(function($enrollment) {
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

        return $courses->map(function($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'status' => $course->status,
                'students_count' => $course->students_count,
                'rating' => $course->reviews_avg_rating ?? 0
            ];
        })->toArray();
    }

public function getSystemStats(): array
{
    return [
        'total_users' => $this->userRepository->countUsers(),
        'total_courses' => $this->courseRepository->countAllCourses(),
        'active_enrollments' => $this->enrollmentRepository->countActiveEnrollments(),
        'monthly_revenue' => $this->enrollmentRepository->calculateMonthlyRevenue(),
        'enrollment_trends' => $this->enrollmentRepository->getEnrollmentTrends(),
    ];
    //  dd($a); // → Ekranda değerleri görmek için

    // return $a;
}

    public function countUsers(): int
{
    return User::count(); // Emin olun ki User modeli doğru import edilmiş
}
}
