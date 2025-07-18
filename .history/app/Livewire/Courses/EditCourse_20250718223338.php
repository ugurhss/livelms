<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use App\Models\Course;
use App\Models\Level;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditCourse extends Component
{
    use WithFileUploads;

    public $course;
    public $title;
    public $description;
    public $image;
    public $currentImage;
    public $level_id;
    public $category_id;
    public $price;
    public $outcomes = [];
    public $prerequisites = [];
    public $newOutcome = '';
    public $newPrerequisite = '';
    public $lessons = [];
    public $currentLesson = [
        'title' => '',
        'description' => '',
        'video_url' => '',
        'duration' => 0,
        'is_free' => false
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|min:50',
        'image' => 'nullable|image|max:2048',
        'level_id' => 'required|exists:levels,id',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'outcomes' => 'array',
        'outcomes.*' => 'string|max:255',
        'prerequisites' => 'array',
        'prerequisites.*' => 'string|max:255',
        'lessons' => 'array|min:1',
        'lessons.*.title' => 'required|string|max:255',
        'lessons.*.description' => 'required|string|min:20',
        'lessons.*.video_url' => 'required|url',
        'lessons.*.duration' => 'required|integer|min:1',
        'lessons.*.is_free' => 'boolean'
    ];

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->currentImage = $course->image;
        $this->level_id = $course->level_id;
        $this->category_id = $course->category_id;
        $this->price = $course->price;
        $this->outcomes = $course->outcomes ?? [];
        $this->prerequisites = $course->prerequisites ?? [];

        // Dersleri yükle
        $this->lessons = $course->lessons->map(function($lesson) {
            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'video_url' => $lesson->video_url,
                'duration' => $lesson->duration,
                'is_free' => $lesson->is_free
            ];
        })->toArray();
    }

    public function addOutcome()
    {
        $this->validate(['newOutcome' => 'required|string|max:255']);
        $this->outcomes[] = $this->newOutcome;
        $this->newOutcome = '';
    }

    public function removeOutcome($index)
    {
        unset($this->outcomes[$index]);
        $this->outcomes = array_values($this->outcomes);
    }

    public function addPrerequisite()
    {
        $this->validate(['newPrerequisite' => 'required|string|max:255']);
        $this->prerequisites[] = $this->newPrerequisite;
        $this->newPrerequisite = '';
    }

    public function removePrerequisite($index)
    {
        unset($this->prerequisites[$index]);
        $this->prerequisites = array_values($this->prerequisites);
    }

    public function addLesson()
    {
        $this->validate([
            'currentLesson.title' => 'required|string|max:255',
            'currentLesson.description' => 'required|string|min:20',
            'currentLesson.video_url' => 'required|url',
            'currentLesson.duration' => 'required|integer|min:1'
        ]);

        $this->lessons[] = $this->currentLesson;
        $this->resetCurrentLesson();
    }

    public function removeLesson($index)
    {
        unset($this->lessons[$index]);
        $this->lessons = array_values($this->lessons);
    }

    public function resetCurrentLesson()
    {
        $this->currentLesson = [
            'title' => '',
            'description' => '',
            'video_url' => '',
            'duration' => 0,
            'is_free' => false
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'level_id' => $this->level_id,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'outcomes' => $this->outcomes,
            'prerequisites' => $this->prerequisites
        ];

        if ($this->image) {
            // Eski resmi sil
            if ($this->currentImage) {
                Storage::delete($this->currentImage);
            }
            $data['image'] = $this->image->store('courses', 'public');
        }

        // Kursu güncelle
        $this->course->update($data);

        // Dersleri güncelle
        $this->course->lessons()->delete();
        foreach ($this->lessons as $lesson) {
            $this->course->lessons()->create($lesson);
        }

        session()->flash('message', 'Kurs başarıyla güncellendi!');
        return redirect()->route('courses.show', $this->course->id);
    }

    public function render()
    {
        return view('livewire.courses.edit-course', [
            'categories' => Category::all(),
            'levels' => Level::all()
        ])->layout('layouts.app');
    }
}
