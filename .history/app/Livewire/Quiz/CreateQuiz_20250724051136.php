<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;

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
        'time_limit' => 'nullable|integer|min:1',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'passing_score' => 'required|integer|min:0|max:100',
        'is_published' => 'required|boolean',
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
    }

    public function render()
    {
        return view('livewire.quiz.create-quiz');
    }

    public function save()
    {
        $this->validate();

        try {
            $quizService = app(QuizService::class);
            $quizData = [
                'course_id' => $this->courseId,
                'title' => $this->title,
                'description' => $this->description,
                'time_limit' => $this->time_limit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'passing_score' => $this->passing_score,
                'is_published' => $this->is_published,
            ];

            $quizService->createNewQuiz($quizData);

            return Redirect::route('courses.quizzes.index', $this->courseId)
                ->with('success', __('Quiz başarıyla oluşturuldu.'));

        } catch (\Exception $e) {
            $this->addError('save_error', __('Quiz oluşturulurken hata oluştu: ') . $e->getMessage());
        }
    }
}
