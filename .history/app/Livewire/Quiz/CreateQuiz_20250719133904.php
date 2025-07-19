<?php

namespace App\Livewire\Quiz;

use Livewire\Component;

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

    public function mount($courseId, QuizService $quizService)
    {
        $this->courseId = $courseId;
        $this->quizService = $quizService;
    }

    public function submit()
    {
        $validated = $this->validate();
        $validated['course_id'] = $this->courseId;

        $this->quizService->createNewQuiz($validated);

        session()->flash('success', 'Quiz başarıyla oluşturuldu.');

        return redirect()->route('courses.quizzes.index', $this->courseId);
    }
    public function render()
    {
        return view('livewire.quiz.create-quiz');
    }
}
