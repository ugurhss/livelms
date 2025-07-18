<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCourses extends Component
{
        use WithFileUploads;

public $courseId = null;
    public $title;
    public $description;
    public $slug;
    public $thumbnail;
    public $category;
    public $level = 'beginner';
    public $price = 0;
    public $original_price = 0;
    public $status = 'draft';
    public $outcomes = [];
    public $prerequisites = [];
    public $newOutcome = '';
    public $newPrerequisite = '';
    public $tempThumbnail;

     protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'slug' => 'nullable|string|max:255|unique:courses,slug',
        'thumbnail' => 'nullable|image|max:2048',
        'category' => 'nullable|string|max:255',
        'level' => 'required|in:beginner,intermediate,advanced',
        'price' => 'required|numeric|min:0',
        'original_price' => 'required|numeric|min:0',
        'status' => 'required|in:draft,published,archived',
        'outcomes' => 'array',
        'prerequisites' => 'array',
    ];



    public function render()
    {
        return view('livewire.courses.create-courses');
    }
}
