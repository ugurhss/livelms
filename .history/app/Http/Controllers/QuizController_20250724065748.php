<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
        // $this->middleware('auth')->except(['index', 'show']); // Sadece temel auth kontrolü
    }

    public function index($courseId)
    {
        $quizzes = $this->quizService->getCourseQuizzes($courseId);
        return view('quiz.index', compact('quizzes', 'courseId'));
    }

   public function create($courseId)
    {
        return view('quiz.create', compact('courseId'));
    }


     public function store(Request $request, $courseId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'required|boolean',
        ]);

        $validated['course_id'] = $courseId;
        $this->quizService->createNewQuiz($validated);

        return redirect()->route('courses.quizzes.index', $courseId)
                         ->with('success', 'Quiz başarıyla oluşturuldu.');
    }


    public function show($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        $availability = $this->quizService->checkQuizAccess($quizId, Auth::id());

        if (!$availability['available']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        return view('quiz.show', compact('quiz', 'courseId', 'quizId'));
    }

     public function edit($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);

        // Quiz'in bu kursa ait olduğunu kontrol et
        if ($quiz->course_id != $courseId) {
            abort(404, 'Quiz bulunamadı veya bu kursa ait değil');
        }

        return view('quiz.edit', compact('quiz', 'courseId', 'quizId'));
    }

      public function update(Request $request, $courseId, $quizId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'required|boolean',
        ]);

        $quiz = $this->quizService->getQuizDetails($quizId);

        // Quiz'in bu kursa ait olduğunu kontrol et
        if ($quiz->course_id != $courseId) {
            abort(404, 'Quiz bulunamadı veya bu kursa ait değil');
        }

        $this->quizService->updateQuizDetails($quizId, $validated);

        return redirect()->route('courses.quizzes.show', [$courseId, $quizId])
                         ->with('success', 'Quiz başarıyla güncellendi.');
    }

    public function destroy($courseId, $quizId)
    {
        $this->quizService->deleteQuiz($quizId);
        return redirect()->route('courses.quizzes.index', $courseId)
                         ->with('success', 'Quiz başarıyla silindi.');
    }

    public function createQuestion($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        return view('quiz.questions.create', compact('quiz', 'courseId', 'quizId'));
    }

    public function storeQuestion(Request $request, $courseId, $quizId)
    {
        $validated = $this->validateQuestionRequest($request, $quizId);
        $this->quizService->addQuestionToQuiz($quizId, $validated);

        return redirect()->route('courses.quizzes.show', [$courseId, $quizId])
                         ->with('success', 'Soru başarıyla eklendi.');
    }

    public function startQuiz($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        $availability = $this->quizService->checkQuizAccess($quizId, Auth::id());

        if (!$availability['available']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        if ($this->quizService->hasUserCompletedQuiz($quizId, Auth::id())) {
            return redirect()->back()
                ->with('error', 'Bu quizi zaten tamamladınız.');
        }

        return view('quiz.takes', compact('quiz', 'courseId', 'quizId'));
    }

    public function submitQuiz(Request $request, $courseId, $quizId)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required',
        ]);

        try {
            $result = $this->quizService->submitQuiz(
                $quizId,
                Auth::id(),
                $validated['answers']
            );

            return redirect()->route('courses.quizzes.result', [
                $courseId, $quizId, $result->id
            ])->with('success', 'Quiz tamamlandı!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Quiz gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    public function showResult($courseId, $quizId, $attemptId)
    {
        try {
            $result = $this->quizService->getQuizAttempt($attemptId);

            if ($result->user_id !== Auth::id() || $result->quiz_id != $quizId) {
                abort(403, 'Bu sonucu görüntüleme yetkiniz yok.');
            }

            return view('quiz.result', compact('result', 'courseId', 'quizId', 'attemptId'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Sonuç bulunamadı: ' . $e->getMessage());
        }
    }

    protected function validateQuestionRequest(Request $request, $quizId)
    {
        $rules = [
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|integer|min:1',
        ];

        if (in_array($request->question_type, ['multiple_choice', 'true_false'])) {
            $rules['answers'] = 'required|array|min:1';
            $rules['answers.*.answer_text'] = 'required|string';
            $rules['answers.*.is_correct'] = 'required|boolean';
        }

        return $request->validate($rules);
    }
}
