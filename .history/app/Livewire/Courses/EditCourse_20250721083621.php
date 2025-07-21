<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Category;
use App\Models\Level;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditCourse extends Component
{
    use WithFileUploads;

    public $courseId;
    public $course;
    public $form = [
        'title' => '',
        'description' => '',
        'price' => 0,
        'category_id' => '',
        'level_id' => '',
        'outcomes' => [],
        'prerequisites' => [],
        'lessons' => [],
    ];
    public $image;
    public $currentImage;
    public $newOutcome = '';
    public $newPrerequisite = '';
    public $currentLesson = [
        'title' => '',
        'description' => '',
        'video_url' => '',
        'duration_minutes' => 30,
        'is_free' => false,
    ];

    protected $rules = [
        'form.title' => 'required|string|max:255',
        'form.description' => 'required|string',
        'form.price' => 'required|numeric|min:0',
        'form.category_id' => 'required|exists:categories,id',
        'form.level_id' => 'required|exists:levels,id',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->course = Course::with('lessons')->findOrFail($courseId);

        if (auth()->id() !== $this->course->user_id) {
            abort(403, 'Bu kursu düzenleme yetkiniz yok.');
        }

        $this->form = [
            'title' => $this->course->title,
            'description' => $this->course->description,
            'price' => $this->course->price,
            'category_id' => $this->course->category_id,
            'level_id' => $this->course->level_id,
            'outcomes' => $this->course->outcomes ?? [],
            'prerequisites' => $this->course->prerequisites ?? [],
            'lessons' => $this->course->lessons->map(function ($lesson) {
                return $lesson->only(['id', 'title', 'description', 'video_url', 'duration_minutes', 'is_free']);
            })->toArray(),
        ];

        $this->currentImage = $this->course->image;
    }

    public function addOutcome()
    {
        $this->validate(['newOutcome' => 'required|string|max:255']);
        $this->form['outcomes'][] = $this->newOutcome;
        $this->newOutcome = '';
    }

    public function removeOutcome($index)
    {
        unset($this->form['outcomes'][$index]);
        $this->form['outcomes'] = array_values($this->form['outcomes']);
    }

    public function addPrerequisite()
    {
        $this->validate(['newPrerequisite' => 'required|string|max:255']);
        $this->form['prerequisites'][] = $this->newPrerequisite;
        $this->newPrerequisite = '';
    }

    public function removePrerequisite($index)
    {
        unset($this->form['prerequisites'][$index]);
        $this->form['prerequisites'] = array_values($this->form['prerequisites']);
    }

    public function addLesson()
    {
        $this->validate([
            'currentLesson.title' => 'required|string|max:255',
            'currentLesson.description' => 'required|string',
            'currentLesson.video_url' => 'required|url',
    'currentLesson.duration_minutes' => 'required|integer|min:1',
        ]);

        $this->form['lessons'][] = $this->currentLesson;
        $this->resetCurrentLesson();
    }

    public function removeLesson($index)
    {
        unset($this->form['lessons'][$index]);
        $this->form['lessons'] = array_values($this->form['lessons']);
    }

    public function removeImage()
    {
        $this->currentImage = null;
    }

    private function resetCurrentLesson()
    {
        $this->currentLesson = [
            'title' => '',
            'description' => '',
            'video_url' => '',
            'duration_minutes' => 30,
            'is_free' => false,
        ];
    }

    public function save()
    {
        $this->validate();

        $data = $this->form;

        if ($this->image) {
            if ($this->currentImage) {
                Storage::disk('public')->delete($this->currentImage);
            }
            $data['image'] = $this->image->store('course_images', 'public');
        } elseif (!$this->currentImage) {
            $data['image'] = null;
        } else {
            $data['image'] = $this->currentImage;
        }

        try {
            $service = app()->make(\App\Services\CourseService::class);
            $service->updateCourse($this->courseId, $data);

            session()->flash('message', 'Kurs başarıyla güncellendi.');
            return redirect()->route('courses.show', $this->courseId);
        } catch (\Exception $e) {
            $this->addError('general', 'Hata oluştu: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.courses.edit-course', [
            'categories' => Category::all(),
            'levels' => Level::all(),
        ]);
    }
}
