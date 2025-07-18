<?php

namespace App\Livewire\Course;

use Livewire\Component;

class Create extends Component
{
 use WithFileUploads;

    // Form fields
    public $title = '';
    public $description = '';
    public $thumbnail = null;
    public $category = '';
    public $level = 'beginner';
    public $price = 0;
    public $original_price = 0;
    public $status = 'draft';

    // Arrays for outcomes and prerequisites
    public $outcomes = [];
    public $prerequisites = [];
    public $currentOutcome = '';
    public $currentPrerequisite = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'thumbnail' => 'nullable|image|max:2048', // 2MB Max
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
        $this->outcomes = array_values($this->outcomes); // Reindex array
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
        $this->prerequisites = array_values($this->prerequisites); // Reindex array
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
        return view('livewire.course.create');
    }
}
