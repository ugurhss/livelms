<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    // Admin methods
    public function countTotalUsers(): int
    {
        return User::count();
    }

    public function countTotalCourses(): int
    {
        return Course::count();
    }

    public function countActiveEnrollments(): int
    {
        return Enrollment::whereNull('completed_at')->count();
    }

    public function calculateMonthlyRevenue(): float
    {
        $currentMonth = now()->format('Y-m');
        return (float) Enrollment::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $currentMonth)
            ->sum('price');
    }

    public function getEnrollmentTrends(int $months = 12): array
    {
        $trends = [];
        $endDate = now();
        $startDate = now()->subMonths($months - 1);

        $results = Enrollment::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('count(*) as enrollments')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fill in missing months with 0
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1M'),
            $endDate
        );

        foreach ($period as $date) {
            $month = $date->format('Y-m');
            $found = $results->firstWhere('month', $month);
            $trends[] = [
                'month' => $date->format('M Y'),
                'enrollments' => $found ? $found->enrollments : 0
            ];
        }

        return $trends;
    }

    public function getRecentUsers(int $limit = 5): array
    {
        return User::withCount(['courses'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'role' => $user->role,
                    'courses_count' => $user->courses_count,
                    'joined_at' => $user->created_at->format('d M Y')
                ];
            })
            ->toArray();
    }

    // Instructor methods
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

    public function countInstructorAssignments(int $instructorId): int
    {
        return Assignment::whereHas('course', function ($query) use ($instructorId) {
            $query->where('user_id', $instructorId);
        })->count();
    }

    public function getInstructorRecentSubmissions(int $instructorId, int $limit = 5): array
    {
        return AssignmentSubmission::whereHas('assignment.course', function ($query) use ($instructorId) {
            $query->where('user_id', $instructorId);
        })
            ->with(['assignment', 'user'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'assignment_title' => $submission->assignment->title,
                    'student_name' => $submission->user->name,
                    'submitted_at' => $submission->created_at->format('d M Y H:i'),
                    'grade' => $submission->grade,
                    'file_path' => $submission->file_path
                ];
            })
            ->toArray();
    }

    public function getInstructorCoursePerformance(int $instructorId): array
    {
        return Course::where('user_id', $instructorId)
            ->withCount(['students', 'lessons'])
            ->withAvg('reviews', 'rating')
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'students_count' => $course->students_count,
                    'lessons_count' => $course->lessons_count,
                    'average_rating' => round($course->reviews_avg_rating, 1),
                    'image' => $course->image
                ];
            })
            ->toArray();
    }

    // Student methods
    public function getStudentEnrolledCourses(int $studentId): array
    {
        return Course::whereHas('students', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
            ->with(['instructor'])
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'instructor_name' => $course->instructor->name,
                    'progress' => $course->pivot->progress,
                    'image' => $course->image
                ];
            })
            ->toArray();
    }

    public function getStudentActiveAssignments(int $studentId): array
    {
        return Assignment::whereHas('course.students', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
            ->where('due_date', '>=', now())
            ->with(['course'])
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'course_title' => $assignment->course->title,
                    'due_date' => $assignment->due_date->format('d M Y H:i'),
                    'points' => $assignment->points
                ];
            })
            ->toArray();
    }

    public function getStudentCompletedAssignments(int $studentId): array
    {
        return AssignmentSubmission::where('user_id', $studentId)
            ->with(['assignment.course'])
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'assignment_title' => $submission->assignment->title,
                    'course_title' => $submission->assignment->course->title,
                    'submitted_at' => $submission->created_at->format('d M Y H:i'),
                    'grade' => $submission->grade,
                    'feedback' => $submission->feedback
                ];
            })
            ->toArray();
    }

    public function getStudentCourseProgress(int $studentId): array
    {
        return Course::whereHas('students', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
            ->with(['lessons'])
            ->get()
            ->map(function ($course) {
                $totalLessons = $course->lessons->count();
                $completedLessons = $course->lessons->where('completed', true)->count();

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'progress' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
                    'total_lessons' => $totalLessons,
                    'completed_lessons' => $completedLessons
                ];
            })
            ->toArray();
    }

    public function getStudentRecentAnnouncements(int $studentId, int $limit = 5): array
    {
        // Assuming you have an Announcement model with a relationship to courses
        return \App\Models\Announcement::whereHas('course.students', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
            ->with(['course'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'course_title' => $announcement->course->title,
                    'posted_at' => $announcement->created_at->format('d M Y H:i')
                ];
            })
            ->toArray();
    }
}
