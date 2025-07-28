<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LessonPlayer extends Component
{

 public Course $course;
    public $lessons;
    public $currentLesson;
    public $showModal = false;

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->lessons = $course->lessons()
                               ->orderBy('order')
                               ->get()
                               ->each(function ($lesson) {
                                   $lesson->loadMissing('course');
                               });
    }


    public function openModal($lessonId)
    {
        try {
            $this->currentLesson = Lesson::with('course')->findOrFail($lessonId);
            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Ders yüklenirken bir hata oluştu: ' . $e->getMessage()
            ]);
            $this->currentLesson = null;
            $this->showModal = false;
        }
    }

        public function render()
    {
        return view('livewire.lesson-player');
    }
}
