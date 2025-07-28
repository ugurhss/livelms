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

        // Eğer video_type ayarlanmamışsa manuel olarak ayarla
        if (empty($this->currentLesson->video_type) {
            $this->prepareVideoData($this->currentLesson);
        }

        $this->showModal = true;
    }

    protected function prepareVideoData($lesson)
    {
        if (empty($lesson->video_url)) {
            return;
        }

        if (str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be')) {
            $lesson->video_type = 'youtube';
            $lesson->video_id = $lesson->extractYouTubeId($lesson->video_url);
        }
        elseif (str_contains($lesson->video_url, 'vimeo.com')) {
            $lesson->video_type = 'vimeo';
            $lesson->video_id = $lesson->extractVimeoId($lesson->video_url);
        }
        elseif (preg_match('/\.(mp4|webm|ogg)$/i', $lesson->video_url)) {
            $lesson->video_type = 'html5';
        }
        else {
            $lesson->video_type = 'external';
        }
    }


    public function render()
    {
        return view('livewire.lesson-player');
    }
}
