<?php

namespace App\Livewire\Courses;

use Livewire\Component;

class Create extends Component
{

     use WithFileUploads;

    public $title;
    public $description;
    public $thumbnail;
    public $category;
    public $level = 'beginner';
    public $price;
    public $original_price;
    public $status = 'draft';
    public $outcomes = [];
    public $prerequisites = [];
    public $currentOutcome = '';
    public $currentPrerequisite = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'thumbnail' => 'nullable|image|max:2048',
        'category' => 'required|string',
        'level' => 'required|in:beginner,intermediate,advanced',
        'price' => 'required|numeric|min:0',
        'original_price' => 'required|numeric|min:0',
        'status' => 'required|in:draft,published,archived',
        'outcomes' => 'array',
        'prerequisites' => 'array',
    ];

    public function addOutcome()
    {
        $this->validate(['currentOutcome' => 'required|string|max:255']);
        $this->outcomes[] = $this->currentOutcome;
        $this->currentOutcome = '';
    }

    public function removeOutcome($index)
    {
        unset($this->outcomes[$index]);
        $this->outcomes = array_values($this->outcomes);
    }

    public function addPrerequisite()
    {
        $this->validate(['currentPrerequisite' => 'required|string|max:255']);
        $this->prerequisites[] = $this->currentPrerequisite;
        $this->currentPrerequisite = '';
    }

    public function removePrerequisite($index)
    {
        unset($this->prerequisites[$index]);
        $this->prerequisites = array_values($this->prerequisites);
    }

    public function save(CourseService $courseService)
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'category' => $this->category,
                'level' => $this->level,
                'price' => $this->price,
                'original_price' => $this->original_price,
                'status' => $this->status,
                'outcomes' => $this->outcomes,
                'prerequisites' => $this->prerequisites,
            ];

            if ($this->thumbnail) {
                $data['thumbnail'] = $this->thumbnail->store('thumbnails', 'public');
            }

            $course = $courseService->createCourse($data);

            session()->flash('success', 'Course created successfully!');
            return redirect()->route('courses.edit', $course->id);
        } catch (\Exception $e) {
            Log::error('Course creation failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to create course: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.courses.create');
    }
}
