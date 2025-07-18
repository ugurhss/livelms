<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use App\Services\CourseService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Create extends Component
{

     public $title;
    public $description;
    public $level;
    public $price;
    public $original_price;
    public $status = 'published'; // veya published
    public $outcomes = [];
    public $prerequisites = [];
    public $lessons = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'level' => 'required|string',
        'price' => 'required|numeric',
        'original_price' => 'nullable|numeric',
        'status' => 'required|string',
        'outcomes' => 'nullable|array',
        'prerequisites' => 'nullable|array',
        'lessons' => 'nullable|array',
    ];

  public function save()
{
    $this->validate();

    Log::info('Livewire validasyon başarılı', [
        'title' => $this->title,
           'description' => $this->description,
        'level' => $this->level,
        'price' => $this->price,
        'original_price' => $this->original_price,
        'outcomes' => $this->outcomes,
        'prerequisites' => $this->prerequisites,

        'status' => $this->status,
        'lessons' => $this->lessons,
    ]);

    $courseService = App::make(CourseService::class);

    try {
        $course = $courseService->createCourse([
            'title' => $this->title,
            'description' => $this->description,
            'level' => $this->level,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'status' => $this->status,
            'outcomes' => $this->outcomes,
            'prerequisites' => $this->prerequisites,
            'lessons' => $this->lessons,
        ]);

        Log::info('Kurs başarıyla oluşturuldu', ['course_id' => $course->id]);

        session()->flash('success', 'Kurs başarıyla oluşturuldu!');
        $this->reset();
    } catch (\Exception $e) {
        Log::error('Kurs oluşturulurken hata oluştu', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        session()->flash('error', 'Kurs oluşturulurken bir hata oluştu.');
    }}
    public function render()
    {
        return view('livewire.courses.create');
    }
}
