<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\SubmitQuizRequest;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    private QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
        $this->middleware('auth')->except(['index', 'show']);
    }

    // Admin için quiz yönetimi
    public function index($courseId)
    {
        $quizzes = $this->quizService->getCourseQuizzes($courseId);
        return view('quizzes.index', compact('quizzes', 'courseId'));
    }

    public function create($courseId)
    {
        return view('quizzes.create', compact('courseId'));
    }

    public function store(StoreQuizRequest $request, $courseId)
    {
        $validated = $request->validated();
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

        return view('quizzes.show', compact('quiz', 'courseId'));
    }

    public function edit($courseId, $quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        return view('quizzes.edit', compact('quiz', 'courseId'));
    }

    public function update(StoreQuizRequest $request, $courseId, $quizId)
    {
        $validated = $request->validated();
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
        return view('quizzes.questions.create', compact('quiz', 'courseId'));
    }

    public function storeQuestion(StoreQuestionRequest $request, $courseId, $quizId)
    {
        $validated = $request->validated();
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

    public function submitQuiz(SubmitQuizRequest $request, $courseId, $quizId)
    {
        $validated = $request->validated();
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
