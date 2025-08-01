<?php

namespace App\Livewire;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonPlayer extends Component
{
    public $course;
    public $lessonId;
    public $lesson;
    public $userProgress = 0;
    public $isLessonCompleted = false;

    protected $listeners = ['loadLesson'];

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
        $this->calculateUserProgress();
        $this->dispatch('lessonLoaded'); // JS'de scroll yapmak için
    }

    public function calculateUserProgress()
    {
        if (!Auth::check() || !$this->lesson) return;

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

    public function markAsCompleted()
    {
        if (!Auth::check() || !$this->lesson || $this->isLessonCompleted) return;

        Auth::user()->completedLessons()->attach($this->lesson->id);
        $this->isLessonCompleted = true;
        $this->calculateUserProgress();
        $this->dispatch('progressUpdated');
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
