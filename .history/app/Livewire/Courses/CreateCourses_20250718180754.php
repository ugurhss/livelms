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





    public function render()
    {
        return view('livewire.courses.create-courses');
    }
}
