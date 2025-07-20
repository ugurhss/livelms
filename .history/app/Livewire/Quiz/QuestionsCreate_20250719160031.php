<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;

class QuestionsCreate extends Component
{
    public $courseId;
    public $quizId;
    public $quiz;

    // Form fields
    public $question_text = '';
    public $question_type = '';
    public $points = 1;
    public $answers = [];

    protected $rules = [
        'question_text' => 'required|string',
        'question_type' => 'required|in:multiple_choice,true_false,short_answer',
        'points' => 'required|integer|min:1',
        'answers' => 'required|array|min:1',
        'answers.*.answer_text' => 'required|string',
        'answers.*.is_correct' => 'required|boolean',
    ];

    public function mount($courseId, $quizId, QuizService $quizService)
    {
        $this->courseId = $courseId;
        $this->quizId = $quizId;
        $this->quiz = $quizService->getQuizDetails($quizId);

        // Initialize default answers for multiple choice
        $this->initializeDefaultAnswers();
    }

    public function initializeDefaultAnswers()
    {
        $this->answers = [
            ['answer_text' => '', 'is_correct' => true],
            ['answer_text' => '', 'is_correct' => false],
            ['answer_text' => '', 'is_correct' => false],
            ['answer_text' => '', 'is_correct' => false],
        ];
    }

    public function updatedQuestionType($value)
    {
        $this->answers = [];

        switch($value) {
            case 'multiple_choice':
                $this->initializeDefaultAnswers();
                break;
            case 'true_false':
                $this->answers = [
                    ['answer_text' => 'Doğru', 'is_correct' => true],
                    ['answer_text' => 'Yanlış', 'is_correct' => false],
                ];
                break;
            case 'short_answer':
                $this->answers = [
                    ['answer_text' => '', 'is_correct' => true],
                ];
                break;
        }
    }

    public function addAnswer()
    {
        $this->answers[] = ['answer_text' => '', 'is_correct' => false];
    }

    public function removeAnswer($index)
    {
        unset($this->answers[$index]);
        $this->answers = array_values($this->answers);
    }

    public function submit(QuizService $quizService)
    {
        $this->validate();

        $questionData = [
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'points' => $this->points,
            'answers' => $this->answers,
        ];

        $quizService->addQuestionToQuiz($this->quizId, $questionData);

        session()->flash('success', 'Soru başarıyla eklendi.');

        return redirect()->route('courses.quizzes.show', [
            'courseId' => $this->courseId,
            'quizId' => $this->quizId
        ]);
    }

    public function render()
    {
        return view('livewire.quiz.questions-create');
    }
}
