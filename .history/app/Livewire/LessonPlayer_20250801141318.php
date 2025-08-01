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
    public $showModal = false;

    public function mount($course, $lessonId = null)
    {
        $this->course = $course;

        if ($lessonId) {
            $this->loadLesson($lessonId);
        }
    }

 // LessonPlayer.php
public function loadLesson($lessonId)
{
    $this->reset(['lesson', 'videoId', 'videoType']); // Önceki verileri temizle
    $this->lesson = Lesson::findOrFail($lessonId);
    $this->videoId = $this->lesson->video_id;
    $this->videoType = $this->lesson->video_type;
    $this->showModal = true;
}


    public function closeModal()
    {
        $this->showModal = false;
        $this->lesson = null;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
