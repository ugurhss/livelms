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
    public $isOpen = false;

    protected $listeners = ['openLessonPlayer' => 'open'];

    public function mount($course)
    {
        $this->course = $course->load(['lessons']); // Eager load lessons
    }

    public function open($lessonId)
    {
        $this->loadLesson($lessonId);
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function loadLesson($lessonId)
    {
        $this->lesson = $this->course->lessons->find($lessonId);

        if (!$this->lesson) {
            $this->lesson = Lesson::where('course_id', $this->course->id)
                                ->findOrFail($lessonId);
            // Reload course with lessons if not already loaded
            $this->course->load(['lessons']);
        }

        $this->videoId = $this->lesson->video_id;
        $this->videoType = $this->lesson->video_type;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
