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

//     public function getAllCourses(array $filters = []): LengthAwarePaginator
//     {
//         return $this->repository->getAllCourses($filters);
//     }

//     public function getPopularCourses(int $limit = 5): array
//     {
//         return $this->repository->getPopularCourses($limit);
//     }

//     public function getPublishedCourses(array $filters = []): LengthAwarePaginator
//     {
//         return $this->repository->getPublishedCourses([
//             'category' => $filters['category'] ?? null,
//             'difficulty' => $filters['level'] ?? null,
//             'status' => 'published',
//             'search' => $filters['search'] ?? null,
//             'per_page' => $filters['per_page'] ?? 9
//         ]);
//     }

//     public function getCourseById(int $courseId): Course
//     {
//         return $this->repository->getCourseById($courseId);
//     }

//     public function createCourse(array $data): Course
//     {
//         try {
//             $data['user_id'] = Auth::id();
//             $data['slug'] = $this->generateUniqueSlug($data['title']);
//             $data['outcomes'] = $data['outcomes'] ?? [];
//             $data['prerequisites'] = $data['prerequisites'] ?? [];

//             $lessons = $data['lessons'] ?? [];
//             unset($data['lessons']);

//             $course = $this->repository->createCourse($data);
//             $this->createLessons($course, $lessons);

//             return $course;
//         } catch (\Exception $e) {
//             Log::error('Course creation failed: '.$e->getMessage(), ['data' => $data]);
//             throw $e;
//         }
//     }

// public function updateCourse(int $courseId, array $data): Course
// {
//     $course = $this->getCourseById($courseId);

//     // Yetki kontrolü
//     if ($course->user_id !== auth()->id()) {
//         throw new UnauthorizedAccessException();
//     }

//     // Slug güncelleme
//     if (isset($data['title']) && $data['title'] !== $course->title) {
//         $data['slug'] = $this->generateUniqueSlug($data['title'], $course->id);
//     }

//     // Dersleri güncelle
//     if (isset($data['lessons'])) {
//         $this->updateLessons($course, $data['lessons']);
//         unset($data['lessons']);
//     }

//     $course->update($data);

//     return $course->fresh();
// }
// protected function updateLessons(Course $course, array $lessonsData): void
// {
//     $existingLessonIds = $course->lessons()->pluck('id')->toArray();
//     $updatedLessonIds = [];

//     foreach ($lessonsData as $lessonData) {
//         // Ensure duration_minutes is set, default to 15 if not
//         $lessonData['duration_minutes'] = $lessonData['duration_minutes'] ?? 15;

//         if (isset($lessonData['id'])) {
//             $lesson = $course->lessons()->findOrFail($lessonData['id']);
//             $lesson->update($lessonData);
//             $updatedLessonIds[] = $lesson->id;
//         } else {
//             $lessonData['slug'] = $this->generateUniqueLessonSlug($lessonData['title']);
//             $newLesson = $course->lessons()->create($lessonData);
//             $updatedLessonIds[] = $newLesson->id;
//         }
//     }

//     // Remove deleted lessons
//     $deletedLessonIds = array_diff($existingLessonIds, $updatedLessonIds);
//     if (!empty($deletedLessonIds)) {
//         $course->lessons()->whereIn('id', $deletedLessonIds)->delete();
//     }
// }


//     public function deleteCourse(int $courseId): Course
//     {
//         $this->validateCourseOwnership($courseId);
//         return $this->repository->deleteCourse($courseId);
//     }

//     public function checkEnrollment(int $courseId, int $userId): bool
// {
//     return $this->repository->checkEnrollment($courseId, $userId);
// }

// public function enrollUser(int $courseId, int $userId): void
// {
//     $course = $this->repository->getCourseById($courseId);

//     // Kullanıcı kontrolü ekliyoruz
//     if ($userId !== Auth::id()) {
//         throw new UnauthorizedAccessException();
//     }

//     if ($course->status !== 'published') {
//         throw new CourseNotPublishedException();
//     }

//     if ($this->repository->checkEnrollment($courseId, $userId)) {
//         throw new AlreadyEnrolledException();
//     }

//     $this->repository->enrollUser($courseId, $userId);
// }


//     public function unenrollUser(int $courseId, int $userId): void
//     {
//         if (!$this->repository->checkEnrollment($courseId, $userId)) {
//             throw new NotEnrolledException();
//         }

//         $this->repository->unenrollUser($courseId, $userId);
//     }

