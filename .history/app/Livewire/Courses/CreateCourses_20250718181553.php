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
    public $slug;
    public $level = 'beginner';
    public $price = 0;
    public $original_price = 0;
    public $status = 'draft';
    public $outcomes = [];
    public $prerequisites = [];
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

    public function mount()
    {
        // Eğer düzenleme modunda ise verileri yükle
        $this->outcomes = [];
        $this->prerequisites = [];
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
        $this->outcomes = array_values($this->outcomes); // Dizi indekslerini yeniden düzenle
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

        session()->flash('success', 'Kurs başarıyla oluşturuldu!');
        return redirect()->route('courses.show', $course);
    }


    public function render()
    {
        return view('livewire.courses.create-courses');
    }
}
