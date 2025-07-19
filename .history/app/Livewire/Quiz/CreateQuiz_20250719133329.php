<?php

namespace App\Livewire\Quiz;

use App\Repositories\QuizRepository;
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

    protected $quizService;

    public function mount($courseId, QuizRepository $quizService)
    {
        Log::info('CreateQuiz mounted', ['courseId' => $courseId]);
        $this->courseId = $courseId;
        $this->quizService = $quizService;
    }

    public function submit()
    {
            Log::emergency('Submit method called!', ['data' => $this->all()]);

        Log::debug('Quiz submission started', [
            'courseId' => $this->courseId,
            'title' => $this->title,
            'is_published' => $this->is_published
        ]);

        $this->validate();

        try {
            $quizData = [
                'course_id' => $this->courseId,
                'title' => $this->title,
                'description' => $this->description,
                'time_limit' => $this->time_limit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'passing_score' => $this->passing_score,
                'is_published' => $this->is_published,
            ];

            Log::info('Creating new quiz', $quizData);

            $this->quizService->createNewQuiz($quizData);

            Log::notice('Quiz created successfully', ['courseId' => $this->courseId]);
            session()->flash('success', 'Quiz başarıyla oluşturuldu.');

            return Redirect::route('courses.quizzes.index', $this->courseId);

        } catch (\Exception $e) {
            Log::error('Quiz creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'courseId' => $this->courseId
            ]);

            session()->flash('error', 'Quiz oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function render()
    {
        Log::debug('Rendering CreateQuiz view', ['courseId' => $this->courseId]);
        return view('livewire.quiz.create-quiz')
          ;
    }
}
