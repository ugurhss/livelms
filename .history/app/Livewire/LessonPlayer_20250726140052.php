<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;

class LessonPlayer extends Component
{

    public Course $course;
    public $lessons;
    public $currentLesson;
    public $showModal = false;

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->lessons = $course->lessons()->orderBy('order')->get();
    }

    public function openModal($lessonId)
    {
        $this->currentLesson = Lesson::find($lessonId);

        if (!$this->currentLesson) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Ders bulunamadı!'
            ]);
            return;
        }

        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
