<?php

namespace App\Livewire\Quiz;

use Livewire\Component;

class CreateQuiz extends Component
{ public $quiz;
    public $quizId;
    public $course_id;
    public $title;
    public $description;
    public $time_limit;
    public $start_date;
    public $end_date;
    public $passing_score = 70;
    public $is_published = false;

    public $questions = [];
    public $newQuestion = [
        'question_text' => '',
        'question_type' => 'multiple_choice',
        'points' => 1,
        'answers' => [
            ['answer_text' => '', 'is_correct' => false],
            ['answer_text' => '', 'is_correct' => false],
            ['answer_text' => '', 'is_correct' => false],
            ['answer_text' => '', 'is_correct' => false],
        ]
    ];

    protected $rules = [
        'course_id' => 'required|exists:courses,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'time_limit' => 'nullable|integer|min:1',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
        'passing_score' => 'required|integer|min:1|max:100',
        'is_published' => 'boolean',
        'newQuestion.question_text' => 'required|string|min:3',
        'newQuestion.question_type' => 'required|in:multiple_choice,true_false,short_answer',
        'newQuestion.points' => 'required|integer|min:1',
        'newQuestion.answers.*.answer_text' => 'required_if:newQuestion.question_type,multiple_choice,true_false|string',
    ];

    protected $messages = [
        'newQuestion.answers.*.answer_text.required_if' => 'The answer field is required.',
    ];

    public function mount($quizId = null)
    {
        $this->quizId = $quizId;

        if ($this->quizId) {
            $quizService = app(QuizService::class);
            $this->quiz = $quizService->getQuiz($this->quizId);
            $this->course_id = $this->quiz->course_id;
            $this->title = $this->quiz->title;
            $this->description = $this->quiz->description;
            $this->time_limit = $this->quiz->time_limit;
            $this->start_date = $this->quiz->start_date?->format('Y-m-d\TH:i');
            $this->end_date = $this->quiz->end_date?->format('Y-m-d\TH:i');
            $this->passing_score = $this->quiz->passing_score;
            $this->is_published = $this->quiz->is_published;

            $this->questions = $quizService->getQuizQuestions($this->quizId)->toArray();
        }
    }

    public function addAnswer()
    {
        $this->newQuestion['answers'][] = ['answer_text' => '', 'is_correct' => false];
    }

    public function removeAnswer($index)
    {
        unset($this->newQuestion['answers'][$index]);
        $this->newQuestion['answers'] = array_values($this->newQuestion['answers']);
    }

    public function addQuestion()
    {
        $this->validate([
            'newQuestion.question_text' => 'required|string|min:3',
            'newQuestion.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'newQuestion.points' => 'required|integer|min:1',
            'newQuestion.answers.*.answer_text' => 'required_if:newQuestion.question_type,multiple_choice,true_false|string',
        ]);

        $quizService = app(QuizService::class);

        if ($this->quizId) {
            $questionData = [
                'quiz_id' => $this->quizId,
                'question_text' => $this->newQuestion['question_text'],
                'question_type' => $this->newQuestion['question_type'],
                'points' => $this->newQuestion['points'],
            ];

            $question = $quizService->addQuestionToQuiz($this->quizId, $questionData);

            if (in_array($this->newQuestion['question_type'], ['multiple_choice', 'true_false'])) {
                foreach ($this->newQuestion['answers'] as $answerData) {
                    $question->answers()->create([
                        'answer_text' => $answerData['answer_text'],
                        'is_correct' => $answerData['is_correct'],
                    ]);
                }
            }

            $this->questions = $quizService->getQuizQuestions($this->quizId)->toArray();
            $this->resetNewQuestion();
        }
    }

    public function editQuestion($questionIndex)
    {
        $question = $this->questions[$questionIndex];
        $this->newQuestion = [
            'id' => $question['id'],
            'question_text' => $question['question_text'],
            'question_type' => $question['question_type'],
            'points' => $question['points'],
            'answers' => []
        ];

        if (isset($question['answers'])) {
            foreach ($question['answers'] as $answer) {
                $this->newQuestion['answers'][] = [
                    'id' => $answer['id'],
                    'answer_text' => $answer['answer_text'],
                    'is_correct' => $answer['is_correct']
                ];
            }
        }
    }

    public function updateQuestion()
    {
        $this->validate([
            'newQuestion.question_text' => 'required|string|min:3',
            'newQuestion.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'newQuestion.points' => 'required|integer|min:1',
            'newQuestion.answers.*.answer_text' => 'required_if:newQuestion.question_type,multiple_choice,true_false|string',
        ]);

        $quizService = app(QuizService::class);

        $questionData = [
            'question_text' => $this->newQuestion['question_text'],
            'question_type' => $this->newQuestion['question_type'],
            'points' => $this->newQuestion['points'],
        ];

        $quizService->updateQuestion($this->newQuestion['id'], $questionData);

        if (in_array($this->newQuestion['question_type'], ['multiple_choice', 'true_false'])) {
            $question = $quizService->getQuestion($this->newQuestion['id']);

            // Update existing answers
            foreach ($this->newQuestion['answers'] as $answerData) {
                if (isset($answerData['id'])) {
                    $answer = $question->answers->find($answerData['id']);
                    if ($answer) {
                        $answer->update([
                            'answer_text' => $answerData['answer_text'],
                            'is_correct' => $answerData['is_correct'],
                        ]);
                    }
                } else {
                    $question->answers()->create([
                        'answer_text' => $answerData['answer_text'],
                        'is_correct' => $answerData['is_correct'],
                    ]);
                }
            }
        }

        $this->questions = $quizService->getQuizQuestions($this->quizId)->toArray();
        $this->resetNewQuestion();
    }

    public function deleteQuestion($questionId)
    {
        $quizService = app(QuizService::class);
        $quizService->deleteQuestion($questionId);
        $this->questions = $quizService->getQuizQuestions($this->quizId)->toArray();
    }

    public function resetNewQuestion()
    {
        $this->newQuestion = [
            'question_text' => '',
            'question_type' => 'multiple_choice',
            'points' => 1,
            'answers' => [
                ['answer_text' => '', 'is_correct' => false],
                ['answer_text' => '', 'is_correct' => false],
                ['answer_text' => '', 'is_correct' => false],
                ['answer_text' => '', 'is_correct' => false],
            ]
        ];
    }

    public function saveQuiz()
    {
        $this->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_published' => 'boolean',
        ]);

        $quizService = app(QuizService::class);

        $quizData = [
            'course_id' => $this->course_id,
            'title' => $this->title,
            'description' => $this->description,
            'time_limit' => $this->time_limit,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'passing_score' => $this->passing_score,
            'is_published' => $this->is_published,
        ];

        if ($this->quizId) {
            $quiz = $quizService->updateQuiz($this->quizId, $quizData);
            session()->flash('message', 'Quiz updated successfully.');
        } else {
            $quiz = $quizService->createQuiz($quizData);
            $this->quizId = $quiz->id;
            session()->flash('message', 'Quiz created successfully.');
        }

        return redirect()->route('quizzes.edit', $this->quizId);
    }

    public function render()
    {
        return view('livewire.quiz.create-quiz');
    }
}
