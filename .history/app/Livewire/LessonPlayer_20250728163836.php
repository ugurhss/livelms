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

    public function loadLesson($lessonId)
    {
        $this->lesson = Lesson::findOrFail($lessonId);
        $this->videoId = $this->lesson->video_id;
        $this->videoType = $this->lesson->video_type;
        $this->showModal = true;

        // Dersin görüntülendiğini işaretlemek için buraya gerekli kodu ekleyebilirsiniz
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
