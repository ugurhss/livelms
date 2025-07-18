<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

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




    public function render()
    {
        return view('livewire.courses.create-courses');
    }
}
