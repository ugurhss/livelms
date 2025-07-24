<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\QuizService;

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

// Quiz oluşturma formunu göster
    public function create()
    {
        $courses = Course::all();
        return view('quiz.deneme', compact('courses'));
    }

    // Quiz kaydet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.answer_text' => 'required|string',
            'questions.*.answers.*.is_correct' => 'required_if:questions.*.question_type,multiple_choice,true_false|boolean',
        ]);

        // Quiz oluştur
        $quiz = $this->quizService->createNewQuiz([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'time_limit' => $validated['time_limit'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'passing_score' => $validated['passing_score'],
            'is_published' => $validated['is_published'] ?? false,
        ]);

        // Soruları ekle
        foreach ($validated['questions'] as $questionData) {
            $this->quizService->addQuestionToQuiz($quiz->id, $questionData);
        }

        return redirect()->route('quizzes.show', $quiz->id)
            ->with('success', 'Quiz başarıyla oluşturuldu!');
    }

    // Quiz düzenleme formunu göster
    public function edit($id)
    {
        $quiz = $this->quizService->getQuizDetails($id);
        $courses = Course::all();
        return view('quizzes.edit', compact('quiz', 'courses'));
    }

    // Quiz güncelle
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean',
        ]);

        $this->quizService->updateQuizDetails($id, $validated);

        return redirect()->route('quizzes.show', $id)
            ->with('success', 'Quiz başarıyla güncellendi!');
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
        return view('quiz.questionsCreate', compact('quiz', 'courseId','quizId'));
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

        return view('quiz.takes', compact('quiz', 'courseId','quizId'));
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
    try {
        $userId = Auth()->id();

        $result = $this->quizService->getQuizAttempt($attemptId);

        if ($result->user_id != $userId || $result->quiz_id != $quizId) {
            abort(403, 'Bu sonucu görüntüleme yetkiniz yok.');
        }

        // Eksik değişkenleri ekle
        return view('quiz.result', compact('result', 'courseId', 'quizId', 'attemptId'));
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Sonuç bulunamadı: ' . $e->getMessage());
    }
}

}
