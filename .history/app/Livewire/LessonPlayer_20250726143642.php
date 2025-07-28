<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;
use Illuminate\Support\Str;

class LessonPlayer extends Component
{

  public Course $course;
    public $lessons;
    public $currentLesson = null;
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

        Log::info('Lessons loaded', [
            'count' => $this->lessons->count(),
            'first_lesson' => $this->lessons->first()?->toArray()
        ]);
    }

    public function openModal($lessonId)
    {
        Log::info('Attempting to open lesson', ['lessonId' => $lessonId]);

        $this->currentLesson = Lesson::with('course')->find($lessonId);

        if (!$this->currentLesson) {
            Log::error('Lesson not found', ['lessonId' => $lessonId]);
            $this->dispatch('notify',
                type: 'error',
                message: 'Ders bulunamadı!'
            );
            return;
        }

        Log::info('Lesson loaded successfully', [
            'lesson' => $this->currentLesson->toArray(),
            'video_id' => $this->currentLesson->video_id
        ]);

        $this->showModal = true;
    }

        public function render()
    {
        return view('livewire.lesson-player');
    }
}
