<?php

namespace App\Livewire\CreateCourses;

use App\Models\Level;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Services\CourseService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CreateCourses extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $totalSteps = 3;

    // Form alanları
    public $title;
    public $description;
    public $image;
    public $level_id;
    public $category_id;
    public $price;
    public $outcomes = [];
    public $prerequisites = [];
    public $newOutcome = '';
    public $newPrerequisite = '';

    // Dersler bölümü
    public $lessons = [];
    public $currentLesson = [
        'title' => '',
        'description' => '',
        'video_url' => '',
        'duration' => 0,
        'is_free' => false
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|min:50',
        'image' => 'required|image|max:2048',
        'level_id' => 'required|exists:levels,id',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'outcomes' => 'array',
        'outcomes.*' => 'string|max:255',
        'prerequisites' => 'array',
        'prerequisites.*' => 'string|max:255',
        'lessons' => 'array|min:1',
        'lessons.*.title' => 'required|string|max:255',
        'lessons.*.description' => 'required|string|min:20',
        'lessons.*.video_url' => 'required|url',
        'lessons.*.duration' => 'required|integer|min:1',
        'lessons.*.is_free' => 'boolean'
    ];

    protected $messages = [
        'lessons.min' => 'En az bir ders eklemelisiniz.',
        'lessons.*.title.required' => 'Ders başlığı gereklidir.',
        'lessons.*.description.required' => 'Ders açıklaması gereklidir.',
        'lessons.*.video_url.required' => 'Video URL gereklidir.',
        'lessons.*.video_url.url' => 'Geçerli bir URL giriniz.',
        'lessons.*.duration.required' => 'Ders süresi gereklidir.',
        'lessons.*.duration.min' => 'Ders süresi en az 1 dakika olmalıdır.'
    ];

    public function __construct()
    {
        $this->lessons = [];
    }

    public function render()
    {
        return view('livewire.courses.create-courses', [
            'categories' => Category::all(),
            'levels' => Level::all()
        ])->layout('layouts.instructor');
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:50',
                'image' => 'required|image|max:2048',
                'level_id' => 'required|exists:levels,id',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'outcomes' => 'array',
                'outcomes.*' => 'string|max:255',
                'prerequisites' => 'array',
                'prerequisites.*' => 'string|max:255'
            ]);
        }

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

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
        $this->outcomes = array_values($this->outcomes);
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

    public function addLesson()
    {
        $this->validate([
            'currentLesson.title' => 'required|string|max:255',
            'currentLesson.description' => 'required|string|min:20',
            'currentLesson.video_url' => 'required|url',
            'currentLesson.duration' => 'required|integer|min:1'
        ]);

        $this->lessons[] = $this->currentLesson;
        $this->resetCurrentLesson();
    }

    public function removeLesson($index)
    {
        unset($this->lessons[$index]);
        $this->lessons = array_values($this->lessons);
    }

    public function resetCurrentLesson()
    {
        $this->currentLesson = [
            'title' => '',
            'description' => '',
            'video_url' => '',
            'duration' => 0,
            'is_free' => false
        ];
    }

    public function submit(CourseService $courseService)
    {
        $this->validate();

        try {
            // Resmi yükle
            $imagePath = $this->image->store('courses', 'public');

            // Kurs verilerini hazırla
            $courseData = [
                'title' => $this->title,
                'description' => $this->description,
                'image' => $imagePath,
                'level_id' => $this->level_id,
                'category_id' => $this->category_id,
                'price' => $this->price,
                'outcomes' => $this->outcomes,
                'prerequisites' => $this->prerequisites,
                'lessons' => $this->lessons
            ];

            // Kursu oluştur
            $course = $courseService->createCourse($courseData);

            // Başarı mesajı göster ve yönlendir
            session()->flash('success', 'Kurs başarıyla oluşturuldu!');
            return redirect()->route('instructor.courses.edit', $course->id);

        } catch (\Exception $e) {
            $this->addError('create_error', 'Kurs oluşturulurken bir hata oluştu: ' . $e->getMessage());
            Log::error('Course creation failed: ' . $e->getMessage());
        }
    }
}
