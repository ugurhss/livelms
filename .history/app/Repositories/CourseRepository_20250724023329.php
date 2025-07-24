<?php

namespace App\Repositories;

use RuntimeException;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Review;
use App\Models\Enrollment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
   public function getAllCourses(array $filters = []): LengthAwarePaginator
    {
        $query = Course::query();

        if (isset($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (isset($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        return $query->paginate(10);
    }

    public function getPublishedCourses(array $filters = []): LengthAwarePaginator
    {
        $query = Course::published();

        if (isset($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (isset($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        return $query->paginate(10);
    }

    public function getCourseById(int $courseId): Course
    {
        return Course::with(['lessons', 'instructor', 'reviews', 'category'])->findOrFail($courseId);
    }

   public function createCourse(array $data): Course
{
    DB::beginTransaction();

    try {
        // Thumbnail işleme
        $thumbnailPath = null;
        if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
            $thumbnailPath = $data['thumbnail']->store('course-thumbnails', 'public');
        }

        // Kurs oluştur
        $course = Course::create([
            'title' => $data['title'],
            'slug' => $this->generateUniqueSlug($data['title']),
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
            'duration' => 0, // Başlangıçta 0, aşağıda hesaplanacak
        ]);

        // Dersleri ekle ve süreleri topla
        $totalDuration = 0;
        if (isset($data['lessons']) && is_array($data['lessons'])) {
            foreach ($data['lessons'] as $index => $lessonData) {
                $lessonDuration = $lessonData['duration_minutes'] ?? 0;
                $totalDuration += $lessonDuration;

                $lesson = new Lesson([
                    'title' => $lessonData['title'],
                    'slug' => $this->generateUniqueSlug($lessonData['title'], Lesson::class),
                    'description' => $lessonData['description'] ?? null,
                    'duration_minutes' => $lessonDuration,
                    'video_url' => $lessonData['video_url'] ?? null,
                    'order' => $lessonData['order'] ?? ($index + 1),
                    'is_free' => $lessonData['is_free'] ?? false,
                ]);

                $course->lessons()->save($lesson);
            }
        }

        // Toplam süreyi güncelle
        $course->update(['duration' => $totalDuration]);

        DB::commit();
        return $course;
    } catch (\Exception $e) {
        DB::rollBack();
        throw new \RuntimeException("Kurs oluşturulurken hata oluştu: " . $e->getMessage());
    }
}
private function generateUniqueSlug(string $title, string $model = Course::class): string
{
    $slug = Str::slug($title);
    $count = $model::where('slug', $slug)->count();

    return $count ? "{$slug}-{$count}" : $slug;
}

    public function updateCourse(int $courseId, array $courseDetails): Course
    {
        $course = $this->getCourseById($courseId);

        DB::beginTransaction();

        try {
            $course->update($courseDetails);

            // Dersleri güncelle
            if (isset($courseDetails['lessons'])) {
                $existingLessonIds = $course->lessons->pluck('id')->toArray();
                $updatedLessonIds = [];

                foreach ($courseDetails['lessons'] as $lessonData) {
                    if (isset($lessonData['id'])) {
                        // Var olan dersi güncelle
                        $lesson = Lesson::find($lessonData['id']);
                        if ($lesson) {
                            $lesson->update($lessonData);
                            $updatedLessonIds[] = $lesson->id;
                        }
                    } else {
                        // Yeni ders ekle
                        $lesson = new Lesson($lessonData);
                        $course->lessons()->save($lesson);
                        $updatedLessonIds[] = $lesson->id;
                    }
                }

                // Silinen dersleri kaldır
                $lessonsToDelete = array_diff($existingLessonIds, $updatedLessonIds);
                if (!empty($lessonsToDelete)) {
                    Lesson::whereIn('id', $lessonsToDelete)->delete();
                }
            }

            DB::commit();
            return $course->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new RuntimeException("Kurs güncellenirken hata oluştu: " . $e->getMessage());
        }
    }

    public function deleteCourse(int $courseId): Course
    {
        $course = $this->getCourseById($courseId);

        DB::beginTransaction();

        try {
            // İlişkili dersleri sil
            $course->lessons()->delete();

            // Kursu sil
            $course->delete();

            DB::commit();
            return $course;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new RuntimeException("Kurs silinirken hata oluştu: " . $e->getMessage());
        }
    }

  public function getCoursesByInstructor(int $instructorId, array $filters = []): LengthAwarePaginator
{
    $query = Course::withCount(['students', 'lessons'])
        ->withAvg('reviews', 'rating')
        ->withSum('enrollments', 'progress')
        ->where('user_id', $instructorId);

    if (isset($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    return $query->paginate(10);
}

public function getEnrolledCourses(int $userId): LengthAwarePaginator
{
    return Course::withCount('lessons')
        ->whereHas('students', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['enrollments' => function($query) use ($userId) {
            $query->where('user_id', $userId);
        }])
        ->paginate(12);
}

    public function enrollUser(int $courseId, int $userId): void
    {
        $course = $this->getCourseById($courseId);

        if ($this->checkEnrollment($courseId, $userId)) {
            throw new RuntimeException('Kullanıcı zaten bu kursa kayıtlı');
        }

        $course->students()->attach($userId, [
            'enrolled_at' => now(),
            'progress' => 0
        ]);
    }

    public function unenrollUser(int $courseId, int $userId): void
    {
        $course = $this->getCourseById($courseId);

        if (!$this->checkEnrollment($courseId, $userId)) {
            throw new RuntimeException('Kullanıcı bu kursa kayıtlı değil');
        }

        $course->students()->detach($userId);
    }

    public function checkEnrollment(int $courseId, int $userId): bool
    {
        return Enrollment::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function getCourseStatistics(int $courseId): array
    {
        $course = $this->getCourseById($courseId);

        return [
            'total_students' => $course->students()->count(),
            'average_rating' => $course->reviews()->avg('rating') ?? 0,
            'total_lessons' => $course->lessons()->count(),
            'completion_rate' => $course->enrollments()->avg('progress') ?? 0,
        ];
    }

    public function getPopularCourses(int $limit = 5): array
    {
        return Course::published()
            ->withCount('students')
            ->orderBy('students_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function countAllCourses(): int
    {
        return Course::count();
    }

    public function searchCourses(string $query, array $filters = []): LengthAwarePaginator
    {
        $searchQuery = Course::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%'.$query.'%')
                  ->orWhere('description', 'like', '%'.$query.'%');
            });

        if (isset($filters['category'])) {
            $searchQuery->where('category_id', $filters['category']);
        }

        if (isset($filters['level'])) {
            $searchQuery->where('level', $filters['level']);
        }

        return $searchQuery->paginate(10);
    }

    public function countInstructorCourses(int $instructorId): int
    {
        return Course::where('user_id', $instructorId)->count();
    }

    public function countInstructorStudents(int $instructorId): int
    {
        return Enrollment::whereHas('course', function($query) use ($instructorId) {
            $query->where('user_id', $instructorId);
        })->count();
    }

    public function getInstructorAverageRating(int $instructorId): float
    {
        return Review::whereHas('course', function($query) use ($instructorId) {
            $query->where('user_id', $instructorId);
        })->avg('rating') ?? 0;
    }

    public function calculateInstructorEarnings(int $instructorId): float
    {
        return Enrollment::whereHas('course', function($query) use ($instructorId) {
            $query->where('user_id', $instructorId);
        })->sum('progress');
    }

    public function isCourseOwner(int $courseId, int $userId): bool
    {
        return Course::where('id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }
}
