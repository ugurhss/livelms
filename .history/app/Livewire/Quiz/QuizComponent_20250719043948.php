<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;

class QuizComponent extends Component
{


      public $courseId;
    public $quizzes;

    protected QuizService $quizService;

    // Mount ile courseId al ve quizleri yükle
    public function mount($courseId, QuizService $quizService)
    {
        $this->courseId = $courseId;
        $this->quizService = $quizService;
        // $this->loadQuizzes();
    }
    public function render()
    {
        return view('livewire.quiz.quiz-component');
    }
}
