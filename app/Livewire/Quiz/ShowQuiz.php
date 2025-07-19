<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;

class ShowQuiz extends Component
{
    public $courseId;
    public $quizId;
    public $quiz;
    public $availability;

    protected $quizService;

    public function mount($courseId, $quizId, QuizService $quizService)
    {
        $this->courseId = $courseId;
        $this->quizId = $quizId;
        $this->quizService = $quizService;

        $this->loadQuiz();
    }

    public function loadQuiz()
    {
        $this->quiz = $this->quizService->getQuizDetails($this->quizId);
        $this->availability = $this->quizService->checkQuizAccess($this->quizId);

        if (!$this->availability['available']) {
            session()->flash('error', $this->availability['message']);
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('livewire.quiz.show-quiz', [
            'quiz' => $this->quizId,
            'courseId' => $this->courseId
        ]);
    }
}
