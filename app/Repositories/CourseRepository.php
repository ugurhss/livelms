<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Review;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function getAllCourses(array $filters = []): LengthAwarePaginator
    {
        return $this->buildCourseQuery($filters)->paginate($filters['per_page'] ?? 15);
    }

    public function getPublishedCourses(array $filters = []): LengthAwarePaginator
{
    return Course::query()
        ->with(['category',  'instructor'])
        ->when($filters['category'] ?? false, function ($query, $category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        })
        ->when($filters['level'] ?? false, function ($query, $level) {
            $query->whereHas('difficulty', function($q) use ($level) {
                $q->where('slug', $level);
            });
        })
        ->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        }, function ($query) {
            $query->where('status', 'published');
        })
        ->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        })
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->withCount('enrollments as students_count')
        ->orderBy('created_at', 'desc')
        ->paginate($filters['per_page'] ?? 9);
}

    public function getCourseById(int $courseId): Course
    {
        return Course::with([
                'instructor',
                'category',
                'lessons',
                'reviews.user',
                'students'
            ])
            ->withCount(['students', 'lessons'])
            ->findOrFail($courseId);
    }

    public function createCourse(array $data): Course
    {
        return DB::transaction(function () use ($data) {
            return Course::create($data);
        });
    }

public function updateCourse(int $courseId, array $courseDetails): Course
{
    $course = Course::findOrFail($courseId);

    if (isset($courseDetails['image'])) {
        // Eski resmi sil
        Storage::delete($course->image);
    }

    $course->update($courseDetails);

    return $course;
}

    public function deleteCourse(int $courseId): Course
    {
        return DB::transaction(function () use ($courseId) {
            $course = Course::findOrFail($courseId);
            $course->delete();
            return $course;
        });
    }

    public function getCoursesByInstructor(int $instructorId, array $filters = []): LengthAwarePaginator
    {
        $query = Course::where('user_id', $instructorId)
            ->with(['category', 'students'])
            ->withCount(['students', 'lessons']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getEnrolledCourses(int $userId): LengthAwarePaginator
    {
        return Course::whereHas('students', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['instructor', 'category'])
            ->withCount(['lessons'])
            ->paginate();
    }

    public function enrollUser(int $courseId, int $userId): void
    {
        DB::transaction(function () use ($courseId, $userId) {
            $course = Course::findOrFail($courseId);
            $course->students()->syncWithoutDetaching([$userId]);
        });
    }

    public function unenrollUser(int $courseId, int $userId): void
    {
        DB::transaction(function () use ($courseId, $userId) {
            $course = Course::findOrFail($courseId);
            $course->students()->detach($userId);
        });
    }

   public function checkEnrollment(int $courseId, int $userId): bool
{
    return Enrollment::where('course_id', $courseId)
        ->where('user_id', $userId)
        ->exists();
}

    public function getCourseStatistics(int $courseId): array
    {
        $course = Course::withCount(['students', 'lessons', 'reviews'])
            ->findOrFail($courseId);

        return [
            'total_students' => $course->students_count,
            'total_lessons' => $course->lessons_count,
            'average_rating' => $course->reviews()->avg('rating') ?? 0,
            'completion_rate' => $this->calculateCompletionRate($courseId),
            'total_reviews' => $course->reviews_count
        ];
    }

    public function countAllCourses(): int
    {
        return Course::count();
    }

    public function getPopularCourses(int $limit = 5): array
    {
        return Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator
    {
        return $this->buildCourseQuery($filters)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate($filters['per_page'] ?? 15);
    }

    public function countInstructorCourses(int $instructorId): int
    {
        return Course::where('user_id', $instructorId)->count();
    }

    public function countInstructorStudents(int $instructorId): int
    {
        return Course::where('user_id', $instructorId)
            ->withCount('students')
            ->get()
            ->sum('students_count');
    }

    public function getInstructorAverageRating(int $instructorId): float
    {
        return Course::where('user_id', $instructorId)
            ->withAvg('reviews', 'rating')
            ->get()
            ->avg('reviews_avg_rating') ?? 0;
    }

    public function calculateInstructorEarnings(int $instructorId): float
    {
        return Course::where('user_id', $instructorId)
            ->withCount('students')
            ->get()
            ->sum(function($course) {
                return $course->students_count * $course->price * 0.7; // %30 platform payı
            });
    }

    public function isCourseOwner(int $courseId, int $userId): bool
    {
        return Course::where('id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }

    private function buildCourseQuery(array $filters): Builder
    {
        $query = Course::with(['instructor', 'category', 'students'])
            ->withCount(['students', 'lessons']);

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['difficulty'])) {
            $query->where('level', $filters['difficulty']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['instructor'])) {
            $query->where('user_id', $filters['instructor']);
        }

        return $query;
    }

    private function calculateCompletionRate(int $courseId): float
    {
        $totalLessons = Course::findOrFail($courseId)->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = Course::findOrFail($courseId)
            ->lessons()
            ->whereHas('completions')
            ->count();

        return ($completedLessons / $totalLessons) * 100;
    }
}
