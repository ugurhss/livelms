<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use App\Services\CourseService;
use App\Models\Course;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class EditCourse extends Component
{
    use WithFileUploads;

    public Course $course;
    public $title;
    public $description;
    public $image;
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
        'prerequisites' => 'array',
        'lessons.*.title' => 'required|string|max:255',
        'lessons.*.description' => 'required|string|min:20',
        'lessons.*.video_url' => 'required|url',
        'lessons.*.duration' => 'required|integer|min:1'
    ];

    public function mount(CourseService $courseService, Course $course)
    {
        // Yetki kontrolü
        if (Auth::id() !== $course->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $this->course = $course;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->level_id = $course->level_id;
        $this->category_id = $course->category_id;
        $this->price = $course->price;
        $this->outcomes = $course->outcomes ?? [];
        $this->prerequisites = $course->prerequisites ?? [];
        $this->lessons = $course->lessons->toArray();
    }

    public function save(CourseService $courseService)
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'level_id' => $this->level_id,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'outcomes' => $this->outcomes,
            'prerequisites' => $this->prerequisites,
            'lessons' => $this->lessons
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('courses', 'public');
        }

        $courseService->updateCourse($this->course->id, $data);

        session()->flash('message', 'Kurs başarıyla güncellendi!');
        return redirect()->route('courses.show', $this->course->id);
    }

    // CreateCourse'daki diğer metodlar (addOutcome, removeOutcome vb.) buraya da eklenmeli
    // ...

    public function render()
    {
        return view('livewire.courses.edit-course', [
            'categories' => \App\Models\Category::all(),
            'levels' => \App\Models\Level::all()
        ])->layout('layouts.app');
    }
}
