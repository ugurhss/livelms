<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;

class CreateQuiz extends Component
{
 public $course_id;
    public $title;
    public $description;
    public $passing_score = 70;
    public $is_published = true;
    public $start_date;
    public $end_date;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'passing_score' => 'required|numeric|min:0|max:100',
        'is_published' => 'boolean',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
    ];

    public function mount($course)
    {
        $this->course_id = $course;
    }



    public function save(QuizService $quizService)
    {
        $this->validate();

        $quizData = [
            'course_id' => $this->course_id,
            'title' => $this->title,
            'description' => $this->description,
            'passing_score' => $this->passing_score,
            'is_published' => $this->is_published,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        $quiz = $quizService->createNewQuiz($quizData);

        return redirect()->route('quizzes.questions', $quiz->id)
            ->with('success', 'Quiz created successfully! Now add questions.');
    }
    public function render()
    {
          $course = Course::findOrFail($this->course_id);
        return view('livewire.quiz.create-quiz',compact('course'));
    }
}