//     public function getInstructorCourses(int $instructorId, array $filters = []): LengthAwarePaginator
//     {
//         if ($instructorId !== Auth::id()) {
//             throw new UnauthorizedAccessException();
//         }

//         return $this->repository->getCoursesByInstructor($instructorId, $filters);
//     }

//     public function getEnrolledCourses(int $userId): LengthAwarePaginator
//     {
//         if ($userId !== Auth::id()) {
//             throw new UnauthorizedAccessException();
//         }

//         return $this->repository->getEnrolledCourses($userId);
//     }

//     public function getCourseStatistics(int $courseId): array
//     {
//         $course = $this->repository->getCourseById($courseId);
//         $user = Auth::user();

//         $isOwner = $course->user_id === $user->id;
//         $isAdmin = $user->role === 'admin';
//         $isEnrolled = $this->repository->checkEnrollment($courseId, $user->id);

//         if (!$isOwner && !$isAdmin && !$isEnrolled) {
//             throw new UnauthorizedAccessException();
//         }

//         $stats = $this->repository->getCourseStatistics($courseId);

//         if ($isEnrolled && !$isOwner && !$isAdmin) {
//             return [
//                 'average_rating' => $stats['average_rating'],
//                 'total_students' => $stats['total_students'],
//                 'completion_rate' => $stats['completion_rate']
//             ];
//         }

//         return $stats;
//     }

//     public function searchCourses(string $query, array $filters = []): LengthAwarePaginator
//     {
//         return $this->repository->searchCourses($query, $filters);
//     }

//     public function getEnrolledCoursesCount(int $userId): int
//     {
//         return $this->repository->getEnrolledCourses($userId)->total();
//     }

//     public function getStudentCoursesWithProgress(int $userId): Collection
//     {
//         $courses = $this->repository->getEnrolledCourses($userId);

//         return $courses->map(function($course) use ($userId) {
//             $totalLessons = $course->lessons()->count();
//             $completedLessons = $course->lessons()
//                 ->whereHas('completions', function($q) use ($userId) {
//                     $q->where('user_id', $userId);
//                 })
//                 ->count();

//             $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

//             return [
//                 'id' => $course->id,
//                 'title' => $course->title,
//                 'slug' => $course->slug,
//                 'image' => $course->image,
//                 'progress' => $progress
//             ];
//         });
//     }

//     public function getTotalStudentsForInstructor(int $instructorId): int
//     {
//         return $this->repository->countInstructorStudents($instructorId);
//     }

//     public function getAverageRatingForInstructor(int $instructorId): float
//     {
//         return $this->repository->getInstructorAverageRating($instructorId);
//     }

//     public function getCompletionRateForInstructor(int $instructorId): float
//     {
//         $courses = $this->repository->getCoursesByInstructor($instructorId);

//         $totalLessons = $courses->sum(function($course) {
//             return $course->lessons_count;
//         });

//         $completedLessons = $courses->sum(function($course) {
//             return $course->lessonCompletions()->count();
//         });

//         return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
//     }

//     private function generateUniqueSlug(string $title, ?int $excludeId = null): string
//     {
//         $slug = Str::slug($title);
//         $originalSlug = $slug;
//         $i = 1;

//         $query = Course::where('slug', $slug);
//         if ($excludeId) {
//             $query->where('id', '!=', $excludeId);
//         }

//         while ($query->exists()) {
//             $slug = $originalSlug.'-'.$i++;
//             $query = Course::where('slug', $slug);
//             if ($excludeId) {
//                 $query->where('id', '!=', $excludeId);
//             }
//         }

//         return $slug;
//     }

//     private function createLessons(Course $course, array $lessonsData): void
//     {
//         foreach ($lessonsData as $lessonData) {
//             $lessonData['slug'] = $this->generateUniqueLessonSlug($lessonData['title']);
//             $course->lessons()->create($lessonData);
//         }
//     }

//     private function generateUniqueLessonSlug(string $title): string
//     {
//         $slug = Str::slug($title);
//         $originalSlug = $slug;
//         $i = 1;

//         while (Lesson::where('slug', $slug)->exists()) {
//             $slug = $originalSlug.'-'.$i++;
//         }

//         return $slug;
//     }

//     protected function validateCourseOwnership(int $courseId): void
//     {
//         if (!$this->isCourseOwner($courseId)) {
//             throw new UnauthorizedAccessException();
//         }
//     }

//     protected function isCourseOwner(int $courseId): bool
//     {
//         return $this->repository->isCourseOwner($courseId, Auth::id());
//     }
}
