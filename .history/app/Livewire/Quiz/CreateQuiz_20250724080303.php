<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use Livewire\Component;
use App\Services\CrudQuizService;

class CreateQuiz extends Component
{
    public $quiz;
    public $questions = [];
    public $currentQuestion = null;
    public $showQuestionForm = false;
    public $isEditing = false;

    protected $quizService;

    public function __construct()
    {
        $this->quizService = app(CrudQuizService::class);
    }

    public function mount($quizId = null)
    {
        if ($quizId) {
            $this->quiz = $this->quizService->getQuizById($quizId);
            $this->questions = $this->quizService->getQuestions($quizId)->toArray();
            $this->isEditing = true;
        } else {
            $this->quiz = new Quiz();
            $this->quiz->is_published = false;
        }
    }



    public function saveQuiz()
    {
        $validatedData = $this->validate([
            'quiz.title' => 'required|string|max:255',
            'quiz.description' => 'nullable|string',
            'quiz.time_limit' => 'nullable|integer|min:1',
            'quiz.start_date' => 'nullable|date',
            'quiz.end_date' => 'nullable|date|after:quiz.start_date',
            'quiz.passing_score' => 'required|integer|min:1|max:100',
            'quiz.is_published' => 'boolean',
            'quiz.course_id' => 'required|exists:courses,id',
        ]);

        if ($this->isEditing) {
            $this->quizService->updateQuiz($this->quiz->id, $validatedData['quiz']);
            session()->flash('message', 'Quiz updated successfully!');
        } else {
            $quiz = $this->quizService->createQuiz($validatedData['quiz']);
            $this->quiz = $quiz;
            $this->isEditing = true;
            session()->flash('message', 'Quiz created successfully!');
        }
    }

    public function addQuestion()
    {
        $this->currentQuestion = [
            'id' => null,
            'quiz_id' => $this->quiz->id,
            'question_text' => '',
            'question_type' => 'multiple_choice',
            'points' => 1,
            'answers' => [
                ['text' => '', 'is_correct' => false],
                ['text' => '', 'is_correct' => false]
            ]
        ];
        $this->showQuestionForm = true;
    }

    public function editQuestion($index)
    {
        $question = $this->questions[$index];
        $this->currentQuestion = $question;

        if ($question['question_type'] === 'multiple_choice') {
            $fullQuestion = $this->quizService->getQuestionWithAnswers($question['id']);
            $this->currentQuestion['answers'] = $fullQuestion->answers->map(function($answer) {
                return ['text' => $answer->answer_text, 'is_correct' => $answer->is_correct];
            })->toArray();
        } else {
            $this->currentQuestion['answers'] = [];
        }

        $this->showQuestionForm = true;
    }

    public function saveQuestion()
    {
        $validatedData = $this->validate([
            'currentQuestion.question_text' => 'required|string',
            'currentQuestion.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'currentQuestion.points' => 'required|integer|min:1',
            'currentQuestion.answers.*.text' => 'required_if:currentQuestion.question_type,multiple_choice|string',
            'currentQuestion.answers.*.is_correct' => 'required_if:currentQuestion.question_type,multiple_choice|boolean',
        ]);

        $questionData = $this->currentQuestion;

        if (isset($questionData['id'])) {
            $this->quizService->updateQuestion($questionData['id'], $questionData);
            session()->flash('message', 'Question updated successfully!');
        } else {
            $this->quizService->createQuestion($this->quiz->id, $questionData);
            session()->flash('message', 'Question added successfully!');
        }

        $this->questions = $this->quizService->getQuestions($this->quiz->id)->toArray();
        $this->showQuestionForm = false;
    }

    public function deleteQuestion($index)
    {
        $questionId = $this->questions[$index]['id'];
        $this->quizService->deleteQuestion($questionId);
        $this->questions = $this->quizService->getQuestions($this->quiz->id)->toArray();
        session()->flash('message', 'Question deleted successfully!');
    }

    public function addAnswer()
    {
        $this->currentQuestion['answers'][] = ['text' => '', 'is_correct' => false];
    }

    public function removeAnswer($index)
    {
        unset($this->currentQuestion['answers'][$index]);
        $this->currentQuestion['answers'] = array_values($this->currentQuestion['answers']);
    }
    public function render()
    {
        return view('livewire.quiz.create-quiz');
    }
}
