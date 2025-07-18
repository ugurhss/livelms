<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Services\CourseService;
use Illuminate\Support\Facades\Auth;

class CreateCourses extends Component
{
    public $title;
    public $description;
    public $level = 'beginner';
    public $price;
    public $original_price;
    public $status = 'draft'; // 'draft' olarak düzeltildi (önceki 'draft' yazım hatası)
    public $outcomes = []; // Başlangıç değeri olarak boş dizi
    public $prerequisites = []; // Başlangıç değeri olarak boş dizi
    public $newOutcome = '';
    public $newPrerequisite = '';

    protected $rules = [
        'title' => 'required|min:6|max:255',
        'description' => 'required|min:20',
        'level' => 'required|in:beginner,intermediate,advanced',
        'price' => 'required|numeric|min:0',
        'original_price' => 'nullable|numeric|min:0',
        'status' => 'required|in:draft,published,archived',
    ];


  public function addOutcome()
    {
        if (!empty($this->newOutcome)) {
            $this->outcomes[] = $this->newOutcome;
            $this->newOutcome = '';
        }
    }

    public function removeOutcome($index)
    {
        unset($this->outcomes[$index]);
        $this->outcomes = array_values($this->outcomes);
    }

    public function addPrerequisite()
    {
        if (!empty($this->newPrerequisite)) {
            $this->prerequisites[] = $this->newPrerequisite;
            $this->newPrerequisite = '';
        }
    }

    public function removePrerequisite($index)
    {
        unset($this->prerequisites[$index]);
        $this->prerequisites = array_values($this->prerequisites);
    }

    public function submit(CourseService $courseService)
    {
        $this->validate();

        $courseData = [
            'title' => $this->title,
            'description' => $this->description,
            'slug' => Str::slug($this->title),
            'level' => $this->level,
            'price' => $this->price,
            'original_price' => $this->original_price ?? $this->price,
            'status' => $this->status,
            'outcomes' => $this->outcomes,
            'prerequisites' => $this->prerequisites,
            'user_id' => auth()->id(),
        ];

        $course = $courseService->createCourse($courseData);

        session()->flash('message', 'Kurs başarıyla oluşturuldu!');
        return redirect()->route('courses.show', $course);
    }


    public function render()
    {
        return view('livewire.courses.create-courses');
    }
}
