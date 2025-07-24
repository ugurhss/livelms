<?php

namespace App\Livewire\Quiz;

use Livewire\Component;
use App\Services\QuizService;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
    ];

    public function mount($quizId = null)
    {
        Log::debug('CreateQuiz mount called', ['quizId' => $quizId]);

        try {
            $this->courses = Course::all();
            Log::debug('Courses loaded', ['count' => $this->courses->count()]);

            if ($quizId) {
                $this->editQuiz($quizId);
            }
        } catch (\Exception $e) {
            Log::error('Mount error', ['error' => $e->getMessage()]);
            session()->flash('error', 'Bir hata oluştu: '.$e->getMessage());
        }
    }

    public function editQuiz($quizId)
    {
        Log::debug('Editing quiz', ['quizId' => $quizId]);

        try {
            $quizService = app(QuizService::class);
            $quiz = $quizService->getQuizDetails($quizId);

            Log::debug('Quiz details loaded', ['quiz' => $quiz->toArray()]);

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

            Log::debug('Questions loaded', ['count' => count($this->questions)]);
        } catch (\Exception $e) {
            Log::error('Edit quiz error', ['error' => $e->getMessage()]);
            session()->flash('error', 'Quiz yüklenirken hata: '.$e->getMessage());
        }
    }

    public function addQuestion()
    {
        Log::debug('Adding question', ['currentQuestion' => $this->currentQuestion]);

        try {
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
            Log::debug('Question added', ['newQuestion' => $question, 'totalQuestions' => count($this->questions)]);

            $this->resetCurrentQuestion();
        } catch (\Exception $e) {
            Log::error('Add question error', ['error' => $e->getMessage()]);
            session()->flash('error', 'Soru eklenirken hata: '.$e->getMessage());
        }
    }

    public function updateQuestion()
    {
        Log::debug('Updating question', [
            'index' => $this->activeQuestionIndex,
            'currentQuestion' => $this->currentQuestion
        ]);

        try {
            $this->validate([
                'currentQuestion.question_text' => 'required',
                'currentQuestion.question_type' => 'required|in:multiple_choice,true_false,short_answer',
                'currentQuestion.points' => 'required|numeric|min:1',
            ]);

            $this->questions[$this->activeQuestionIndex] = $this->currentQuestion;
            Log::debug('Question updated', [
                'index' => $this->activeQuestionIndex,
                'updatedQuestion' => $this->currentQuestion
            ]);

            $this->resetCurrentQuestion();
            $this->activeQuestionIndex = null;
        } catch (\Exception $e) {
            Log::error('Update question error', ['error' => $e->getMessage()]);
            session()->flash('error', 'Soru güncellenirken hata: '.$e->getMessage());
        }
    }

    public function saveQuiz()
    {
        Log::debug('Saving quiz', [
            'isEditing' => $this->isEditing,
            'quizData' => [
                'course_id' => $this->course_id,
                'title' => $this->title,
                'description' => $this->description,
                'is_published' => $this->is_published,
                'passing_score' => $this->passing_score,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ],
            'questionsCount' => count($this->questions)
        ]);

        try {
            // DD ile form verisini kontrol et
            // dd([
            //     'Form Data' => $this->all(),
            //     'Validation Errors' => $this->getErrorBag()->toArray()
            // ]);

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
                Log::debug('Updating quiz', ['quizId' => $this->quizId]);
                $quiz = $quizService->updateQuizDetails($this->quizId, $quizData);
            } else {
                Log::debug('Creating new quiz');
                $quiz = $quizService->createNewQuiz($quizData);
                $this->quizId = $quiz->id;
            }

            Log::debug('Quiz saved', ['quizId' => $this->quizId]);

            // Save questions
            foreach ($this->questions as $index => $questionData) {
                $questionServiceData = [
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'points' => $questionData['points'],
                    'answers' => $questionData['answers']
                ];

                if (isset($questionData['id']) && is_numeric($questionData['id'])) {
                    Log::debug('Updating existing question', [
                        'questionId' => $questionData['id'],
                        'data' => $questionServiceData
                    ]);
                    // Update existing question
                    // $quizService->updateQuestion($questionData['id'], $questionServiceData);
                } else {
                    Log::debug('Adding new question', [
                        'quizId' => $this->quizId,
                        'data' => $questionServiceData
                    ]);
                    $quizService->addQuestionToQuiz($this->quizId, $questionServiceData);
                }
            }

            session()->flash('message', 'Quiz başarıyla kaydedildi.');
            Log::info('Quiz saved successfully', ['quizId' => $this->quizId]);

            return redirect()->route('quizzes.show', $this->quizId);
        } catch (\Exception $e) {
            Log::error('Save quiz error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Quiz kaydedilirken hata oluştu: '.$e->getMessage());

            // Hata durumunda form verisini görmek için
            // dd([
            //     'error' => $e->getMessage(),
            //     'quizData' => $quizData ?? null,
            //     'questions' => $this->questions
            // ]);
        }
    }

    // ... Diğer metodlar (removeQuestion, addAnswer, removeAnswer, resetCurrentQuestion) aynı kalacak
    // Sadece try-catch ve log ekleyebilirsiniz

    public function render()
    {
        // Debug için render öncesi verileri kontrol et
        // dd([
        //     'questions' => $this->questions,
        //     'currentQuestion' => $this->currentQuestion,
        //     'activeQuestionIndex' => $this->activeQuestionIndex
        // ]);

        return view('livewire.quiz.create-quiz');
    }
}
