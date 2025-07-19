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
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index($courseId)
    {
        $quizzes = $this->quizService->getCourseQuizzes($courseId);
        return response()->json($quizzes);
    }

    public function show($quizId)
    {
        $quiz = $this->quizService->getQuizDetails($quizId);
        $availability = $this->quizService->checkQuizAccess($quizId);

        return response()->json([
            'quiz' => $quiz,
            'availability' => $availability
        ]);
    }

    public function store(StoreQuizRequest $request)
    {
        $validated = $request->validated();
        $quiz = $this->quizService->createNewQuiz($validated);

        return response()->json($quiz, 201);
    }

    public function update(StoreQuizRequest $request, $quizId)
    {
        $validated = $request->validated();
        $quiz = $this->quizService->updateQuizDetails($quizId, $validated);

        return response()->json($quiz);
    }

    public function destroy($quizId)
    {
        $this->quizService->deleteQuiz($quizId);
        return response()->json(null, 204);
    }

    public function addQuestion(StoreQuestionRequest $request, $quizId)
    {
        $validated = $request->validated();
        $question = $this->quizService->addQuestionToQuiz($quizId, $validated);

        return response()->json($question, 201);
    }

    public function submitQuiz(SubmitQuizRequest $request, $quizId)
    {
        $validated = $request->validated();
        $userId = auth()->id();

        try {
            $result = $this->quizService->submitQuiz($quizId, $userId, $validated['answers']);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getResult($quizId)
    {
        $userId = auth()->id();
        $result = $this->quizService->getQuizResult($quizId, $userId);

        return response()->json($result);
    }
}
