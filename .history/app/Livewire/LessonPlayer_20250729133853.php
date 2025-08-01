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

    protected $listeners = ['loadLesson'];

    public function mount($course, $lessonId = null)
    {
        $this->course = $course;
        $this->calculateUserProgress();

        if ($lessonId) {
            $this->loadLesson($lessonId);
        }
    }

    public function loadLesson($lessonId)
    {
        try {
            $this->lesson = $this->course->lessons()->findOrFail($lessonId);
            $this->videoId = $this->lesson->video_id;
            $this->videoType = $this->lesson->video_type;
            $this->showModal = true;
            $this->calculateUserProgress();
            $this->checkLessonCompletion();

            // Modal açıldığında JavaScript'e bildir
            $this->dispatch('modalOpened');
        } catch (\Exception $e) {
            session()->flash('error', 'Ders yüklenirken bir hata oluştu.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('modalClosed');
    }

    public function calculateUserProgress()
    {
        if (Auth::check() && $this->course) {
            $user = Auth::user();

            $completedLessons = $user->completedLessons()
                ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
                ->count();

            $totalLessons = $this->course->lessons()->count();

            $this->userProgress = $totalLessons > 0
                ? round(($completedLessons / $totalLessons) * 100)
                : 0;
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

    public function markAsCompleted()
    {
        if (Auth::check() && $this->lesson) {
            $user = Auth::user();

            if (!$this->isLessonCompleted) {
                // Dersi tamamlandı olarak işaretle
                $user->completedLessons()->syncWithoutDetaching([$this->lesson->id => [
                    'course_id' => $this->course->id,
                    'completed_at' => now()
                ]]);

                $this->isLessonCompleted = true;
                $this->calculateUserProgress();

                session()->flash('success', 'Ders başarıyla tamamlandı!');
            }
        }
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
