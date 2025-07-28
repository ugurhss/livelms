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
    public $debugInfo = []; // Debug bilgileri için

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

        // Debug bilgilerini topla
        $this->collectDebugInfo();

        $this->showModal = true;
    }

    protected function collectDebugInfo()
    {
        $this->debugInfo = [
            'lesson_id' => $this->currentLesson->id,
            'video_url' => $this->currentLesson->video_url,
            'video_type' => $this->currentLesson->video_type ?? 'null',
            'is_youtube' => Str::contains($this->currentLesson->video_url, ['youtube.com', 'youtu.be']),
            'extracted_id' => $this->currentLesson->extractYouTubeId($this->currentLesson->video_url),
            'has_video' => !empty($this->currentLesson->video_url),
            'valid_url' => $this->isValidVideoUrl($this->currentLesson->video_url)
        ];

        // Loglara yaz
        \Log::info('Lesson Player Debug Info:', $this->debugInfo);
    }

    protected function isValidVideoUrl($url)
    {
        if (empty($url)) return false;

        if (Str::contains($url, ['youtube.com', 'youtu.be'])) {
            return !empty($this->currentLesson->extractYouTubeId($url));
        }

        return Str::startsWith($url, ['http://', 'https://']);
    }    public function render()
    {
        return view('livewire.lesson-player');
    }
}
