<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuestionsCreate extends Component
{
   public $quizId;
    public $questionId;
    public $questionText;
    public $questionType = 'multiple_choice';
    public $points = 1;
    public $answers = [];
    public $editMode = false;

    protected $rules = [
        'questionText' => 'required|string',
        'questionType' => 'required|in:multiple_choice,true_false,short_answer',
        'points' => 'required|integer|min:1',
        'answers.*.text' => 'required_if:questionType,multiple_choice,true_false|string',
        'answers.*.is_correct' => 'required_if:questionType,multiple_choice,true_false|boolean',
    ];

    protected $listeners = ['editQuestion', 'resetQuestionForm'];

    public function mount($quizId)
    {
        $this->quizId = $quizId;
        $this->initializeAnswers();
    }



    public function initializeAnswers()
    {
        if ($this->questionType === 'true_false') {
            $this->answers = [
                ['text' => 'True', 'is_correct' => false],
                ['text' => 'False', 'is_correct' => false],
            ];
        } elseif ($this->questionType === 'multiple_choice') {
            $this->answers = [
                ['text' => '', 'is_correct' => false],
                ['text' => '', 'is_correct' => false],
                ['text' => '', 'is_correct' => false],
                ['text' => '', 'is_correct' => false],
            ];
        } else {
            $this->answers = [];
        }
    }

    public function updatedQuestionType()
    {
        $this->initializeAnswers();
    }

    public function addAnswer()
    {
        $this->answers[] = ['text' => '', 'is_correct' => false];
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
            'question_text' => $this->questionText,
            'question_type' => $this->questionType,
            'points' => $this->points,
        ];

        if ($this->editMode) {
            $question = $quizService->updateQuizQuestion($this->questionId, $questionData);

            // Delete existing answers if not short answer
            if ($this->questionType !== 'short_answer') {
                $question->answers()->delete();

                // Add new answers
                foreach ($this->answers as $answer) {
                    $question->answers()->create([
                        'answer_text' => $answer['text'],
                        'is_correct' => $answer['is_correct'],
                    ]);
                }
            }

            $this->emit('questionUpdated', $question);
            session()->flash('message', 'Question updated successfully.');
        } else {
            $question = $quizService->addQuestionToQuiz($this->quizId, $questionData);

            // Add answers if not short answer
            if ($this->questionType !== 'short_answer') {
                foreach ($this->answers as $answer) {
                    $question->answers()->create([
                        'answer_text' => $answer['text'],
                        'is_correct' => $answer['is_correct'],
                    ]);
                }
            }

            $this->emit('questionCreated', $question);
            session()->flash('message', 'Question created successfully.');
        }

        $this->resetQuestionForm();
    }

    public function editQuestion($questionId, QuizService $quizService)
    {
        $question = $quizService->getQuestionWithAnswers($questionId);
        $this->questionId = $question->id;
        $this->quizId = $question->quiz_id;
        $this->questionText = $question->question_text;
        $this->questionType = $question->question_type;
        $this->points = $question->points;

        if ($question->question_type !== 'short_answer') {
            $this->answers = $question->answers->map(function ($answer) {
                return [
                    'text' => $answer->answer_text,
                    'is_correct' => $answer->is_correct,
                ];
            })->toArray();
        } else {
            $this->answers = [];
        }

        $this->editMode = true;
    }

    public function resetQuestionForm()
    {
        $this->reset([
            'questionId',
            'questionText',
            'questionType',
            'points',
            'answers',
            'editMode'
        ]);
        $this->initializeAnswers();
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.quiz.questions-create');
    }
}
