<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
        // $this->middleware('auth')->except(['index', 'show']);
    }

    // Admin için quiz yönetimi
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
            'time_limit' => 'nullable|integer',
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
    $availability = $this->quizService->checkQuizAccess($quizId);

    if (!$availability['available']) {
        return redirect()->back()->with('error', $availability['message']);
    }

    return view('quiz.show', [
        'quiz' => $quiz,
        'courseId' => $courseId,
        'quizId' => $quizId // Make sure to pass quizId to the view
    ]);
}


    public function edit($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        return view('quiz.edit', compact('quiz', 'courseId'));
    }

    public function update(Request $request, $courseId, $quizId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'required|boolean',
        ]);

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

    // Quiz soruları yönetimi
    public function createQuestion($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        return view('quiz.questionsCreate', compact('quiz', 'courseId'));
    }

    public function storeQuestion(Request $request, $courseId, $quizId)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'points' => 'required|integer|min:1',
        ]);

        $this->quizService->addQuestionToQuiz($quizId, $validated);

        return redirect()->route('courses.quizzes.show', [$courseId, $quizId])
                         ->with('success', 'Soru başarıyla eklendi.');
    }

    // Quiz katılımı
    public function startQuiz($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        $availability = $this->quizService->checkQuizAccess($quizId);

        if (!$availability['available']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        return view('quizzes.take', compact('quiz', 'courseId'));
    }

    public function submitQuiz(Request $request, $courseId, $quizId)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            // İstersen burada answers içeriğini daha detaylı doğrulayabilirsin
        ]);

        $userId = auth()->id();

        try {
            $result = $this->quizService->submitQuiz($quizId, $userId, $validated['answers']);
            return redirect()->route('courses.quizzes.result', [$courseId, $quizId, $result->id])
                             ->with('success', 'Quiz tamamlandı!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function showResult($courseId, $quizId, $attemptId)
    {
        $userId = auth()->id();
        $result = $this->quizService->getQuizResults($quizId, $userId);

        if ($result->id != $attemptId) {
            abort(403, 'Bu sonucu görüntüleme yetkiniz yok.');
        }

        return view('quizzes.result', compact('result', 'courseId'));
    }
}
