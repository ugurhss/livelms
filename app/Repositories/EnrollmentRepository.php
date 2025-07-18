<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Models\Course;
use App\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function enrollUser(int $courseId, int $userId)
    {
        $enrollment = Enrollment::firstOrCreate([
            'user_id' => $userId,
            'course_id' => $courseId
        ], [
            'progress' => 0,
            'completed_at' => null
        ]);

        return $enrollment;
    }

    public function unenrollUser(int $courseId, int $userId)
    {
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->firstOrFail();

        $enrollment->delete();
        return $enrollment;
    }

    public function getEnrollmentsByUser(int $userId): LengthAwarePaginator
    {
        return Enrollment::where('user_id', $userId)
            ->with(['course.instructor', 'course.category'])
            ->paginate();
    }

    public function getEnrollmentsByCourse(int $courseId): LengthAwarePaginator
    {
        return Enrollment::where('course_id', $courseId)
            ->with(['user'])
            ->paginate();
    }

    public function countUserEnrollments(int $userId): int
    {
        return Enrollment::where('user_id', $userId)->count();
    }

    public function countCompletedCourses(int $userId): int
    {
        return Enrollment::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->count();
    }

    public function countInProgressCourses(int $userId): int
    {
        return Enrollment::where('user_id', $userId)
            ->whereNull('completed_at')
            ->count();
    }


    public function calculateCompletionRate(int $userId): float
{
    $totalEnrollments = $this->countUserEnrollments($userId);
    if ($totalEnrollments === 0) {
        return 0;
    }

    $completedEnrollments = $this->countCompletedCourses($userId);
    return ($completedEnrollments / $totalEnrollments) * 100;
}
 public function calculateMonthlyRevenue(): float
{
    return Enrollment::whereMonth('created_at', now()->month)
        ->with('course') // Kurs ilişkisini yükle
        ->get()
        ->sum(function($enrollment) {
            return $enrollment->course->price ?? 0; // Kurs fiyatını topla
        });
}

    public function countActiveEnrollments(): int
    {
        return Enrollment::whereNull('completed_at')->count();
    }



    public function getEnrollmentTrends(int $months = 6): array
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Enrollment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = [
                'month' => $date->format('M Y'),
                'enrollments' => $count
            ];
        }

        return $data;
    }
}
