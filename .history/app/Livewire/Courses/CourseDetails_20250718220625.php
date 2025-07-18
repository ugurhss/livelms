<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use App\Services\CourseService;
use Illuminate\Support\Facades\Auth;

class CourseDetails extends Component
{
    public $courseId;
    public $course;
    public $loading = true;
    public $error = null;
    public $expandedLessons = [];
    public $expandAll = false;
    public $selectedLesson = null;
    public $enrolling = false;
 public $isEnrolled = false;
    public $isInstructor = false;

    protected $listeners = ['lessonClosed' => 'closeLessonViewer'];

    // public function mount($id)
    // {
    //     $this->courseId = $id;
    //     $this->loadCourse();
    // }

       public function mount(CourseService $courseService, $course)
    {
        // Parametreyi integer'a çevir
        $courseId = (int)$course;
        $this->course = $courseService->getCourseById($courseId);
    }

    public function loadCourse()
    {
        $this->loading = true;
        $this->error = null;

        try {
            $courseService = app(CourseService::class);
            $this->course = $courseService->getCourseById($this->courseId);

                        $this->isInstructor = Auth::user()->isInstructor() && $this->course->user_id == Auth::id();

            // Kullanıcının kursa kayıtlı olup olmadığını kontrol et
            // $this->isEnrolled = $courseService->checkEnrollment($this->courseId, Auth::id());

            // Kullanıcının eğitmen olup olmadığını ve bu kursun sahibi olup olmadığını kontrol et

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }
    public function getTotalDurationProperty()
{
    if (!$this->course || !$this->course->lessons) {
        return '0 dk';
    }

    $totalMinutes = collect($this->course->lessons)->sum(function($lesson) {
        // "15 min" gibi string'lerden sadece sayıyı al
        return intval(preg_replace('/[^0-9]/', '', $lesson->duration ?? '15 min'));
    });

    if ($totalMinutes < 60) {
        return $totalMinutes . ' dk';
    }

    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    return $hours . ' sa ' . $minutes . ' dk';
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

    public function closeLessonViewer()
    {
        $this->selectedLesson = null;
    }

    public function enrollInCourse()
    {
        $this->enrolling = true;

        try {
            $courseService = app(CourseService::class);
            $courseService->enrollUser($this->courseId, Auth::id());
            $this->isEnrolled = true;
            session()->flash('success', 'Successfully enrolled in the course!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        } finally {
            $this->enrolling = false;
        }
    }



   public function render()
{
    return view('livewire.courses.course-details');

}
}
