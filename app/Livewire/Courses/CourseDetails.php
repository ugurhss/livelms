<?php

namespace App\Livewire\Courses;
use Livewire\Component;
use App\Services\CourseService;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseDetails extends Component
{
    public $courseId;
    public $course;
    public $isEnrolled = false;
    public $isInstructor = false;
    public $totalDuration = '0 dakika';
    public $loading = true;
    public $error = null;
    public $expandedLessons = [];
    public $expandAll = false;
    public $selectedLesson = null;

    protected $courseService;

    public function boot()
    {
        $this->courseService = app(CourseService::class);
    }

    public function mount($courseId, $initialIsEnrolled = null, $initialIsInstructor = null)
    {
        $this->courseId = $courseId;

        try {
            $this->loadCourseData();

            if (!is_null($initialIsEnrolled)) {
                $this->isEnrolled = $initialIsEnrolled;
            }

            if (!is_null($initialIsInstructor)) {
                $this->isInstructor = $initialIsInstructor;
            }

        } catch (\Exception $e) {
            $this->error = 'Kurs bilgileri yüklenirken bir hata oluştu.';
            $this->loading = false;
            return;
        }

        $this->loading = false;
    }

    protected function loadCourseData()
    {
        $this->course = $this->courseService->getCourseById($this->courseId);

        if (!$this->course) {
            abort(404);
        }

        $this->isEnrolled = auth()->check()
            ? $this->courseService->checkEnrollment($this->courseId, auth()->id())
            : false;

        $this->isInstructor = auth()->check() && auth()->id() === $this->course->user_id;
        $this->totalDuration = $this->calculateTotalDuration($this->course->lessons);
    }

    private function calculateTotalDuration($lessons)
    {
        if (!$lessons || $lessons->isEmpty()) return '0 dakika';

        $totalMinutes = $lessons->sum(function($lesson) {
            return intval($lesson->duration) ?: 15;
        });

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return $hours > 0
            ? sprintf("%d saat %d dakika", $hours, $minutes)
            : sprintf("%d dakika", $minutes);
    }

    public function enrollInCourse()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        try {
            $this->courseService->enrollUser($this->courseId, auth()->id());
            $this->isEnrolled = true;

            $this->dispatchBrowserEvent('show-alert', [
                'type' => 'success',
                'message' => 'Kursa başarıyla kaydoldunuz!'
            ]);

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('show-alert', [
                'type' => 'error',
                'message' => 'Kayıt işlemi sırasında bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleLesson($index)
    {
        if (in_array($index, $this->expandedLessons)) {
            $this->expandedLessons = array_diff($this->expandedLessons, [$index]);
        } else {
            $this->expandedLessons[] = $index;
        }
    }

    public function toggleExpandAll()
    {
        $this->expandAll = !$this->expandAll;

        if ($this->expandAll) {
            $this->expandedLessons = range(0, count($this->course->lessons) - 1);
        } else {
            $this->expandedLessons = [];
        }
    }

    public function startLesson($index)
    {
        $this->selectedLesson = $index;
    }

    public function render()
    {
        return view('livewire.courses.course-details');
    }
}
