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
    public $isCompleted = false; // Bu satırı ekliyoruz

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

        // Giriş yapmış kullanıcı için tamamlama durumunu kontrol ediyoruz
        if (auth()->check()) {
            $this->isCompleted = $this->lesson->completedBy->contains(auth()->id());
        }
    }

    public function toggleCompletion()
    {
        if (!auth()->check()) {
            return;
        }

        if ($this->isCompleted) {
            $this->lesson->completedBy()->detach(auth()->id());
        } else {
            $this->lesson->completedBy()->attach(auth()->id());
        }

        $this->isCompleted = !$this->isCompleted;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
