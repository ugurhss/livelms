<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
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
    try {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $course = $this->courseService->createCourse($data);

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Kurs başarıyla oluşturuldu');

    } catch (\Exception $e) {
        // ❗ Hata detaylarını log dosyasına yaz
        Log::error('Kurs oluşturulurken hata oluştu', [
            'user_id' => Auth::id(),
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);

        return back()->withInput()
            ->with('error', 'Kurs oluşturulurken bir hata oluştu. Lütfen tekrar deneyin.');
    }
}
    public function show(int $id)
    {
        try {
            $course = $this->courseService->getCourseById($id);
            $isEnrolled = Auth::check() ? $this->courseService->checkEnrollment($id, Auth::id()) : false;

            return view('course.courses.show', compact('course', 'isEnrolled'));
        } catch (\Exception $e) {

            return redirect()->route('courses.index')
                ->with('error', 'Kurs bulunamadı');
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
        try {
            if (!$this->courseService->isCourseOwner($id, Auth::id())) {
                return redirect()->route('courses.index')
                    ->with('error', 'Bu işlem için yetkiniz yok');
            }

            $data = $request->validated();
            $this->courseService->updateCourse($id, $data);

            return redirect()->route('courses.show', $id)
                ->with('success', 'Kurs başarıyla güncellendi');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Kurs güncellenirken hata oluştu: ' . $e->getMessage());
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

    public function enroll(Request $request, int $courseId)
    {
        try {
            $this->courseService->enrollUser($courseId, Auth::id());

            return redirect()->route('courses.show', $courseId)
                ->with('success', 'Kursa başarıyla kaydoldunuz');
        } catch (\Exception $e) {
            return redirect()->route('courses.show', $courseId)
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
}
