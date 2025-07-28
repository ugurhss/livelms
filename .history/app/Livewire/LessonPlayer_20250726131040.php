<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;

class LessonPlayer extends Component
{

    public $course;
    public $lessons;
    public $currentLesson = null;
    public $isOpen = false;
    public $progress = 0;

    protected $listeners = ['openLessonModal'];

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->lessons = $course->lessons()->orderBy('order')->get();

        if ($this->lessons->isNotEmpty()) {
            $this->currentLesson = $this->lessons->first();
        }
    }

    public function openLessonModal($lessonId)
    {
        try {
            $this->currentLesson = Lesson::findOrFail($lessonId);
            $this->isOpen = true;
            $this->updateProgress();
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Ders bulunamadı!'
            ]);
        }
    }

    public function nextLesson()
    {
        if (!$this->currentLesson) return;

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
        if (!$this->currentLesson) return;

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
        // Basit ilerleme simülasyonu (%25 artış)
        $this->progress = min($this->progress + 25, 100);
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
