<?php

namespace App\Livewire;

use App\Models\Lesson;
use Livewire\Component;

class LessonPlayer extends Component
{
    public $course;
    public $lesson;
    public $videoId;
    public $videoType;
    public $isCompleted = false;
    public $progressPercentage = 0;

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

        if (auth()->check()) {
            $this->isCompleted = $this->lesson->completedBy->contains(auth()->id());
            $this->calculateProgress();
        }
    }

    protected function calculateProgress()
    {
        if (!auth()->check()) {
            $this->progressPercentage = 0;
            return;
        }

        $completedLessons = auth()->user()->completedLessons()
                            ->whereHas('course', function($query) {
                                $query->where('id', $this->course->id);
                            })
                            ->count();

        $totalLessons = $this->course->lessons()->count();

        $this->progressPercentage = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;
    }

    public function toggleCompletion()
    {
        if (!auth()->check()) {
            return;
        }

        if ($this->isCompleted) {
            $this->lesson->completedBy()->detach(auth()->id());
        } else {
            $this->lesson->completedBy()->attach(auth()->id(), [
                'course_id' => $this->course->id
            ]);
        }

        $this->isCompleted = !$this->isCompleted;
        $this->calculateProgress();
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
