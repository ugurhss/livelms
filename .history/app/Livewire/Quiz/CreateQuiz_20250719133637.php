<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class CreateQuiz extends Component
{
    public $courseId;
    public $title;
    public $description;
    public $time_limit;
    public $start_date;
    public $end_date;
    public $passing_score = 70;
    public $is_published = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'time_limit' => 'nullable|integer',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'passing_score' => 'required|integer|min:0|max:100',
        'is_published' => 'required|boolean',
    ];

    private $quizService;

    public function __construct()
    {
        parent::__construct();
        $this->quizService = app(QuizService::class);
    }

    public function mount($courseId)
    {
        $this->courseId = $courseId;
    }

    public function submit()
    {
        $this->validate();

        try {
            Log::info('Creating quiz with data', [
                'course_id' => $this->courseId,
                'title' => $this->title,
                'description' => $this->description,
                'time_limit' => $this->time_limit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'passing_score' => $this->passing_score,
                'is_published' => $this->is_published,
            ]);

            $this->quizService->createNewQuiz([
                'course_id' => $this->courseId,
                'title' => $this->title,
                'description' => $this->description,
                'time_limit' => $this->time_limit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'passing_score' => $this->passing_score,
                'is_published' => $this->is_published,
            ]);

            session()->flash('success', 'Quiz başarıyla oluşturuldu.');
            return redirect()->route('courses.quizzes.index', $this->courseId);

        } catch (\Exception $e) {
            Log::error('Quiz creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Quiz oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.quiz.create-quiz');
    }
}
