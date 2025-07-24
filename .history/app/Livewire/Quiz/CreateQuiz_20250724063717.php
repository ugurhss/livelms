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
    public $courses;
    public $quizId = null;
    public $isEditing = false;

    // Quiz form fields
    public $course_id;
    public $title;
    public $description;
    public $is_published = false;
    public $passing_score = 70;
    public $start_date;
    public $end_date;

    // Question related
    public $questions = [];
    public $currentQuestion = [
        'question_text' => '',
        'question_type' => 'multiple_choice',
        'points' => 1,
        'answers' => [
            ['answer_text' => '', 'is_correct' => false]
        ]
    ];
    public $activeQuestionIndex = null;

    protected $rules = [
        'course_id' => 'required|exists:courses,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_published' => 'boolean',
        'passing_score' => 'required|numeric|min:0|max:100',
        'start_date' => 'nullable|date|after_or_equal:now',
        'end_date' => 'nullable|date|after:start_date',
        'questions.*.question_text' => 'required|string',
        'questions.*.question_type' => 'required|in:multiple_choice,true_false,short_answer',
        'questions.*.points' => 'required|numeric|min:1',
        'questions.*.answers.*.answer_text' => 'required|string',
    ];

    protected $messages = [
        'questions.*.question_text.required' => 'Soru metni gereklidir.',
        'questions.*.answers.*.answer_text.required' => 'Cevap metni gereklidir.',
        'passing_score.required' => 'Geçme notu gereklidir.',
        'passing_score.numeric' => 'Geçme notu sayı olmalıdır.',
        'passing_score.min' => 'Geçme notu 0-100 arasında olmalıdır.',
        'passing_score.max' => 'Geçme notu 0-100 arasında olmalıdır.',
    ];

    public function mount($courseId = null )
    {

        $this->course_id = $courseId;
        $this->courses = Course::all();

        if ($quizId) {
            $this->editQuiz($quizId);
        }
    }

    public function editQuiz($quizId)
    {
        $quizService = app(QuizService::class);
        $quiz = $quizService->getQuizDetails($quizId);

        $this->quizId = $quizId;
        $this->isEditing = true;

        // Set quiz properties
        $this->course_id = $quiz->course_id;
        $this->title = $quiz->title;
        $this->description = $quiz->description;
        $this->is_published = $quiz->is_published;
        $this->passing_score = $quiz->passing_score;
        $this->start_date = optional($quiz->start_date)->format('Y-m-d\TH:i');
        $this->end_date = optional($quiz->end_date)->format('Y-m-d\TH:i');

        // Load questions
        $this->questions = $quiz->questions->map(function($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'points' => $question->points,
                'answers' => $question->answers->map(function($answer) {
                    return [
                        'id' => $answer->id,
                        'answer_text' => $answer->answer_text,
                        'is_correct' => $answer->is_correct
                    ];
                })->toArray()
            ];
        })->toArray();
    }

    public function addQuestion()
    {
        $this->validate([
            'currentQuestion.question_text' => 'required',
            'currentQuestion.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'currentQuestion.points' => 'required|numeric|min:1',
        ]);

        $question = [
            'id' => Str::random(10),
            'question_text' => $this->currentQuestion['question_text'],
            'question_type' => $this->currentQuestion['question_type'],
            'points' => $this->currentQuestion['points'],
            'answers' => $this->currentQuestion['answers']
        ];

        $this->questions[] = $question;
        $this->resetCurrentQuestion();
    }

    public function editQuestion($index)
    {
        $this->activeQuestionIndex = $index;
        $this->currentQuestion = $this->questions[$index];
    }

    public function updateQuestion()
    {
        $this->validate([
            'currentQuestion.question_text' => 'required',
            'currentQuestion.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'currentQuestion.points' => 'required|numeric|min:1',
        ]);

        $this->questions[$this->activeQuestionIndex] = $this->currentQuestion;
        $this->resetCurrentQuestion();
        $this->activeQuestionIndex = null;
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addAnswer()
    {
        $this->currentQuestion['answers'][] = [
            'answer_text' => '',
            'is_correct' => false
        ];
    }

    public function removeAnswer($index)
    {
        unset($this->currentQuestion['answers'][$index]);
        $this->currentQuestion['answers'] = array_values($this->currentQuestion['answers']);
    }

    public function resetCurrentQuestion()
    {
        $this->currentQuestion = [
            'question_text' => '',
            'question_type' => 'multiple_choice',
            'points' => 1,
            'answers' => [
                ['answer_text' => '', 'is_correct' => false]
            ]
        ];
    }

    public function saveQuiz()
    {
        $this->validate();

        $quizData = [
            'course_id' => $this->course_id,
            'title' => $this->title,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'passing_score' => $this->passing_score,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        $quizService = app(QuizService::class);

        if ($this->isEditing) {
            $quiz = $quizService->updateQuizDetails($this->quizId, $quizData);
        } else {
            $quiz = $quizService->createNewQuiz($quizData);
            $this->quizId = $quiz->id;
        }

        // Save questions
        foreach ($this->questions as $questionData) {
            $questionServiceData = [
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'points' => $questionData['points'],
                'answers' => $questionData['answers']
            ];

            if (!isset($questionData['id']) || !is_numeric($questionData['id'])) {
                $quizService->addQuestionToQuiz($this->quizId, $questionServiceData);
            }
        }

        session()->flash('message', 'Quiz başarıyla kaydedildi.');
        return redirect()->route('courses.quizzes.show', [$this->course_id, $this->quizId]);
    }

    public function render()
    {
        return view('livewire.quiz.create-quiz');
    }
}
