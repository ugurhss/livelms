<?php

namespace App\Livewire\Courses;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use App\Services\CourseService;

class CreateCourses extends Component
{
      #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('required|string')]
    public string $description = '';

    #[Rule('required|string')]
    public string $level = 'beginner';

    #[Rule('required|numeric|min:0')]
    public float $price = 0;

    #[Rule('nullable|numeric|min:0')]
    public ?float $original_price = null;

    #[Rule('required|string')]
    public string $status = 'draft';

#[Rule('required|array')]
public array $outcomes = [];

#[Rule('required|string|max:255')]
public string $newOutcome = '';    public array $prerequisites = [];
    public string $newPrerequisite = '';

    public function addOutcome()
    {
        $this->validate([
            'newOutcome' => 'required|string|max:255'
        ]);

        $this->outcomes[] = $this->newOutcome;
        $this->newOutcome = '';
    }

    public function removeOutcome($index)
    {
        unset($this->outcomes[$index]);
        $this->outcomes = array_values($this->outcomes); // Reindex array
    }

    public function addPrerequisite()
    {
        $this->validate([
            'newPrerequisite' => 'required|string|max:255'
        ]);

        $this->prerequisites[] = $this->newPrerequisite;
        $this->newPrerequisite = '';
    }

    public function removePrerequisite($index)
    {
        unset($this->prerequisites[$index]);
        $this->prerequisites = array_values($this->prerequisites);
    }

    public function save(CourseService $courseService)
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'slug' => Str::slug($this->title),
            'level' => $this->level,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'status' => $this->status,
            'outcomes' => $this->outcomes,
            'prerequisites' => $this->prerequisites,
            'user_id' => auth()->id(),
        ];

        $course = $courseService->createCourse($data);

        session()->flash('message', 'Kurs başarıyla oluşturuldu!');

        return $this->redirect(route('courses.show', $course), navigate: true);
    }

    public function mount()
{
    $this->outcomes = []; // Başlangıçta boş array
}
    public function render()
    {
        return view('livewire.courses.create-courses');
    }
}
