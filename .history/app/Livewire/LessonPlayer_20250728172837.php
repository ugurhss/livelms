<?php

namespace App\Livewire;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonPlayer extends Component
{
    public $course;
    public $lesson;
    public $videoId;
    public $videoType;
    public $showModal = false;
    public $userProgress = 0;
    public $isLessonCompleted = false;

    protected $listeners = ['closeModal', 'lessonCompleted' => 'markAsCompleted'];

    public function mount($course, $lessonId = null)
    {
        $this->course = $course;

        if ($lessonId) {
            $this->loadLesson($lessonId);
        }
    }

    public function loadLesson($lessonId)
{
    $this->lesson = Lesson::findOrFail($lessonId);
    $this->videoId = $this->lesson->video_id;
    $this->videoType = $this->lesson->video_type;
    $this->showModal = true;
    $this->calculateUserProgress();
}

    public function showLessonModal()
    {
        if ($this->lesson) {
            $this->showModal = true;
        }
    }

    public function calculateUserProgress()
    {
        if (Auth::check() && $this->lesson) {
            $user = Auth::user();

            $this->isLessonCompleted = $user->completedLessons()
                ->where('lesson_id', $this->lesson->id)
                ->exists();

            $completedLessons = $user->completedLessons()
                ->where('course_id', $this->course->id)
                ->count();

            $totalLessons = $this->course->lessons()->count();

            $this->userProgress = $totalLessons > 0
                ? round(($completedLessons / $totalLessons) * 100)
                : 0;
        }
    }

    public function markAsCompleted()
    {
        if (Auth::check() && $this->lesson && !$this->isLessonCompleted) {
            Auth::user()->completedLessons()->attach($this->lesson->id);
            $this->isLessonCompleted = true;
            $this->calculateUserProgress();
            $this->emit('progressUpdated');
        }
    }

  public function closeModal()
{
    $this->showModal = false;
    $this->dispatch('modalClosed'); // Dispatch event for JavaScript
}

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
