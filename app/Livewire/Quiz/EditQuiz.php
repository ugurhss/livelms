<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;

class EditQuiz extends Component
{
    public $courseId;
    public $quizId;
    public $quiz;

    // Form fields
    public $title;
    public $description;
    public $time_limit;
    public $start_date;
    public $end_date;
    public $passing_score;
    public $is_published;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'time_limit' => 'nullable|integer',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'passing_score' => 'required|integer|min:1|max:100',
        'is_published' => 'required|boolean',
    ];

    public function mount($courseId, $quizId, QuizService $quizService)
    {
        $this->courseId = $courseId;
        $this->quizId = $quizId;
        $this->quiz = $quizService->getQuizDetails($quizId);

        // Initialize form fields
        $this->fill([
            'title' => $this->quiz->title,
            'description' => $this->quiz->description,
            'time_limit' => $this->quiz->time_limit,
            'start_date' => $this->quiz->start_date?->format('Y-m-d\TH:i'),
            'end_date' => $this->quiz->end_date?->format('Y-m-d\TH:i'),
            'passing_score' => $this->quiz->passing_score,
            'is_published' => $this->quiz->is_published,
        ]);
    }

    public function submit(QuizService $quizService)
    {
        $validated = $this->validate();

        $quizService->updateQuizDetails($this->quizId, $validated);

        session()->flash('success', 'Quiz başarıyla güncellendi.');

        return redirect()->route('courses.quizzes.show', [
            'courseId' => $this->courseId,
            'quizId' => $this->quizId
        ]);
    }

    public function render()
    {
        return view('livewire.quiz.edit-quiz');
    }
}
