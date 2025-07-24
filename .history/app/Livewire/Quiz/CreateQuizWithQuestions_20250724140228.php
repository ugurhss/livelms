<?php

namespace App\Livewire\Quiz;

use App\Models\Course;
use Livewire\Component;
use App\Services\QuizService;

class CreateQuizWithQuestions extends Component
{
   public $course;
    public $quiz = [
        'title' => '',
        'description' => '',
        'passing_score' => 60,
        'start_date' => null,
        'end_date' => null,
        'is_published' => false
    ];

    public $questions = [];
    public $currentQuestion = [
        'question_text' => '',
        'question_type' => 'multiple_choice',
        'points' => 1,
        'answers' => [
            ['answer_text' => '', 'is_correct' => false]
        ]
    ];

    protected $quizService;

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->addQuestion(); // Başlangıçta bir soru ekle
    }

    public function boot(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'question_text' => '',
            'question_type' => 'multiple_choice',
            'points' => 1,
            'answers' => [
                ['answer_text' => '', 'is_correct' => false]
            ]
        ];
    }

    public function addAnswer($questionIndex)
    {
        $this->questions[$questionIndex]['answers'][] = [
            'answer_text' => '',
            'is_correct' => false
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function removeAnswer($questionIndex, $answerIndex)
    {
        unset($this->questions[$questionIndex]['answers'][$answerIndex]);
        $this->questions[$questionIndex]['answers'] = array_values($this->questions[$questionIndex]['answers']);
    }

    public function saveQuiz()
    {
        $validatedData = $this->validate([
            'quiz.title' => 'required|string|max:255',
            'quiz.passing_score' => 'required|numeric|min:0|max:100',
            'quiz.start_date' => 'nullable|date',
            'quiz.end_date' => 'nullable|date|after_or_equal:quiz.start_date',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'questions.*.points' => 'required|numeric|min:1',
            'questions.*.answers.*.answer_text' => 'required|string',
        ]);

        // Quiz oluştur
        $quiz = $this->quizService->createNewQuiz([
            'course_id' => $this->course->id,
            ...$this->quiz
        ]);

        // Soruları ekle
        foreach ($this->questions as $questionData) {
            $this->quizService->addQuestionToQuiz($quiz->id, $questionData);
        }

        session()->flash('success', 'Quiz ve sorular başarıyla oluşturuldu!');
        return redirect()->route('courses.show', $this->course);
    }

    public function render()
    {
        return view('livewire.quiz.create-quiz-with-questions')
          ;
    }
}
