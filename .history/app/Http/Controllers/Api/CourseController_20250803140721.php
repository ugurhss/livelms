<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Exceptions\UnauthorizedAccessException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseController extends Controller
{
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(Request $request)
    {
        $courses = $this->courseService->getPublishedCourses($request->all());
        return view('course.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('course.courses.create');
    }

public function store(StoreCourseRequest $request)
{
    DB::beginTransaction();

    try {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $course = $this->courseService->createCourse($data);

        DB::commit();

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Kurs başarıyla oluşturuldu');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Course creation failed', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->except(['password', '_token'])
        ]);

        return back()->withInput()
            ->with('error', 'Kurs oluşturulurken bir hata oluştu: ' . $e->getMessage());
    }
}
 public function show(int $id)
{
    try {
        $course = $this->courseService->getCourseById($id);

        $isEnrolled = Auth::check() ? $this->courseService->checkEnrollment($id, Auth::id()) : false;
        $isInstructor = Auth::check() && $this->courseService->isCourseOwner($id, Auth::id());

        // Load necessary relationships
        $course->load([
            'lessons',
            'instructor',
            'reviews',
            'students',
            'enrollments'
        ]);

        // Calculate additional statistics
        $course->lessons_count = $course->lessons->count();
        $course->duration = $course->lessons->sum('duration_minutes');
        $course->students_count = $course->students->count();
        $course->reviews_count = $course->reviews->count();
        $course->reviews_avg_rating = $course->reviews->avg('rating');

        return view('course.courses.show', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
            'isInstructor' => $isInstructor,
            'loading' => false,
            'error' => null,
            'expandableLessons' => true, // or implement your logic for this
            'expandAll' => false, // default state
            'expandedLessons' => [] // default empty array
        ]);

    } catch (ModelNotFoundException $e) {
        return redirect()->route('courses.index')
            ->with('error', 'Kurs bulunamadı');
    } catch (\Exception $e) {
        Log::error('Course show error: ' . $e->getMessage());

        return view('course.courses.show', [
            'loading' => false,
            'error' => 'Kurs yüklenirken bir hata oluştu: ' . $e->getMessage(),
            'course' => null
        ]);
    }
}

    public function edit(int $id)
    {
        try {
            $course = $this->courseService->getCourseById($id);

            if (!$this->courseService->isCourseOwner($id, Auth::id())) {
                return redirect()->route('courses.index')
                    ->with('error', 'Bu işlem için yetkiniz yok');
            }

            return view('course.courses.edit', compact('course'));
        } catch (\Exception $e) {
            return redirect()->route('courses.index')
                ->with('error', 'Kurs bulunamadı');
        }
    }

   public function update(UpdateCourseRequest $request, int $id)
{
    DB::beginTransaction();

    try {
        if (!$this->courseService->isCourseOwner($id, Auth::id())) {
            return redirect()->route('courses.index')
                ->with('error', 'Bu işlem için yetkiniz yok');
        }

        $data = $request->validated();

        // Handle thumbnail update if present
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');

            // Optionally delete old thumbnail
            $course = $this->courseService->getCourseById($id);
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
        }

        $this->courseService->updateCourse($id, $data);

        DB::commit();

        return redirect()->route('courses.show', $id)
            ->with('success', 'Kurs başarıyla güncellendi');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Course update failed', [
            'user_id' => Auth::id(),
            'course_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->except(['password', '_token'])
        ]);

        return back()->withInput()
            ->with('error', 'Kurs güncellenirken bir hata oluştu: ' . $e->getMessage());
    }
}

    public function destroy(int $id)
    {
        try {
            if (!$this->courseService->isCourseOwner($id, Auth::id())) {
                return redirect()->route('courses.index')
                    ->with('error', 'Bu işlem için yetkiniz yok');
            }

            $this->courseService->deleteCourse($id);

            return redirect()->route('courses.index')
                ->with('success', 'Kurs başarıyla silindi');
        } catch (\Exception $e) {
            return redirect()->route('courses.index')
                ->with('error', 'Kurs silinirken hata oluştu: ' . $e->getMessage());
        }
    }
public function enroll(Request $request, Course $course)
{
    try {
        $this->courseService->enrollUser($course->id, Auth::id());

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Kursa başarıyla kaydoldunuz');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Kursa kaydolunamadı: ' . $e->getMessage());
    }
}

    public function unenroll(Request $request, int $courseId)
    {
        try {
            $this->courseService->unenrollUser($courseId, Auth::id());

            return redirect()->route('courses.show', $courseId)
                ->with('success', 'Kurstan başarıyla ayrıldınız');
        } catch (\Exception $e) {
            return redirect()->route('courses.show', $courseId)
                ->with('error', 'Kurstan ayrılamadınız: ' . $e->getMessage());
        }
    }

    public function instructorCourses(Request $request)
{
    $stats = [
        'total_courses' => $this->courseService->countInstructorCourses(Auth::id()),
        'total_students' => $this->courseService->countInstructorStudents(Auth::id()),
        'average_rating' => $this->courseService->getInstructorAverageRating(Auth::id()),
        'total_earnings' => $this->courseService->calculateInstructorEarnings(Auth::id()),
    ];

    $filters = $request->only(['status']);
    $courses = $this->courseService->getCoursesByInstructor(Auth::id(), $filters);

    return view('course.courses.instructor-index', [
        'courses' => $courses,
        'stats' => $stats
    ]);
}

   public function myCourses()
{
    $courses = $this->courseService->getEnrolledCourses(Auth::id());
    return view('course.courses.my-courses', compact('courses'));
}

    public function quiz(int $courseId)
    {
        $course = $this->courseService->getCourseById($courseId);
        return view('courses.quiz', compact('courseId'));
    }









    public function learn(Course $course)
{
    if (!$this->courseService->checkEnrollment($course->id, Auth::id())) {
        return redirect()->route('courses.show', $course->id)
            ->with('error', 'Bu kursa erişim izniniz yok');
    }

    $lastLesson = $course->lessons()->orderBy('order')->first();

    return view('courses.learn', [
        'course' => $course,
        'lessons' => $course->lessons()->orderBy('order')->get(),
        'lastLesson' => $lastLesson,
        'progress' => $this->courseService->getUserProgress($course->id, Auth::id())
    ]);
}
}
