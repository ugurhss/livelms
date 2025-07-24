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
    }

    // Admin için quiz yönetimi
    public function index($courseId)
    {
        $quizzes = $this->quizService->getCourseQuizzes($courseId);
        return view('quiz.index', compact('quizzes', 'courseId'));
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


    public function create()
    {
        // Kurslar veritabanından çekilecek (örnek amaçlı boş bırakıldı)
        $courses = Course::all(); // Örn: Course::all();
        return view('quizzes.create', compact('courses'));
    }

    // Quiz ve soruları kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'quiz_title' => 'required|string|max:255',
            'quiz_description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer',
            'questions.*.answers' => 'required_if:questions.*.type,multiple_choice,true_false|array',
            'questions.*.answers.*.text' => 'required_if:questions.*.type,multiple_choice,true_false|string',
            'questions.*.answers.*.is_correct' => 'required_if:questions.*.type,multiple_choice,true_false|boolean',
        ]);

        // Quiz oluştur
        $quiz = $this->quizService->createNewQuiz([
            'course_id' => $request->course_id,
            'title' => $request->quiz_title,
            'description' => $request->quiz_description,
        ]);

        // Soruları ekle
        foreach ($request->questions as $questionData) {
            $this->quizService->addQuestionToQuiz($quiz->id, [
                'question_text' => $questionData['text'],
                'question_type' => $questionData['type'],
                'answers' => $questionData['answers'] ?? [],
            ]);
        }

        return redirect()->route('quizzes.create')->with('success', 'Quiz ve sorular başarıyla oluşturuldu!');
    }
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
