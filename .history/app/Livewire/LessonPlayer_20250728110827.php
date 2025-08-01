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
        $this->course = $course;
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
        $this->lesson = Lesson::where('course_id', $this->course->id)
                            ->findOrFail($lessonId);
        $this->videoId = $this->lesson->video_id;
        $this->videoType = $this->lesson->video_type;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
