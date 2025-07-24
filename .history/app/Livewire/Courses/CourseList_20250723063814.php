<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CourseService;
use App\Models\Category;
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

    // Mevcut seviyeleri tanımla
    public $levels = [
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced'
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'category' => '',
            'level' => '',
            'status' => '',
            'search' => ''
        ]]
    ];

    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function clearFilters()
    {
        $this->reset('filters');
        $this->resetPage();
    }

    public function getHasFiltersProperty()
    {
        return !empty(array_filter($this->filters));
    }

    public function isEnrolled($courseId)
    {
        return app(CourseService::class)->checkEnrollment($courseId, $this->user->id);
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
            'hasFilters' => $this->hasFilters,
            'categories' => Category::all(),
            'levels' => $this->levels // View'a seviyeleri gönder
        ]);
    }
}
