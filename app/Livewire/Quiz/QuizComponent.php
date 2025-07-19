<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;
use App\Repositories\QuizRepository;

class QuizComponent extends Component
{


       public $quizzes;
    public $courseId;

    protected $quizRepository;

    public function mount($courseId, QuizRepository $quizRepository)
    {
        $this->courseId = $courseId;
        $this->quizRepository = $quizRepository;
        $this->quizzes = $this->quizRepository->getQuizByCourse($courseId);
    }
    public function render()
    {
        return view('livewire.quiz.quiz-component');
    }
}
