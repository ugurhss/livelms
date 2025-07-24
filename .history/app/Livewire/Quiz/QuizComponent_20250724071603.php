<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;
use App\Repositories\QuizRepository;

class QuizComponent extends Component
{

 public $courseId;
    public $quizzes = [];
    public $selectedQuiz = null;
    public $questions = [];
    public $showQuizForm = false;
    public $showQuestionForm = false;

    protected $listeners = [
        'quizCreated' => 'refreshQuizzes',
        'quizUpdated' => 'refreshQuizzes',
        'questionCreated' => 'refreshQuestions',
        'questionUpdated' => 'refreshQuestions',
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->loadQuizzes();
    }



    public function loadQuizzes(QuizService $quizService)
    {
        $this->quizzes = $quizService->getQuizzesByCourse($this->courseId);
    }

    public function selectQuiz($quizId, QuizService $quizService)
    {
        $this->selectedQuiz = $quizService->getQuizById($quizId);
        $this->questions = $quizService->getQuizQuestions($quizId);
        $this->showQuizForm = false;
        $this->showQuestionForm = false;
    }

    public function createQuiz()
    {
        $this->selectedQuiz = null;
        $this->questions = [];
        $this->showQuizForm = true;
        $this->showQuestionForm = false;
        $this->emit('resetForm');
    }

    public function editQuiz($quizId)
    {
        $this->emit('editQuiz', $quizId);
        $this->showQuizForm = true;
        $this->showQuestionForm = false;
    }

    public function createQuestion()
    {
        if (!$this->selectedQuiz) return;

        $this->showQuestionForm = true;
        $this->emit('resetQuestionForm');
    }

    public function editQuestion($questionId)
    {
        $this->emit('editQuestion', $questionId);
        $this->showQuestionForm = true;
    }

    public function deleteQuiz($quizId, QuizService $quizService)
    {
        $quizService->deleteQuiz($quizId);
        $this->refreshQuizzes();
        $this->selectedQuiz = null;
        $this->questions = [];
        session()->flash('message', 'Quiz deleted successfully.');
    }

    public function deleteQuestion($questionId, QuizService $quizService)
    {
        $quizService->deleteQuizQuestion($questionId);
        $this->refreshQuestions();
        session()->flash('message', 'Question deleted successfully.');
    }

    public function togglePublish($quizId, QuizService $quizService)
    {
        $quiz = $quizService->getQuizById($quizId);
        if ($quiz->is_published) {
            $quizService->unpublishQuiz($quizId);
        } else {
            $quizService->publishQuiz($quizId);
        }
        $this->refreshQuizzes();
    }

    public function refreshQuizzes()
    {
        $this->loadQuizzes(app(QuizService::class));
        $this->showQuizForm = false;
    }

    public function refreshQuestions()
    {
        if ($this->selectedQuiz) {
            $this->questions = app(QuizService::class)->getQuizQuestions($this->selectedQuiz->id);
        }
        $this->showQuestionForm = false;
    }    public function render()
    {
        return view('livewire.quiz.quiz-component');
    }
}
