<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Take extends Component
{
    public $courseId;
    public $quizId;
    public $quiz;
    public $answers = [];
    public $timeLeft;
    public $timerVisible = false;

    protected $rules = [
        'answers' => 'required|array',

    ];

    public function mount($courseId, $quizId, QuizService $quizService)
    {
        $this->courseId = $courseId;
        $this->quizId = $quizId;

        // Get quiz details and check availability
        $this->quiz = $quizService->getQuizDetails($quizId);
        $availability = $quizService->checkQuizAccess($quizId);

        if (!$availability['available']) {
            session()->flash('error', $availability['message']);
            return redirect()->back(); // redirectBack yerine redirect()->back() kullanıyoruz
        }

        // Initialize timer if time limit exists
        if ($this->quiz->time_limit) {
            $this->timeLeft = $this->quiz->time_limit * 60;
            $this->timerVisible = true;
            $this->startTimer();
        }
    }

    public function startTimer()
    {
        $this->dispatch('start-timer', timeLeft: $this->timeLeft);
    }

    public function submitQuiza(QuizService $quizService)
    {
       $this->validate();

    try {
        $result = $quizService->submitQuiz(
            $this->quizId,
            Auth()->id(),
            $this->answers
        );

        session()->flash('success', 'Quiz tamamlandı!');
        return redirect()->route('courses.quizzes.result', [
            'courseId' => $this->courseId,
            'quizId' => $this->quizId,
            'resultId' => $result->id
        ]);
    } catch (\Exception $e) {
        session()->flash('error', $e->getMessage());
        return redirect()->back();
    }
    }

    public function render()
    {
        return view('livewire.quiz.take');
    }
}
