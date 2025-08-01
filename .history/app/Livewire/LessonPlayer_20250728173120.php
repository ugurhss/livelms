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

    protected $listeners = [
        'loadLesson' => 'loadLesson',
        'closeModal' => 'closeModal',
        'lessonCompleted' => 'markAsCompleted'
    ];

    public function mount($course, $lessonId = null)
    {
        $this->course = $course;

        if ($lessonId) {
            $this->loadLesson($lessonId);
        }

        $this->calculateUserProgress();
    }

    public function loadLesson($lessonId)
    {
        try {
            $this->lesson = Lesson::findOrFail($lessonId);
            $this->videoId = $this->lesson->video_id;
            $this->videoType = $this->lesson->video_type;
            $this->showModal = true;
            $this->calculateUserProgress();

            // Check if current lesson is completed
            $this->checkLessonCompletion();

            // Emit event to update URL or perform other actions
            $this->dispatch('lessonLoaded', ['lessonId' => $lessonId]);

        } catch (\Exception $e) {
            session()->flash('error', 'Ders yüklenirken bir hata oluştu.');
        }
    }

    public function checkLessonCompletion()
    {
        if (Auth::check() && $this->lesson) {
            $this->isLessonCompleted = Auth::user()->completedLessons()
                ->where('lesson_id', $this->lesson->id)
                ->exists();
        }
    }

    public function calculateUserProgress()
    {
        if (Auth::check()) {
            $user = Auth::user();

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
            Auth::user()->completedLessons()->attach($this->lesson->id, [
                'course_id' => $this->course->id,
                'completed_at' => now()
            ]);

            $this->isLessonCompleted = true;
            $this->calculateUserProgress();

            // Emit events for other components
            $this->dispatch('progressUpdated');
            $this->dispatch('lessonCompleted', ['lessonId' => $this->lesson->id]);

            session()->flash('success', 'Ders tamamlandı olarak işaretlendi!');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('modalClosed');
    }

    public function nextLesson()
    {
        if (!$this->lesson) return;

        $lessons = $this->course->lessons()->orderBy('order')->get();
        $currentIndex = $lessons->search(function ($item) {
            return $item->id === $this->lesson->id;
        });

        if ($currentIndex !== false && $currentIndex < $lessons->count() - 1) {
            $nextLesson = $lessons[$currentIndex + 1];
            $this->loadLesson($nextLesson->id);
        }
    }

    public function previousLesson()
    {
        if (!$this->lesson) return;

        $lessons = $this->course->lessons()->orderBy('order')->get();
        $currentIndex = $lessons->search(function ($item) {
            return $item->id === $this->lesson->id;
        });

        if ($currentIndex !== false && $currentIndex > 0) {
            $previousLesson = $lessons[$currentIndex - 1];
            $this->loadLesson($previousLesson->id);
        }
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
