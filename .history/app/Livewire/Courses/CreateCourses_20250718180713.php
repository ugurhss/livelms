<?php

namespace App\Livewire\Courses;

use Livewire\Component;
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

       public function mount($courseId = null)
    {
        if ($courseId) {
            $this->courseId = $courseId;
            $this->loadCourseData();
        }
    }
    public function loadCourseData()
    {
        $course = app(CourseService::class)->getCourseById($this->courseId);

        if ($course) {
            $this->title = $course->title;
            $this->description = $course->description;
            $this->slug = $course->slug;
            $this->category = $course->category;
            $this->level = $course->level;
            $this->price = $course->price;
            $this->original_price = $course->original_price;
            $this->status = $course->status;
            $this->outcomes = $course->outcomes ?? [];
            $this->prerequisites = $course->prerequisites ?? [];
            $this->tempThumbnail = $course->thumbnail;
        }
    }
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

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'category' => $this->category,
            'level' => $this->level,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'status' => $this->status,
            'outcomes' => $this->outcomes,
            'prerequisites' => $this->prerequisites,
            'user_id' => Auth()->id(),
        ];

        $courseService = app(CourseService::class);

        if ($this->courseId) {
            // Güncelleme işlemi
            $course = $courseService->updateCourse($this->courseId, $data, $this->thumbnail);
            session()->flash('message', 'Kurs başarıyla güncellendi.');
        } else {
            // Yeni oluşturma işlemi
            $course = $courseService->createCourse($data, $this->thumbnail);
            session()->flash('message', 'Kurs başarıyla oluşturuldu.');
        }

        return redirect()->route('courses.edit', $course->id);
    }


    public function render()
    {
        return view('livewire.courses.create-courses');
    }
}
