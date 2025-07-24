<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Services\QuizService;

class QuestionsCreate extends Component
{
   public $quiz;
    public $questions = [];
    public $question_text;
    public $question_type = 'multiple_choice';
    public $points = 1;
    public $answers = [
        ['answer_text' => '', 'is_correct' => false],
        ['answer_text' => '', 'is_correct' => false],
    ];

    public function mount($quiz)
    {
        $this->quiz = Quiz::with('questions.answers')->findOrFail($quiz);
        $this->questions = $this->quiz->questions;
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

    public function addQuestion(QuizService $quizService)
    {
        $validatedData = $this->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|numeric|min:1',
            'answers' => 'required|array|min:1',
            'answers.*.answer_text' => 'required|string',
        ]);

        // For true/false and multiple choice, ensure exactly one correct answer
        if (in_array($this->question_type, ['multiple_choice', 'true_false'])) {
            $correctAnswers = array_filter($this->answers, fn($a) => $a['is_correct']);
            if (count($correctAnswers) !== 1) {
                $this->addError('answers', 'Please select exactly one correct answer.');
                return;
            }
        }

        $questionData = [
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'points' => $this->points,
            'answers' => $this->answers,
        ];

        $question = $quizService->addQuestionToQuiz($this->quiz->id, $questionData);

        $this->reset(['question_text', 'points', 'answers']);
        $this->answers = [
            ['answer_text' => '', 'is_correct' => false],
            ['answer_text' => '', 'is_correct' => false],
        ];

        $this->questions->push($question);
        $this->dispatchBrowserEvent('question-added');
    }

    public function deleteQuestion($questionId, QuizService $quizService)
    {
        $quizService->deleteQuestion($questionId);
        $this->questions = $this->questions->reject(fn($q) => $q->id == $questionId);
    }

    public function render()
    {
        return view('livewire.quiz.questions-create');
    }
}
