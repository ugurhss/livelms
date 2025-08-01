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

    public function mount($course, $lessonId = null)
    {
        $this->course = $course;

        if ($lessonId) {
            $this->loadLesson($lessonId);
        }
    }
public function getNextLessonAttribute()
{
    return $this->course->lessons()
        ->where('order', '>', $this->lesson->order)
        ->orderBy('order')
        ->first();
}
    public function loadLesson($lessonId)
    {
        $this->lesson = Lesson::findOrFail($lessonId);
        $this->videoId = $this->lesson->video_id;
        $this->videoType = $this->lesson->video_type;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
