<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;

class LessonPlayer extends Component
{
    public $course;
    public $lessons;
    public $currentLesson;
    public $isOpen = false;
    public $progress;

    protected $listeners = ['openLessonModal'];

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->lessons = $course->lessons()->orderBy('order')->get();
    }

    public function openLessonModal($lessonId)
    {
        $this->currentLesson = Lesson::findOrFail($lessonId);
        $this->isOpen = true;
        $this->updateProgress();
    }

    public function nextLesson()
    {
        $nextLesson = $this->course->lessons()
            ->where('order', '>', $this->currentLesson->order)
            ->orderBy('order')
            ->first();

        if ($nextLesson) {
            $this->currentLesson = $nextLesson;
            $this->updateProgress();
        }
    }

    public function prevLesson()
    {
        $prevLesson = $this->course->lessons()
            ->where('order', '<', $this->currentLesson->order)
            ->orderByDesc('order')
            ->first();

        if ($prevLesson) {
            $this->currentLesson = $prevLesson;
            $this->updateProgress();
        }
    }

    public function updateProgress()
    {
        // Burada kullanıcının ders ilerlemesini güncelleyebilirsiniz
        // Örneğin:
        // auth()->user()->lessons()->syncWithoutDetaching([$this->currentLesson->id => ['completed' => true]]);
        $this->progress = 75; // Örnek değer, gerçek ilerlemeyi hesaplayın
    }
    public function render()
    {
        return view('livewire.lesson-player');
    }
}
