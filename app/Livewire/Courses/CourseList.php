<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CourseService;
use Illuminate\Support\Facades\Auth;

class CourseList extends Component
{
    use WithPagination;

    public $filters = [
        'category' => '',
        'level' => '',
        'status' => '',
        'search' => ''
    ];

    protected $queryString = [
        'filters' => ['except' => ['category' => '', 'level' => '', 'status' => '', 'search' => '']]
    ];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render(CourseService $courseService)
    {
        $courses = $courseService->getPublishedCourses([
            'category' => $this->filters['category'],
            'level' => $this->filters['level'],
            'status' => $this->filters['status'],
            'search' => $this->filters['search'],
            'per_page' => 9
        ]);

        return view('livewire.courses.course-list', [
            'courses' => $courses,
            'user' => $this->user,
            'hasFilters' => $this->hasFilters()
        ]);
    }

    public function clearFilters()
    {
        $this->reset('filters');
    }

    protected function hasFilters()
    {
        return !empty(array_filter($this->filters));
    }

    public function isEnrolled($courseId)
    {
        // Bu metod CourseService'e taşınabilir
        return $this->user->enrolledCourses()->where('course_id', $courseId)->exists();
    }
}
