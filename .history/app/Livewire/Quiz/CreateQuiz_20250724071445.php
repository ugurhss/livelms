<?php

namespace App\Http\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CreateQuiz extends Component
{
    public $courseId;
    public $quizId;
    public $title;
    public $description;
    public $timeLimit;
    public $startDate;
    public $endDate;
    public $passingScore = 70;
    public $isPublished = false;
    public $editMode = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'timeLimit' => 'nullable|integer|min:1',
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
        'passingScore' => 'required|integer|min:1|max:100',
        'isPublished' => 'boolean',
    ];

    protected $listeners = ['editQuiz', 'resetForm'];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
    }



    public function submit(QuizService $quizService)
    {
        $this->validate();

        $data = [
            'course_id' => $this->courseId,
            'title' => $this->title,
            'description' => $this->description,
            'time_limit' => $this->timeLimit,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'passing_score' => $this->passingScore,
            'is_published' => $this->isPublished,
        ];

        if ($this->editMode) {
            $quiz = $quizService->updateQuiz($this->quizId, $data);
            $this->emit('quizUpdated', $quiz);
            session()->flash('message', 'Quiz updated successfully.');
        } else {
            $quiz = $quizService->createQuiz($data);
            $this->emit('quizCreated', $quiz);
            session()->flash('message', 'Quiz created successfully.');
        }

        $this->resetForm();
    }

    public function editQuiz($quizId, QuizService $quizService)
    {
        $quiz = $quizService->getQuizById($quizId);
        $this->quizId = $quiz->id;
        $this->title = $quiz->title;
        $this->description = $quiz->description;
        $this->timeLimit = $quiz->time_limit;
        $this->startDate = $quiz->start_date?->format('Y-m-d\TH:i');
        $this->endDate = $quiz->end_date?->format('Y-m-d\TH:i');
        $this->passingScore = $quiz->passing_score;
        $this->isPublished = $quiz->is_published;
        $this->editMode = true;
    }

    public function resetForm()
    {
        $this->reset([
            'quizId',
            'title',
            'description',
            'timeLimit',
            'startDate',
            'endDate',
            'passingScore',
            'isPublished',
            'editMode'
        ]);
        $this->resetErrorBag();
    }

    public function togglePublish()
    {
        $this->isPublished = !$this->isPublished;
    }
    public function render()
    {
        return view('livewire.quiz.create-quiz');
    }
}
