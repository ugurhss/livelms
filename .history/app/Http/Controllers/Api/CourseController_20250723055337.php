<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Exceptions\UnauthorizedAccessException;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseController extends Controller
{
    private CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;

        // Sadece auth middleware'ini kullanıyoruz çünkü bazı metodlar herkese açık

    }

    /**
     * Tüm kursları listeler (admin paneli için)
     */
    public function index(Request $request)
    {
        $courses = $this->courseService->getAllCourses($request->all());
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Yayınlanmış kursları listeler (ana sayfa için)
     */
    public function getPublishedCourses(Request $request)
    {
        return view('course.CourseList');
    }

    /**
     * Popüler kursları getirir (ana sayfa sidebar vb. için)
     */
    public function getPopularCourses()
    {
        $popularCourses = $this->courseService->getPopularCourses();
        return view('partials.popular-courses', compact('popularCourses'));
    }

    /**
     * Tek bir kursun detayını gösterir
     */
   public function show($id)
{
    $course = $this->courseService->getCourseById($id);

    if (!$course) {
        abort(404);
    }

    return view('course.show', [
        'course' => $course,
        'isEnrolled' => auth()->check() ? $this->courseService->checkEnrollment($id, auth()->id()) : false,
        'isInstructor' => auth()->check() && auth()->id() === $course->user_id,
        'totalDuration' => $this->calculateTotalDuration($course->lessons),
    ]);
}

private function calculateTotalDuration($lessons)
{
    if (!$lessons) return '0 dakika';

    $totalMinutes = $lessons->sum(function($lesson) {
        return intval($lesson->duration) ?: 15;
    });

    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    return $hours > 0
        ? sprintf("%d saat %d dakika", $hours, $minutes)
        : sprintf("%d dakika", $minutes);
}

    /**
     * Kurs oluşturma formunu gösterir
     */
    public function create()
    {
        return view('instructor.courses.create');
    }

    /**
     * Yeni kurs oluşturur
     */
    public function store(StoreCourseRequest $request)
    {
        $course = $this->courseService->createCourse($request->validated());
        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Kurs başarıyla oluşturuldu!');
    }

    /**
     * Kurs düzenleme formunu gösterir
     */
   public function edit($id)
{
    $course = $this->courseService->getCourseById($id);

    // Eğitmen kontrolü
    if (auth()->id() !== $course->user_id) {
        abort(403, 'Bu kursu düzenleme yetkiniz yok');
    }

    return view('course.edit', compact('course'));
}

    /**
     * Kurs bilgilerini günceller
     */
 public function update(Request $request, $id)
{
    try {
        $course = $this->courseService->updateCourse($id, $request->validated());

        return redirect()
            ->route('courses.show', $course->id)
            ->with('success', 'Kurs başarıyla güncellendi!');

    } catch (UnauthorizedAccessException $e) {
        abort(403, 'Bu kursu düzenleme yetkiniz yok');

    } catch (ModelNotFoundException $e) {
        abort(404, 'Kurs bulunamadı');

    } catch (\Exception $e) {
        Log::error('Course update failed: '.$e->getMessage(), [
            'course_id' => $id,
            'user_id' => auth()->id(),
            'data' => $request->validated()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Kurs güncellenirken bir hata oluştu. Lütfen tekrar deneyin.');
    }
}

    /**
     * Kursu siler
     */
    public function destroy($id)
    {
        $this->courseService->deleteCourse($id);
        return redirect()->route('instructor.courses')
            ->with('success', 'Kurs başarıyla silindi!');
    }

    /**
     * Kullanıcıyı kursa kaydeder
     */
    public function enroll($courseId)
    {
        $this->courseService->enrollUser($courseId, Auth::id());
        return back()->with('success', 'Kursa başarıyla kaydoldunuz!');
    }

    /**
     * Kullanıcının kurs kaydını siler
     */
    public function unenroll($courseId)
    {
        $this->courseService->unenrollUser($courseId, Auth::id());
        return back()->with('success', 'Kurs kaydınız iptal edildi.');
    }

    /**
     * Eğitmenin kurslarını listeler
     */
    public function instructorCourses(Request $request)
    {
        $courses = $this->courseService->getInstructorCourses(Auth::id(), $request->all());
        return view('instructor.courses.index', compact('courses'));
    }

    /**
     * Kullanıcının kayıtlı olduğu kursları listeler
     */
    public function enrolledCourses()
    {
        $courses = $this->courseService->getEnrolledCourses(Auth::id());
        return view('student.courses', compact('courses'));
    }

    /**
     * Kurs istatistiklerini gösterir
     */
    public function stats($courseId)
    {
        $stats = $this->courseService->getCourseStatistics($courseId);
        return view('courses.stats', compact('stats'));
    }

    /**
     * Kurs arama sonuçlarını gösterir
     */
    public function search(Request $request)
    {
        $courses = $this->courseService->searchCourses($request->query('q'), $request->all());
        return view('courses.search', compact('courses'));
    }

    /**
     * Öğrencinin kurs ilerlemesini gösterir
     */
    public function progress()
    {
        $courses = $this->courseService->getStudentCoursesWithProgress(Auth::id());
        return view('student.progress', compact('courses'));
    }

    /**
     * Eğitmen istatistiklerini gösterir
     */
    public function instructorStats()
    {
        $instructorId = Auth::id();
        $totalStudents = $this->courseService->getTotalStudentsForInstructor($instructorId);
        $averageRating = $this->courseService->getAverageRatingForInstructor($instructorId);
        $completionRate = $this->courseService->getCompletionRateForInstructor($instructorId);

        return view('instructor.stats', compact(
            'totalStudents',
            'averageRating',
            'completionRate'
        ));
    }







    // app/Http/Controllers/Api/CourseController.php

public function createWithLessons()
{
    $categories = Category::all();
    return view('course.edit', [
        'categories' => $categories,
        'course' => null,
        'lessons' => [],
        'isEdit' => false
    ]);
}

public function storeWithLessons(StoreCourseRequest $request)
{
    $validated = $request->validated();

    $course = $this->courseService->createCourseWithLessons(
        $validated['course'],
        $validated['lessons']
    );

    return redirect()->route('courses.show', $course->id)
                     ->with('success', 'Kurs ve dersler başarıyla oluşturuldu!');
}

public function editWithLessons($id)
{
    $course = $this->courseService->getCourseWithLessons($id);

    $categories = Category::all();

    return view('course.edit', [
        'categories' => $categories,
        'course' => $course,
        'lessons' => $course->lessons->toArray(),
        'isEdit' => true
    ]);
}

public function updateWithLessons(UpdateCourseRequest $request, $id)
{
    $validated = $request->validated();

    $course = $this->courseService->updateCourseWithLessons(
        $id,
        $validated['course'],
        $validated['lessons']
    );

    return redirect()->route('courses.show', $course->id)
                     ->with('success', 'Kurs ve dersler başarıyla güncellendi!');
}
}
